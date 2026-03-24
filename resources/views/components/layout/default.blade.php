@php
    $menuData = json_decode(file_get_contents(config_path('menu.json')), true);
    $allMenuItems = $menuData['data'] ?? [];
    $entityConfigFiles = array_merge(
        glob(config_path('entities/*.php')) ?: [],
        glob(config_path('entities/*/*.php')) ?: []
    );
    $entityAclMap = collect($entityConfigFiles)
        ->map(function ($file) {
            $relative = str_replace(config_path('entities') . DIRECTORY_SEPARATOR, '', $file);
            $relative = str_replace(DIRECTORY_SEPARATOR, '/', $relative);
            $configKey = str_replace(['/', '.php'], ['.', ''], $relative);
            $config = config('entities.' . $configKey, []);
            $api = data_get($config, 'common.api');
            $apiPath = parse_url((string) $api, PHP_URL_PATH) ?: (string) $api;
            $apiPath = trim((string) $apiPath, '/');
            $apiResource = $apiPath ? basename($apiPath) : null;
            $shortname = data_get($config, 'common.shortname', $configKey);
            $shortnameTail = $shortname ? collect(explode('.', (string) $shortname))->last() : null;

            return [
                'entity' => $configKey,
                'page' => data_get($config, 'common.page'),
                'shortname' => $shortname,
                'resource' => data_get($config, 'common.resource') ?: $shortnameTail ?: $apiResource,
            ];
        })
        ->values();
    $refIds = array_column(
        array_filter($allMenuItems, fn($item) => str_ends_with($item['shortname'] ?? '', '.references')),
        'id'
    );
    $lookupItems = array_values(array_map(
        fn($item) => ['shortname' => $item['shortname'], 'api' => $item['api']],
        array_filter($allMenuItems, fn($item) =>
            in_array($item['parent_id'] ?? null, $refIds) &&
            !empty($item['api']) &&
            ($item['status'] ?? 0)
        )
    ));
