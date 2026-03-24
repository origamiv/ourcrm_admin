<x-layout.default>

    <script>
        window.USER_ROLES_CONFIG = {
            userId: @json($id),
            userApi: @json($config['common']['api'] ?? null),
            rolesApi: @json($rolesApi ?? null),
            roleUsersApi: @json($roleUsersApi ?? null),
        };
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>

    <div x-data="userRolesPage" x-init="init()" class="max-w-4xl">
        <div class="panel px-6 py-6">
            <div class="mb-6 flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold">Назначение ролей</h1>
                    <p class="mt-1 text-sm text-white-dark" x-show="user.name || user.email">
                        <span x-text="user.name || 'Пользователь'"></span>
                        <span x-show="user.email" x-text="`(${user.email})`"></span>
                    </p>
                </div>

                <a href="/web/main/users" class="btn btn-outline-secondary">Назад</a>
            </div>

            <div x-cloak x-show="forbidden" class="rounded-lg border border-danger/30 bg-danger-light p-4 text-danger">
                Нет доступа к назначению ролей.
            </div>

            <div x-cloak x-show="!forbidden && loading" class="py-12 text-center text-white-dark">
                Загрузка...
            </div>

            <div x-cloak x-show="!forbidden && !loading" class="space-y-4">
                <template x-if="!roles.length">
                    <div class="rounded-lg border border-[#e0e6ed] p-4 text-white-dark dark:border-[#1b2e4b]">
                        Доступные роли не найдены.
                    </div>
                </template>

                <template x-for="role in roles" :key="role.id">
                    <label class="flex items-start gap-3 rounded-xl border border-[#e0e6ed] p-4 transition hover:border-primary/40 dark:border-[#1b2e4b]">
                        <input
                            type="checkbox"
                            class="form-checkbox mt-1"
                            :value="role.id"
                            :checked="selectedRoleIds.includes(role.id)"
                            @change="toggleRole(role.id, $event.target.checked)"
                            :disabled="saving"
                        >
                        <span class="min-w-0">
                            <span class="block font-semibold text-black dark:text-white-light" x-text="role.name"></span>
                            <span class="mt-1 block text-sm text-white-dark" x-text="role.slug || role.shortname || ''"></span>
                            <span class="mt-1 block text-sm text-white-dark" x-show="role.description" x-text="role.description"></span>
                        </span>
                    </label>
                </template>

                <div class="flex items-center justify-between gap-4 pt-4">
                    <div class="text-sm" :class="messageType === 'error' ? 'text-danger' : 'text-success'" x-text="message"></div>
                    <button class="btn btn-primary" @click="save()" :disabled="saving">
                        <span x-show="!saving">Сохранить</span>
                        <span x-show="saving">Сохранение...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('userRolesPage', () => ({
                userId: window.USER_ROLES_CONFIG.userId,
                userApi: window.USER_ROLES_CONFIG.userApi,
                rolesApi: window.USER_ROLES_CONFIG.rolesApi,
                roleUsersApi: window.USER_ROLES_CONFIG.roleUsersApi,

                user: {},
                roles: [],
                roleUserRows: [],
                originalRoleIds: [],
                selectedRoleIds: [],
                loading: true,
                saving: false,
                forbidden: false,
                message: '',
                messageType: 'success',

                async init() {
                    await this.load();
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

                async requestList(api, filter = [], perpage = -1) {
                    const response = await fetch(`${api}/list`, {
                        method: 'POST',
                        headers: this.authHeaders(),
                        body: JSON.stringify({
                            page: 1,
                            perpage,
                            filter,
                        }),
                    });

                    if (response.status === 401) {
                        window.location.replace('/login');
                        throw new Error('Сессия истекла.');
                    }

                    if (response.status === 403) {
                        this.forbidden = true;
                        throw new Error('Нет доступа.');
                    }

                    if (!response.ok) {
                        throw new Error('Ошибка запроса списка.');
                    }

                    return await response.json();
                },

                async load() {
                    this.loading = true;
                    this.message = '';

                    try {
                        const [usersPayload, rolesPayload, roleUsersPayload] = await Promise.all([
                            this.requestList(this.userApi, [{ field: 'id', op: '=', val: this.userId }], 1),
                            this.requestList(this.rolesApi),
                            this.requestList(this.roleUsersApi, [{ field: 'user_id', op: '=', val: this.userId }]),
                        ]);

                        this.user = Array.isArray(usersPayload.data) ? (usersPayload.data[0] ?? {}) : {};
                        this.roles = Array.isArray(rolesPayload.data) ? rolesPayload.data : [];
                        this.roleUserRows = Array.isArray(roleUsersPayload.data) ? roleUsersPayload.data : [];

                        this.originalRoleIds = this.roleUserRows
                            .map(row => Number(row.role_id ?? row.role?.id ?? null))
                            .filter(Number.isFinite)
                            .sort((a, b) => a - b);

                        this.selectedRoleIds = [...this.originalRoleIds];
                    } catch (error) {
                        if (!this.forbidden) {
                            console.error('User roles load error', error);
                            this.message = error?.message || 'Не удалось загрузить роли пользователя.';
                            this.messageType = 'error';
                        }
                    } finally {
                        this.loading = false;
                    }
                },

                toggleRole(roleId, checked) {
                    const normalizedId = Number(roleId);
                    const next = new Set(this.selectedRoleIds);

                    if (checked) next.add(normalizedId);
                    else next.delete(normalizedId);

                    this.selectedRoleIds = Array.from(next).sort((a, b) => a - b);
                },

                async save() {
                    if (this.saving) return;

                    this.saving = true;
                    this.message = '';
                    this.messageType = 'success';

                    try {
                        const original = new Set(this.originalRoleIds);
                        const selected = new Set(this.selectedRoleIds);

                        const toCreate = this.selectedRoleIds.filter(roleId => !original.has(roleId));
                        const toDelete = this.originalRoleIds.filter(roleId => !selected.has(roleId));

                        for (const roleId of toCreate) {
                            const response = await fetch(`${this.roleUsersApi}/create`, {
                                method: 'POST',
                                headers: this.authHeaders(),
                                body: JSON.stringify({
                                    user_id: this.userId,
                                    role_id: roleId,
                                }),
                            });

                            if (response.status === 403) {
                                this.forbidden = true;
                                return;
                            }

                            if (!response.ok) {
                                throw new Error('Не удалось назначить роль.');
                            }
                        }

                        for (const roleId of toDelete) {
                            const response = await fetch(`${this.roleUsersApi}/${this.userId}/${roleId}/delete`, {
                                method: 'DELETE',
                                headers: this.authHeaders(),
                            });

                            if (response.status === 403) {
                                this.forbidden = true;
                                return;
                            }

                            if (!response.ok) {
                                throw new Error('Не удалось снять роль.');
                            }
                        }

                        await this.load();
                        this.message = 'Роли сохранены.';
                    } catch (error) {
                        console.error('User roles save error', error);
                        this.message = error?.message || 'Не удалось сохранить роли.';
                        this.messageType = 'error';
                    } finally {
                        this.saving = false;
                    }
                },
            }));
        });
    </script>
</x-layout.default>
