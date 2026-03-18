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
</body>
</html>