@endphp
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset='utf-8' />
    <meta http-equiv='X-UA-Compatible' content='IE=edge' />
    <title>{{ $title ?? 'VRISTO - Multipurpose Tailwind Dashboard Template' }}</title>

    <meta name='viewport' content='width=device-width, initial-scale=1' />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" type="image/svg" href="/assets/images/favicon.svg" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap"
          rel="stylesheet" />
{{--    <link--}}
{{--        rel="stylesheet"--}}
{{--        href="https://unicons.iconscout.com/release/v4.0.8/css/line.css"--}}
{{--    />--}}
    <link rel="stylesheet" href="/assets/unicons/css/line.css">

    {{-- 🔐 AUTH GUARD (SPA) --}}
    <script>
        (function () {
            const token = localStorage.getItem('access_token');

            if (!token) {
                window.location.replace('/login');
            }
        })();
    </script>

    <script>
        window.ENTITY_ACL_MAP = @json($entityAclMap);

        window.AuthACL = (() => {
            const TOKEN_KEY = 'access_token';
            const PROFILE_KEY = 'user';
            const API_URL = '{{ rtrim(config('app.api_url'), '/') }}';
            const MAIN_USER_API = '{{ rtrim(config('entities.main.users.common.api'), '/') }}';
            const entityMap = Array.isArray(window.ENTITY_ACL_MAP) ? window.ENTITY_ACL_MAP : [];
            let ensureProfilePromise = null;
            let profileHydratedForPage = false;

            const normalizePath = (value) => {
                const path = String(value ?? '').trim();
                if (!path) return '';

                return path
                    .replace(/^https?:\/\/[^/]+/i, '')
                    .replace(/\/+$/, '')
                    .toLowerCase();
            };

            const parseProfile = (value) => {
                if (!value) return null;

                try {
                    const parsed = JSON.parse(value);
                    if (!parsed || typeof parsed !== 'object') return null;

                    parsed.permissions = Array.isArray(parsed.permissions) ? parsed.permissions : [];
                    parsed.roles = Array.isArray(parsed.roles) ? parsed.roles : [];

                    return parsed;
                } catch (error) {
                    console.warn('Failed to parse user profile from localStorage', error);
                    return null;
                }
            };

            const readProfile = () => parseProfile(localStorage.getItem(PROFILE_KEY));

            const writeProfile = (profile) => {
                if (!profile || typeof profile !== 'object') return null;

                const normalized = {
                    ...profile,
                    permissions: Array.isArray(profile.permissions) ? profile.permissions : [],
                    roles: Array.isArray(profile.roles) ? profile.roles : [],
                };

                localStorage.setItem(PROFILE_KEY, JSON.stringify(normalized));
                window.dispatchEvent(new CustomEvent('auth-profile-updated', { detail: normalized }));

                return normalized;
            };

            const normalizeProfilePayload = (payload) => {
                const profile = payload?.data?.user
                    ?? payload?.data?.profile
                    ?? payload?.data
                    ?? payload?.user
                    ?? payload
                    ?? null;

                if (!profile || typeof profile !== 'object' || Array.isArray(profile)) {
                    return null;
                }

                if (!Array.isArray(profile.permissions) && Array.isArray(payload?.data?.permissions)) {
                    profile.permissions = payload.data.permissions;
                }

                if (!Array.isArray(profile.roles) && Array.isArray(payload?.data?.roles)) {
                    profile.roles = payload.data.roles;
                }

                return writeProfile(profile);
            };

            const candidateProfileUrls = () => {
                const urls = [
                    `${API_URL}/api/v1/users/me`,
                    `${API_URL}/api/user/me`,
                    `${API_URL}/api/me`,
                    `${MAIN_USER_API}/me`,
                ];

                return urls.filter(Boolean).filter((value, index, array) => array.indexOf(value) === index);
            };

            const fetchProfile = async () => {
                const token = localStorage.getItem(TOKEN_KEY);
                if (!token) return null;

                for (const url of candidateProfileUrls()) {
                    try {
                        const response = await fetch(url, {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'Authorization': `Bearer ${token}`,
                            },
                        });

                        if (!response.ok) continue;

                        const payload = await response.json();
                        const profile = normalizeProfilePayload(payload);
                        if (profile) return profile;
                    } catch (error) {
                        console.warn('Unable to fetch profile from', url, error);
                    }
                }

                return readProfile();
            };

            const permissionName = (permission) => {
                return String(
                    permission?.shortname
                    ?? permission?.slug
                    ?? permission?.name
                    ?? permission
                    ?? ''
                ).trim();
            };

            const permissionsSet = (profile = readProfile()) => {
                return new Set(
                    (profile?.permissions ?? [])
                        .map(permissionName)
                        .filter(Boolean)
                );
            };

            const resolveResource = (target) => {
                if (!target) return null;

                if (typeof target === 'string') {
                    return target;
                }

                if (target.resource) {
                    return target.resource;
                }

                const normalizedPage = normalizePath(target.page);
                const normalizedShortname = String(target.shortname ?? '').trim().toLowerCase();

                const matched = entityMap.find(item => {
                    return (
                        (normalizedPage && normalizePath(item.page) === normalizedPage) ||
                        (normalizedShortname && String(item.shortname ?? '').trim().toLowerCase() === normalizedShortname)
                    );
                });

                return matched?.resource ?? null;
            };

            const hasPermission = (permission, profile = readProfile()) => {
                if (!permission) return false;
                return permissionsSet(profile).has(String(permission).trim());
            };

            const hasAnyPermission = (permissions, profile = readProfile()) => {
                const list = Array.isArray(permissions) ? permissions : [];
                if (!list.length) return false;

                return list.some(permission => hasPermission(permission, profile));
            };

            const hasAllPermissions = (permissions, profile = readProfile()) => {
                const list = Array.isArray(permissions) ? permissions : [];
                if (!list.length) return true;

                return list.every(permission => hasPermission(permission, profile));
            };

            const hasResourcePermission = (target, action, profile = readProfile()) => {
                const resource = resolveResource(target);
                if (!resource || !action) return false;

                return hasPermission(`${resource}.${action}`, profile);
            };

            const hasAnyResourcePermission = (target, actions, profile = readProfile()) => {
                const list = Array.isArray(actions) ? actions : [];
                if (!list.length) return false;

                return list.some(action => hasResourcePermission(target, action, profile));
            };

            const ensureProfile = async (force = false) => {
                const existing = readProfile();
                const shouldRefresh = force || !profileHydratedForPage;

                if (!shouldRefresh && existing) {
                    return existing;
                }

                if (!ensureProfilePromise || shouldRefresh) {
                    ensureProfilePromise = fetchProfile()
                        .then(profile => {
                            profileHydratedForPage = true;
                            return profile;
                        })
                        .catch(error => {
                            console.warn('Unable to load current user profile', error);
                            profileHydratedForPage = true;
                            return readProfile();
                        })
                        .finally(() => {
                            ensureProfilePromise = null;
                        });
                }

                return ensureProfilePromise;
            };

            return {
                ensureProfile,
                readProfile,
                writeProfile,
                normalizeProfilePayload,
                permissionsSet,
                resolveResource,
                hasPermission,
                hasAnyPermission,
                hasAllPermissions,
                hasResourcePermission,
                hasAnyResourcePermission,
            };
        })();
    </script>

    <script src="/assets/js/perfect-scrollbar.min.js"></script>
    <script defer src="/assets/js/popper.min.js"></script>
    <script defer src="/assets/js/tippy-bundle.umd.min.js"></script>
    <script defer src="/assets/js/sweetalert.min.js"></script>

    @vite(['resources/css/app.css'])
