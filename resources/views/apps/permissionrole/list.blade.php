<x-layout.default>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        window.ROLES_RIGHTS_CONFIG = {
            rolesApi: @json(config('entities.main.roles.common.api')),
            permissionsApi: @json(config('entities.main.permission.common.api')),
            permissionRoleApi: @json($config['common']['api'] ?? null),
        };
    </script>

    <style>
        .rr-board {
            --rr-accent: #4361ee;
            --rr-accent-soft: rgba(67, 97, 238, 0.08);
            --rr-line: rgba(148, 163, 184, 0.24);
            --rr-bg-soft: linear-gradient(135deg, rgba(67, 97, 238, 0.08), rgba(6, 182, 212, 0.08));
            --rr-success: #0e9f6e;
            --rr-warning: #e2a03f;
        }

        .dark .rr-board {
            --rr-line: rgba(148, 163, 184, 0.18);
            --rr-accent-soft: rgba(67, 97, 238, 0.16);
            --rr-bg-soft: linear-gradient(135deg, rgba(67, 97, 238, 0.12), rgba(8, 145, 178, 0.14));
        }

        .rr-toolbar-input,
        .rr-matrix-shell,
        .rr-kpi,
        .rr-group-chip,
        .rr-status-chip {
            border: 1px solid var(--rr-line);
        }

        .rr-gradient-card {
            background: var(--rr-bg-soft);
        }

        .rr-matrix-scroll {
            overflow: auto;
            max-height: 68vh;
        }

        .rr-grid-row {
            display: grid;
            min-width: max-content;
        }

        .rr-grid-header {
            position: sticky;
            top: 0;
            z-index: 20;
            backdrop-filter: blur(14px);
            background: rgba(255, 255, 255, 0.92);
        }

        .dark .rr-grid-header {
            background: rgba(14, 23, 38, 0.92);
        }

        .rr-grid-group {
            position: sticky;
            left: 0;
            z-index: 12;
            background: #eef1f5;
            backdrop-filter: blur(10px);
        }

        .dark .rr-grid-group {
            background: #3a4352;
        }

        .rr-cell-sticky {
            position: sticky;
            left: 0;
            z-index: 10;
            background: inherit;
        }

        .rr-role-head {
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            min-height: 132px;
            max-height: 132px;
        }

        .rr-cell {
            border-left: 1px solid var(--rr-line);
            border-bottom: 1px solid var(--rr-line);
        }

        .rr-cell:first-child {
            border-left: 0;
        }

        .rr-checkbox {
            width: 18px;
            height: 18px;
            border-radius: 5px;
            border: 1.5px solid rgba(148, 163, 184, 0.5);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: transform .15s ease, border-color .15s ease, background-color .15s ease, box-shadow .15s ease;
        }

        .rr-checkbox svg {
            width: 12px;
            height: 12px;
            stroke-width: 2.4;
            opacity: 0;
            transform: scale(0.7);
            transition: opacity .15s ease, transform .15s ease;
        }

        .rr-checkbox.is-on {
            border-color: var(--rr-success);
            background: var(--rr-success);
            box-shadow: 0 0 0 3px rgba(14, 159, 110, 0.12);
        }

        .rr-checkbox.is-on svg,
        .rr-checkbox.is-indeterminate svg {
            opacity: 1;
            transform: scale(1);
        }

        .rr-checkbox.is-off {
            border-color: rgba(148, 163, 184, 0.4);
            background: transparent;
        }

        .rr-checkbox.is-indeterminate {
            border-color: var(--rr-warning);
            background: rgba(226, 160, 63, 0.14);
            box-shadow: 0 0 0 3px rgba(226, 160, 63, 0.12);
        }

        .rr-cell.is-highlighted .rr-checkbox {
            transform: scale(1.08);
            border-color: var(--rr-accent);
        }

        .rr-cell.is-busy {
            opacity: .55;
            pointer-events: none;
        }

        .rr-empty {
            border: 1px dashed var(--rr-line);
        }

        .rr-group-row > .rr-cell,
        .rr-group-row > .rr-grid-group {
            background: #eef1f5;
        }

        .dark .rr-group-row > .rr-cell,
        .dark .rr-group-row > .rr-grid-group {
            background: #3a4352;
        }

        .rr-group-toggle {
            transition: transform .15s ease;
        }

        .rr-group-toggle.is-collapsed {
            transform: rotate(-90deg);
        }
    </style>

    <div x-data="rolesRightsBoard" x-init="init()" class="rr-board">
        <ul class="flex flex-wrap items-center gap-2 text-sm text-white-dark">
            <li><a href="/" class="text-primary hover:underline">Dashboard</a></li>
            <li>/</li>
            <li>Главная</li>
            <li>/</li>
            <li class="text-black dark:text-white-light">Роли и права</li>
        </ul>

        <div class="pt-5 space-y-6">
            <div class="panel">
                <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                    <div>
                        <div class="text-lg font-semibold text-black dark:text-white-light">Матрица доступа</div>
                        <div class="text-xs text-white-dark">Группировка прав и пересечение с ролями</div>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <div class="relative">
                            <input type="text"
                                   class="form-input rr-toolbar-input w-full min-w-[240px] pl-10"
                                   placeholder="Поиск по правам, slug, группе"
                                   x-model.debounce.250ms="query">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-white-dark">
                                <i class="uil uil-search"></i>
                            </div>
                        </div>

                        <select class="form-select min-w-[220px]" x-model="groupFilter">
                            <option value="all">Все группы</option>
                            <template x-for="group in permissionGroups" :key="group.key">
                                <option :value="group.key" x-text="`${group.label} (${group.permissions.length})`"></option>
                            </template>
                        </select>
                    </div>
                </div>

                <div x-cloak x-show="forbidden" class="mt-4 rounded-lg border border-danger/30 bg-danger-light px-4 py-3 text-sm text-danger">
                    Нет доступа к просмотру матрицы ролей и прав.
                </div>

                <div x-cloak x-show="!forbidden && !canManageBoard" class="mt-4 rounded-lg border border-warning/30 bg-warning-light px-4 py-3 text-sm text-warning">
                    Матрица открыта в режиме просмотра. Изменение связей роль-право недоступно.
                </div>
            </div>

            <div class="panel rr-matrix-shell overflow-hidden p-0">
                <div x-show="loading" class="px-6 py-16 text-center text-white-dark">
                    Загружаем роли и права...
                </div>

                <div x-show="!loading && !forbidden && errorText" class="px-6 py-16 text-center text-danger" x-text="errorText"></div>

                <div x-show="!loading && !forbidden && !errorText && filteredGroups.length === 0" class="px-6 py-16 text-center">
                    <div class="rr-empty mx-auto max-w-xl rounded-2xl px-6 py-10 text-white-dark">
                        По текущим фильтрам ничего не найдено.
                    </div>
                </div>

                <div x-show="!loading && !forbidden && !errorText && filteredGroups.length > 0" class="rr-matrix-scroll">
                    <div class="rr-grid-row rr-grid-header border-b border-[#e0e6ed] dark:border-[#1b2e4b]" :style="matrixGridStyle">
                        <div class="rr-cell rr-cell-sticky flex items-center justify-between gap-3 px-5 py-4">
                            <div>
                                <div class="text-sm font-semibold text-black dark:text-white-light">Право</div>
                                <div class="text-xs text-white-dark">Группа / код</div>
                            </div>
                            <div class="rounded-full bg-primary/10 px-2.5 py-1 text-xs font-semibold text-primary" x-text="visibleRoles.length"></div>
                        </div>

                        <template x-for="role in visibleRoles" :key="`head-${role.id}`">
                            <div class="rr-cell flex items-end justify-center px-2 py-3">
                                <div class="rr-role-head flex items-center justify-center text-center text-[11px] font-semibold uppercase tracking-[0.14em] text-black/70 dark:text-white-dark"
                                     x-text="role.slug || role.shortname || role.name"></div>
                            </div>
                        </template>
                    </div>

                    <template x-for="group in filteredGroups" :key="group.key">
                        <div>
                            <div class="rr-grid-row rr-group-row" :style="matrixGridStyle">
                                <button type="button"
                                        class="rr-grid-group rr-cell flex w-full items-center justify-between gap-3 px-5 py-3 text-left"
                                        @click="toggleGroup(group.key)">
                                    <div class="flex items-center gap-3">
                                        <span class="rr-group-toggle inline-flex text-white-dark" :class="{ 'is-collapsed': group.isCollapsed }">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </span>
                                        <div>
                                            <div class="font-semibold text-black dark:text-white-light" x-text="group.label"></div>
                                            <div class="text-xs text-white-dark" x-text="`${group.permissions.length} прав`"></div>
                                        </div>
                                    </div>
                                    <div class="rounded-full bg-black/5 px-2.5 py-1 text-xs dark:bg-white/5" x-text="group.coverage"></div>
                                </button>

                                <template x-for="role in visibleRoles" :key="`group-${group.key}-${role.id}`">
                                    <div class="rr-cell flex items-center justify-center px-2 py-3 text-xs text-white-dark">
                                        <button type="button"
                                                class="inline-flex items-center justify-center"
                                                :disabled="!canManageBoard || isGroupSaving(group.key, role.id)"
                                                @click.stop="toggleGroupAssignments(group, role)">
                                            <span class="rr-checkbox" :class="groupCheckboxClass(group, role)">
                                                <svg viewBox="0 0 16 16" fill="none" stroke="white" aria-hidden="true">
                                                    <path x-show="groupRoleState(group, role) === 'on'" d="M3.5 8.5L6.5 11.5L12.5 4.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path x-show="groupRoleState(group, role) === 'indeterminate'" d="M3.5 8H12.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                            </span>
                                        </button>
                                    </div>
                                </template>
                            </div>

                            <template x-if="!group.isCollapsed">
                                <template x-for="permission in group.permissions" :key="permission.key">
                                    <div class="rr-grid-row" :style="matrixGridStyle">
                                        <div class="rr-cell rr-cell-sticky flex w-full items-center justify-between gap-3 px-4 py-2 text-left hover:bg-black/[0.02] dark:hover:bg-white/[0.02]">
                                            <div class="min-w-0">
                                                <div class="truncate text-[13px] font-medium leading-5 text-black dark:text-white-light"
                                                     x-text="permission.name || permission.slug || permission.key"></div>
                                                <div class="truncate text-xs text-white-dark" x-text="permission.slug || permission.key"></div>
                                            </div>
                                            <div class="rr-status-chip rounded-full px-2 py-1 text-xs text-black/60 dark:text-white-dark" x-text="permission.assignedCount"></div>
                                        </div>

                                        <template x-for="role in visibleRoles" :key="`perm-${permission.key}-${role.id}`">
                                            <button type="button"
                                                    class="rr-cell flex items-center justify-center px-2 py-2"
                                                    :class="{ 'is-highlighted': isHighlightedCell(role.id, permission.key), 'is-busy': isSaving(role.id, permission.key) }"
                                                    :disabled="!canManageBoard || isSaving(role.id, permission.key)"
                                                    @click="toggleAssignment(role, permission)">
                                                <span class="rr-checkbox" :class="hasPermission(role, permission) ? 'is-on' : 'is-off'">
                                                    <svg viewBox="0 0 16 16" fill="none" stroke="white" aria-hidden="true">
                                                        <path d="M3.5 8.5L6.5 11.5L12.5 4.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                </span>
                                            </button>
                                        </template>
                                    </div>
                                </template>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('rolesRightsBoard', () => ({
                rolesApi: window.ROLES_RIGHTS_CONFIG.rolesApi,
                permissionsApi: window.ROLES_RIGHTS_CONFIG.permissionsApi,
                permissionRoleApi: window.ROLES_RIGHTS_CONFIG.permissionRoleApi,

                loading: false,
                forbidden: false,
                errorText: '',
                query: '',
                groupFilter: 'all',
                roles: [],
                permissions: [],
                permissionLookup: {},
                permissionRoleRows: [],
                permissionRoleMap: {},
                activeCell: null,
                savingMap: {},
                collapsedGroups: {},
                groupSavingMap: {},

                async init() {
                    await window.AuthACL?.ensureProfile?.();
                    await this.loadBoard();
                },

                get canViewBoard() {
                    const acl = window.AuthACL;
                    if (!acl) return true;

                    return acl.hasAnyResourcePermission('roles', ['index', 'list'])
                        && acl.hasAnyResourcePermission('permissions', ['index', 'list'])
                        && acl.hasAnyResourcePermission('permissionroles', ['index', 'list']);
                },

                get canManageBoard() {
                    const acl = window.AuthACL;
                    if (!acl) return true;

                    return acl.hasAnyResourcePermission('permissionroles', ['create', 'update', 'delete']);
                },

                authHeaders() {
                    const token = localStorage.getItem('access_token');

                    if (!token) {
                        window.location.replace('/login');
                        throw new Error('Нет access token в localStorage.');
                    }

                    return {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`,
                    };
                },

                async requestList(api, filter = []) {
                    const response = await fetch(`${api}/list`, {
                        method: 'POST',
                        headers: this.authHeaders(),
                        body: JSON.stringify({
                            page: 1,
                            perpage: -1,
                            filter,
                        }),
                    });

                    if (response.status === 401) {
                        window.location.replace('/login');
                        throw new Error('Сессия истекла.');
                    }

                    if (response.status === 403) {
                        throw new Error('Нет доступа к управлению ролями и правами.');
                    }

                    if (!response.ok) {
                        throw new Error('Ошибка загрузки данных.');
                    }

                    return await response.json();
                },

                get visibleRoles() {
                    return this.roles;
                },

                get permissionGroups() {
                    const groups = new Map();

                    this.permissions.forEach(permission => {
                        if (!groups.has(permission.groupKey)) {
                            groups.set(permission.groupKey, {
                                key: permission.groupKey,
                                label: permission.groupLabel,
                                permissions: [],
                                coverage: '0%',
                            });
                        }

                        groups.get(permission.groupKey).permissions.push(permission);
                    });

                    const roles = this.visibleRoles;

                    groups.forEach(group => {
                        const assignedTotal = group.permissions.reduce((sum, permission) => sum + permission.assignedCount, 0);
                        const totalPossible = Math.max(1, group.permissions.length * Math.max(1, roles.length));
                        group.coverage = `${Math.round((assignedTotal / totalPossible) * 100)}%`;
                    });

                    return Array.from(groups.values()).sort((a, b) => a.label.localeCompare(b.label, 'ru'));
                },

                get filteredGroups() {
                    const query = this.query.trim().toLowerCase();

                    return this.permissionGroups
                        .filter(group => this.groupFilter === 'all' || group.key === this.groupFilter)
                        .map(group => ({
                            ...group,
                            permissions: group.permissions.filter(permission => {
                                return !query || [
                                    permission.name,
                                    permission.slug,
                                    permission.groupLabel,
                                    permission.key,
                                ].some(value => String(value ?? '').toLowerCase().includes(query));
                            }),
                            isCollapsed: this.isGroupCollapsed(group.key),
                        }))
                        .filter(group => group.permissions.length > 0);
                },

                get matrixGridStyle() {
                    return `grid-template-columns: minmax(280px, 280px) repeat(${this.visibleRoles.length}, minmax(84px, 84px));`;
                },

                async loadBoard() {
                    this.loading = true;
                    this.forbidden = false;
                    this.errorText = '';

                    try {
                        if (!this.canViewBoard) {
                            this.forbidden = true;
                            return;
                        }

                        const [rolesPayload, permissionsPayload, permissionRolePayload] = await Promise.all([
                            this.requestList(this.rolesApi),
                            this.requestList(this.permissionsApi),
                            this.requestList(this.permissionRoleApi),
                        ]);

                        this.permissions = this.normalizePermissions(permissionsPayload.data ?? []);
                        this.permissionLookup = this.buildPermissionLookup(this.permissions);
                        this.roles = this.normalizeRoles(rolesPayload.data ?? []);
                        this.permissionRoleRows = this.normalizePermissionRoleRows(permissionRolePayload.data ?? []);
                        this.permissionRoleMap = this.buildPermissionRoleMap(this.permissionRoleRows);
                        this.initializeCollapsedGroups();
                        this.decorateCoverage();
                    } catch (error) {
                        this.errorText = error?.message || 'Не удалось загрузить роли и права.';
                    } finally {
                        this.loading = false;
                    }
                },

                normalizeRoles(rawRoles) {
                    return rawRoles
                        .map(role => ({
                            ...role,
                            name: role.name ?? `Role #${role.id}`,
                            slug: role.slug ?? role.shortname ?? '',
                        }))
                        .sort((a, b) => String(a.name).localeCompare(String(b.name), 'ru'));
                },

                normalizePermissions(rawPermissions) {
                    return rawPermissions
                        .map(permission => {
                            const aliases = this.permissionAliases(permission);
                            const key = aliases[0];
                            const { groupKey, groupLabel } = this.permissionGroup(permission);

                            return {
                                ...permission,
                                key,
                                aliases,
                                name: permission.name ?? permission.slug ?? `Permission #${permission.id}`,
                                slug: permission.slug ?? '',
                                groupKey,
                                groupLabel,
                                assignedCount: 0,
                            };
                        })
                        .sort((a, b) => {
                            if (a.groupLabel !== b.groupLabel) {
                                return a.groupLabel.localeCompare(b.groupLabel, 'ru');
                            }

                            return String(a.name).localeCompare(String(b.name), 'ru');
                        });
                },

                normalizePermissionRoleRows(rows) {
                    return rows.map(row => {
                        const roleId = row?.role_id ?? row?.role?.id ?? null;
                        const permissionValue = row?.permission_id
                            ?? row?.permission?.id
                            ?? row?.permission?.slug
                            ?? row?.permission?.name
                            ?? null;
                        const permissionKey = this.resolvePermissionKey(permissionValue);

                        if (!row?.id || !roleId || !permissionKey) return null;

                        return {
                            rowId: String(row.id),
                            roleId: String(roleId),
                            permissionId: String(row?.permission_id ?? row?.permission?.id ?? ''),
                            permissionKey,
                        };
                    }).filter(Boolean);
                },

                buildPermissionLookup(permissions) {
                    return permissions.reduce((acc, permission) => {
                        permission.aliases.forEach(alias => {
                            acc[String(alias).trim().toLowerCase()] = permission.key;
                        });
                        return acc;
                    }, {});
                },

                buildPermissionRoleMap(rows) {
                    return rows.reduce((acc, row) => {
                        acc[this.assignmentKey(row.roleId, row.permissionKey)] = row;
                        return acc;
                    }, {});
                },

                permissionAliases(permission) {
                    return [
                        permission?.slug,
                        permission?.name,
                        permission?.resource,
                        permission?.id,
                    ]
                        .map(value => String(value ?? '').trim())
                        .filter(Boolean)
                        .filter((value, index, array) => array.indexOf(value) === index);
                },

                resolvePermissionKey(value) {
                    return this.permissionLookup[String(value ?? '').trim().toLowerCase()] ?? null;
                },

                assignmentKey(roleId, permissionKey) {
                    return `${String(roleId)}::${String(permissionKey)}`;
                },

                permissionGroup(permission) {
                    const raw = String(permission?.slug || permission?.resource || permission?.name || '');
                    const first = raw.split(/[.:/]/)[0];
                    const fallback = first || raw.split('_')[0] || 'other';
                    const key = fallback.toLowerCase() || 'other';

                    return {
                        groupKey: key,
                        groupLabel: this.humanizeGroupLabel(key),
                    };
                },

                humanizeGroupLabel(key) {
                    const map = {
                        admin: 'Администрирование',
                        role: 'Роли',
                        roles: 'Роли',
                        permission: 'Права',
                        permissions: 'Права',
                        user: 'Пользователи',
                        users: 'Пользователи',
                        company: 'Компании',
                        file: 'Файлы',
                        reference: 'Справочники',
                        references: 'Справочники',
                        other: 'Прочее',
                    };

                    return map[key] ?? (key.charAt(0).toUpperCase() + key.slice(1));
                },

                decorateCoverage() {
                    this.permissions.forEach(permission => {
                        permission.assignedCount = this.roles.filter(role => this.hasPermission(role, permission)).length;
                    });
                },

                hasPermission(role, permission) {
                    return !!this.permissionRoleMap[this.assignmentKey(role.id, permission.key)];
                },

                isSaving(roleId, permissionKey) {
                    return !!this.savingMap[this.assignmentKey(roleId, permissionKey)];
                },

                setSaving(roleId, permissionKey, state) {
                    const key = this.assignmentKey(roleId, permissionKey);
                    const next = { ...this.savingMap };

                    if (state) next[key] = true;
                    else delete next[key];

                    this.savingMap = next;
                },

                groupSavingKey(groupKey, roleId) {
                    return `${String(groupKey)}::${String(roleId)}`;
                },

                isGroupSaving(groupKey, roleId) {
                    return !!this.groupSavingMap[this.groupSavingKey(groupKey, roleId)];
                },

                setGroupSaving(groupKey, roleId, state) {
                    const key = this.groupSavingKey(groupKey, roleId);
                    const next = { ...this.groupSavingMap };

                    if (state) next[key] = true;
                    else delete next[key];

                    this.groupSavingMap = next;
                },

                isGroupCollapsed(groupKey) {
                    return this.collapsedGroups[groupKey] !== false;
                },

                toggleGroup(groupKey) {
                    this.collapsedGroups = {
                        ...this.collapsedGroups,
                        [groupKey]: !this.isGroupCollapsed(groupKey),
                    };
                },

                initializeCollapsedGroups() {
                    const next = { ...this.collapsedGroups };

                    this.permissionGroups.forEach(group => {
                        if (!(group.key in next)) next[group.key] = true;
                    });

                    this.collapsedGroups = next;
                },

                groupRoleState(group, role) {
                    const permissions = group.permissions ?? [];
                    if (!permissions.length) return 'off';

                    const checkedCount = permissions.filter(permission => this.hasPermission(role, permission)).length;

                    if (checkedCount === 0) return 'off';
                    if (checkedCount === permissions.length) return 'on';
                    return 'indeterminate';
                },

                groupCheckboxClass(group, role) {
                    const state = this.groupRoleState(group, role);
                    if (state === 'on') return 'is-on';
                    if (state === 'indeterminate') return 'is-indeterminate';
                    return 'is-off';
                },

                async toggleGroupAssignments(group, role) {
                    if (!this.canManageBoard) return;

                    const groupKey = group.key;
                    const roleId = String(role.id);

                    if (this.isGroupSaving(groupKey, roleId)) return;

                    this.setGroupSaving(groupKey, roleId, true);
                    this.errorText = '';

                    const shouldEnable = this.groupRoleState(group, role) !== 'on';
                    const permissions = (group.permissions ?? []).filter(permission => {
                        const hasLink = this.hasPermission(role, permission);
                        return shouldEnable ? !hasLink : hasLink;
                    });

                    try {
                        for (const permission of permissions) {
                            if (shouldEnable) {
                                const createdRow = await this.createAssignment(role, permission);
                                this.applyCreateAssignment(createdRow, role.id, permission.key);
                            } else {
                                const existing = this.permissionRoleMap[this.assignmentKey(role.id, permission.key)] ?? null;
                                await this.deleteAssignment(existing);
                                this.applyDeleteAssignment(this.assignmentKey(role.id, permission.key));
                            }
                        }
                    } catch (error) {
                        this.errorText = error?.message || 'Не удалось массово изменить права группы.';
                    } finally {
                        this.setGroupSaving(groupKey, roleId, false);
                    }
                },

                async toggleAssignment(role, permission) {
                    if (!this.canManageBoard) return;

                    const roleId = String(role.id);
                    const permissionKey = permission.key;
                    const key = this.assignmentKey(roleId, permissionKey);

                    if (this.isSaving(roleId, permissionKey)) return;

                    this.setSaving(roleId, permissionKey, true);
                    this.activeCell = { roleId, permissionKey };

                    try {
                        if (this.permissionRoleMap[key]) {
                            await this.deleteAssignment(this.permissionRoleMap[key]);
                            this.applyDeleteAssignment(key);
                        } else {
                            const createdRow = await this.createAssignment(role, permission);
                            this.applyCreateAssignment(createdRow, role.id, permission.key);
                        }
                    } catch (error) {
                        this.errorText = error?.message || 'Не удалось сохранить изменение в таблице ролей и прав.';
                    } finally {
                        this.setSaving(roleId, permissionKey, false);
                    }
                },

                applyCreateAssignment(row, roleIdFallback, permissionKeyFallback) {
                    const normalized = this.normalizePermissionRoleRows([row])[0] ?? null;
                    const prepared = normalized ?? {
                        rowId: String(row?.id ?? ''),
                        roleId: String(row?.role_id ?? roleIdFallback),
                        permissionId: String(row?.permission_id ?? ''),
                        permissionKey: permissionKeyFallback,
                    };

                    if (!prepared.rowId || !prepared.roleId || !prepared.permissionKey) return;

                    const key = this.assignmentKey(prepared.roleId, prepared.permissionKey);
                    const nextRows = [
                        ...this.permissionRoleRows.filter(item => this.assignmentKey(item.roleId, item.permissionKey) !== key),
                        prepared,
                    ];

                    this.permissionRoleRows = nextRows;
                    this.permissionRoleMap = this.buildPermissionRoleMap(nextRows);
                    this.decorateCoverage();
                },

                applyDeleteAssignment(assignmentKey) {
                    const nextRows = this.permissionRoleRows.filter(item => this.assignmentKey(item.roleId, item.permissionKey) !== assignmentKey);
                    this.permissionRoleRows = nextRows;
                    this.permissionRoleMap = this.buildPermissionRoleMap(nextRows);
                    this.decorateCoverage();
                },

                async createAssignment(role, permission) {
                    if (!permission?.id) {
                        throw new Error('Для права не найден id, создание связи невозможно.');
                    }

                    const response = await fetch(`${this.permissionRoleApi}/create`, {
                        method: 'POST',
                        headers: this.authHeaders(),
                        body: JSON.stringify({
                            role_id: role.id,
                            permission_id: permission.id,
                        }),
                    });

                    if (!response.ok) {
                        throw new Error('Не удалось создать связь роль-право.');
                    }

                    const json = await response.json();
                    return json?.data ?? null;
                },

                async deleteAssignment(assignment) {
                    if (!assignment?.roleId || !assignment?.permissionId) {
                        throw new Error('Не найдена связь роль-право для удаления.');
                    }

                    const response = await fetch(`${this.permissionRoleApi}/${assignment.roleId}/${assignment.permissionId}/delete`, {
                        method: 'DELETE',
                        headers: this.authHeaders(),
                    });

                    if (!response.ok) {
                        throw new Error('Не удалось удалить связь роль-право.');
                    }
                },

                isHighlightedCell(roleId, permissionKey) {
                    return String(this.activeCell?.roleId) === String(roleId)
                        && this.activeCell?.permissionKey === permissionKey;
                },
            }));
        });
    </script>

</x-layout.default>