</head>

<body x-data="main"
      class="antialiased relative font-nunito text-sm font-normal overflow-x-hidden"
      :class="[
          $store.app.sidebar ? 'toggle-sidebar' : '',
          $store.app.theme === 'dark' || $store.app.isDarkMode ? 'dark' : '',
          $store.app.menu,
          $store.app.layout,
          $store.app.rtlClass
      ]">

<!-- sidebar menu overlay -->
<div x-cloak
     class="fixed inset-0 bg-[black]/60 z-50 lg:hidden"
     :class="{ 'hidden': !$store.app.sidebar }"
     @click="$store.app.toggleSidebar()">
</div>

<!-- screen loader -->
<div
    class="screen_loader fixed inset-0 bg-[#fafafa] dark:bg-[#060818] z-[60] grid place-content-center animate__animated">
    <svg width="64" height="64" viewBox="0 0 135 135" xmlns="http://www.w3.org/2000/svg" fill="#4361ee">
        <path
            d="M67.447 58c5.523 0 10-4.477 10-10s-4.477-10-10-10-10 4.477-10 10 4.477 10 10 10zm9.448 9.447c0 5.523 4.477 10 10 10 5.522 0 10-4.477 10-10s-4.478-10-10-10c-5.523 0-10 4.477-10 10zm-9.448 9.448c-5.523 0-10 4.477-10 10 0 5.522 4.477 10 10 10s10-4.478 10-10c0-5.523-4.477-10-10-10zM58 67.447c0-5.523-4.477-10-10-10s-10 4.477-10 10 4.477 10 10 10 10-4.477 10-10z">
            <animateTransform attributeName="transform" type="rotate"
                              from="0 67 67" to="-360 67 67"
                              dur="2.5s" repeatCount="indefinite" />
        </path>
        <path
            d="M28.19 40.31c6.627 0 12-5.374 12-12 0-6.628-5.373-12-12-12-6.628 0-12 5.372-12 12 0 6.626 5.372 12 12 12zm30.72-19.825c4.686 4.687 12.284 4.687 16.97 0 4.686-4.686 4.686-12.284 0-16.97-4.686-4.687-12.284-4.687-16.97 0-4.687 4.686-4.687 12.284 0 16.97zm35.74 7.705c0 6.627 5.37 12 12 12 6.626 0 12-5.373 12-12 0-6.628-5.374-12-12-12-6.63 0-12 5.372-12 12zm19.822 30.72c-4.686 4.686-4.686 12.284 0 16.97 4.687 4.686 12.285 4.686 16.97 0 4.687-4.686 4.687-12.284 0-16.97-4.685-4.687-12.283-4.687-16.97 0zm-7.704 35.74c-6.627 0-12 5.37-12 12 0 6.626 5.373 12 12 12s12-5.374 12-12c0-6.63-5.373-12-12-12zm-30.72 19.822c-4.686-4.686-12.284-4.686-16.97 0-4.686 4.687-4.686 12.285 0 16.97 4.686 4.687 12.284 4.687 16.97 0 4.687-4.685 4.687-12.283 0-16.97zm-35.74-7.704c0-6.627-5.372-12-12-12-6.626 0-12 5.373-12 12s5.374 12 12 12c6.628 0 12-5.373 12-12zm-19.823-30.72c4.687-4.686 4.687-12.284 0-16.97-4.686-4.686-12.284-4.686-16.97 0-4.687 4.686-4.687 12.284 0 16.97 4.686 4.687 12.284 4.687 16.97 0z">
            <animateTransform attributeName="transform" type="rotate"
                              from="0 67 67" to="360 67 67"
                              dur="8s" repeatCount="indefinite" />
        </path>
    </svg>
</div>

<div class="main-container text-black dark:text-white-dark min-h-screen"
     :class="[$store.app.navbar]">

    <x-common.sidebar />

    <div class="main-content flex flex-col min-h-screen">
        <x-common.header />

        <div class="dvanimation p-6 animate__animated"
             :class="[$store.app.animation]">
            {{ $slot }}
        </div>

        <x-common.footer />
    </div>
</div>

<script src="/assets/js/alpine-collaspe.min.js"></script>
<script src="/assets/js/alpine-persist.min.js"></script>
<script defer src="/assets/js/alpine-ui.min.js"></script>
<script defer src="/assets/js/alpine-focus.min.js"></script>
<script defer src="/assets/js/alpine.min.js"></script>
<script src="/assets/js/custom.js"></script>

{{-- 📊 Excel Export Global Poller — работает на любой странице --}}
<style>
    @keyframes slideInToast {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>
<script>
window.ExcelPoller = (function () {
    const KEY = 'pending_excel_export';
    let timer = null;

    function showToast(type, message, downloadUrl) {
        const existing = document.getElementById('excel-export-toast');
        if (existing) existing.remove();

        const isSuccess = type === 'success';
        const bgColor   = isSuccess ? '#1a7f37' : '#c0392b';
        const icon      = isSuccess ? '✓' : '✕';

        const toast = document.createElement('div');
        toast.id = 'excel-export-toast';
        toast.style.cssText = [
            'position:fixed', 'bottom:24px', 'right:24px', 'z-index:99999',
            'min-width:280px', 'max-width:380px', 'background:' + bgColor,
            'color:#fff', 'border-radius:12px', 'padding:14px 18px',
            'box-shadow:0 8px 30px rgba(0,0,0,0.25)', 'display:flex',
            'flex-direction:column', 'gap:6px', 'font-size:14px',
            'animation:slideInToast .3s ease',
        ].join(';');

        toast.innerHTML =
            '<div style="display:flex;align-items:center;gap:10px;font-weight:600;">' +
                '<span style="font-size:18px;line-height:1;">' + icon + '</span>' +
                '<span>' + message + '</span>' +
                '<button onclick="document.getElementById(\'excel-export-toast\').remove()" ' +
                        'style="margin-left:auto;background:transparent;border:0;color:#fff;font-size:18px;cursor:pointer;line-height:1;">×</button>' +
            '</div>' +
            (downloadUrl
                ? '<a href="' + downloadUrl + '" target="_blank" ' +
                    'style="display:inline-block;margin-top:4px;padding:6px 14px;background:rgba(255,255,255,0.2);border-radius:8px;color:#fff;text-decoration:none;font-weight:600;font-size:13px;text-align:center;">' +
                    '⬇ Скачать Excel</a>'
                : '');

        document.body.appendChild(toast);
        setTimeout(() => toast && toast.remove(), isSuccess ? 10000 : 5000);
    }

    function doPoll(exportId) {
        if (timer) clearInterval(timer);
        timer = setInterval(async function () {
            try {
                const resp = await fetch('/api/export/status/' + exportId, {
                    headers: { 'Accept': 'application/json' },
                });
                const data = await resp.json();
                if (data.status === 'done') {
                    clearInterval(timer); timer = null;
                    localStorage.removeItem(KEY);
                    document.dispatchEvent(new CustomEvent('excel-export-done', { detail: data }));
                    showToast('success', 'Отчёт Excel сформирован', data.download_url);
                } else if (data.status === 'failed') {
                    clearInterval(timer); timer = null;
                    localStorage.removeItem(KEY);
                    document.dispatchEvent(new CustomEvent('excel-export-failed', { detail: data }));
                    showToast('error', 'Ошибка формирования отчёта: ' + (data.error || ''));
                }
            } catch (e) { /* keep polling on network errors */ }
        }, 3000);
    }

    return {
        start(exportId) {
            localStorage.setItem(KEY, exportId);
            doPoll(exportId);
        },
        init() {
            const id = localStorage.getItem(KEY);
            if (id) doPoll(id);
        },
        isPending() {
            return !!localStorage.getItem(KEY);
        },
        showToast,
    };
})();

document.addEventListener('DOMContentLoaded', function () { window.ExcelPoller.init(); });
</script>

{{-- 🗂 Кэш справочников --}}
<script>
window.LookupCache = (function () {
    const CACHE_TTL = 30 * 60 * 1000; // 30 минут
    const KEY_PREFIX = 'lookup:';

    function cacheKey(api) { return KEY_PREFIX + api; }

    function get(api) {
        try {
            const raw = localStorage.getItem(cacheKey(api));
            if (!raw) return null;
            const entry = JSON.parse(raw);
            if (Date.now() - (entry.cached_at || 0) > CACHE_TTL) {
                localStorage.removeItem(cacheKey(api));
                return null;
            }
            return entry.data;
        } catch (e) { return null; }
    }

    function set(api, data) {
        try {
            localStorage.setItem(cacheKey(api), JSON.stringify({
                data: Array.isArray(data) ? data : [],
                cached_at: Date.now()
            }));
        } catch (e) {
            console.warn('LookupCache: не удалось сохранить в localStorage', e);
        }
    }

    function addItem(api, item) {
        const data = get(api);
        if (data !== null) set(api, [...data, item]);
    }

    function updateItem(api, id, item) {
        const data = get(api);
        if (data !== null) {
            const idx = data.findIndex(r => String(r.id) === String(id));
            if (idx >= 0) {
                const updated = [...data];
                updated[idx] = Object.assign({}, updated[idx], item);
                set(api, updated);
            } else {
                set(api, [...data, item]);
            }
        }
    }

    function removeItem(api, id) {
        const data = get(api);
        if (data !== null) {
            set(api, data.filter(r => String(r.id) !== String(id)));
        }
    }

    async function loadAll(lookups, token) {
        for (const lookup of lookups) {
            if (get(lookup.api) !== null) continue;
            try {
                const resp = await fetch(lookup.api + '/list', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + token
                    },
                    body: JSON.stringify({ page: 1, perpage: -1 })
                });
                if (!resp.ok) continue;
                const json = await resp.json();
                set(lookup.api, Array.isArray(json.data) ? json.data : []);
            } catch (e) {
                console.warn('LookupCache: не удалось загрузить', lookup.api, e);
            }
        }
    }

    return { get, set, addItem, updateItem, removeItem, loadAll };
})();

document.addEventListener('DOMContentLoaded', function () {
    const token = localStorage.getItem('access_token');
    if (token) {
        const lookups = @json($lookupItems);
        setTimeout(() => window.LookupCache.loadAll(lookups, token), 1500);
    }
});
</script>

{{-- 🗂 Кэш основных таблиц --}}
<script>
window.MainTableCache = (function () {
    const CACHE_TTL = 30 * 60 * 1000; // 30 минут
    const KEY_PREFIX = 'main:';

    function cacheKey(api) { return KEY_PREFIX + api; }

    function get(api) {
        try {
            const raw = localStorage.getItem(cacheKey(api));
            if (!raw) return null;
            const entry = JSON.parse(raw);
            if (Date.now() - (entry.cached_at || 0) > CACHE_TTL) {
                localStorage.removeItem(cacheKey(api));
                return null;
            }
            return entry.data;
        } catch (e) { return null; }
    }

    function set(api, data) {
        try {
            localStorage.setItem(cacheKey(api), JSON.stringify({
                data: Array.isArray(data) ? data : [],
                cached_at: Date.now()
            }));
        } catch (e) {
            console.warn('MainTableCache: не удалось сохранить в localStorage', e);
        }
    }

    function addItem(api, item) {
        const data = get(api);
        if (data !== null) set(api, [...data, item]);
    }

    function updateItem(api, id, item) {
        const data = get(api);
        if (data !== null) {
            const idx = data.findIndex(r => String(r.id) === String(id));
            if (idx >= 0) {
                const updated = [...data];
                updated[idx] = Object.assign({}, updated[idx], item);
                set(api, updated);
            } else {
                set(api, [...data, item]);
            }
        }
    }

    function removeItem(api, id) {
        const data = get(api);
        if (data !== null) {
            set(api, data.filter(r => String(r.id) !== String(id)));
        }
    }

    return { get, set, addItem, updateItem, removeItem };
})();
</script>
</body>
</html>
