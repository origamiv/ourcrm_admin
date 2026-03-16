<style>
    .sub-menu li::before,
    .sub-menu li::after,
    .sub-menu .nav-link::before,
    .sub-menu .nav-link::after {
        content: none !important;
        display: none !important;
        background: none !important;
    }
    </style>
<div
    class="sidebar-wrapper"
    :class="{
        'dark text-white-dark': $store.app.semidark,
        'sidebar-collapsed': $store.app.sidebarCollapsed
    }"
>
    <nav
        x-data="sidebar"
        class="sidebar fixed min-h-screen h-full top-0 bottom-0 w-[260px]
               shadow-[5px_0_25px_0_rgba(94,92,154,0.1)]
               z-50 transition-all duration-300">

        <div class="bg-white dark:bg-[#0e1726] h-full">

            {{-- HEADER --}}
            <div class="flex justify-between items-center px-4 py-3">
                <a href="/" class="main-logo flex items-center shrink-0">
                    <img class="w-8 ml-[5px] flex-none"
                         src="/assets/images/logo.svg" alt="logo"/>

                    <span
                        class="text-2xl ltr:ml-1.5 rtl:mr-1.5 font-semibold
                               align-middle lg:inline dark:text-white-light
                               menu-title">
                        VRISTO
                    </span>
                </a>

                <a href="javascript:;"
                   class="collapse-icon w-8 h-8 rounded-full flex items-center
                          hover:bg-gray-500/10 dark:hover:bg-dark-light/10
                          dark:text-white-light transition duration-300 rtl:rotate-180"
                   @click="$store.app.toggleSidebar()">

                    <svg class="w-5 h-5 m-auto" viewBox="0 0 24 24" fill="none">
                        <path d="M13 19L7 12L13 5"
                              stroke="currentColor" stroke-width="1.5"
                              stroke-linecap="round" stroke-linejoin="round"/>
                        <path opacity="0.5"
                              d="M17 19L11 12L17 5"
                              stroke="currentColor" stroke-width="1.5"
                              stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>

            {{-- MENU --}}
            <ul class="perfect-scrollbar relative font-semibold
                       space-y-0.5 h-[calc(100vh-80px)]
                       overflow-y-auto overflow-x-hidden
                       p-4 py-0">

                <template x-for="item in rootMenus" :key="item.id">
                    <li class="menu nav-item">

                        {{-- ROOT --}}
                        <button
                            type="button"
                            class="nav-link group"
                            :class="{ 'active': isActive(item) }"
                            @click="onRootClick(item)">

                            <div class="flex items-center">
                                <i
                                    class="group-hover:!text-primary shrink-0"
                                    :class="item.icon || 'uil uil-circle'">
                                </i>

                                <span
                                    class="ltr:pl-3 rtl:pr-3 menu-title"
                                    x-text="item.name">
                                </span>
                            </div>

                            <template x-if="hasChildren(item)">
                                <div class="rtl:rotate-180">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                        <path d="M9 5L15 12L9 19"
                                              stroke="currentColor" stroke-width="1.5"
                                              stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </template>
                        </button>

                        {{-- CHILDREN --}}
                        <ul
                            x-show="open[item.id]"
                            x-collapse
                            class="sub-menu text-gray-500">

                            <template x-for="child in children(item.id)" :key="child.id">
                                <li>
                                    <a
                                        :href="menuUrl(child)"
                                        class="nav-link flex items-center"
                                        :class="{ 'active': isActive(child) }"
                                        style="margin-left: 5px; "
                                    >
                                        <i
                                            class="shrink-0 uil"
                                            :class="child.icon ? child.icon.replace('uil ', '') : 'uil-angle-right'">
                                        </i>

                                        <span class="ltr:pl-3 rtl:pr-3 menu-title"
                                              x-text="child.name">
        </span>
                                    </a>
                                </li>
                            </template>
                        </ul>

                    </li>
                </template>

            </ul>
        </div>
    </nav>
</div>

<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("sidebar", () => ({

            menus: [],
            open: {},

            async init() {
                await this.loadMenus();
                this.openActiveParents();
            },

            async loadMenus() {
                const json = @json(json_decode(file_get_contents(config_path('menu.json')), true));

                let apiMenus = (json.data || [])
                    .filter(item => item.status || item.status === null);

                // Добавляем кастомный пункт "Отчеты" и подпункт "Статистика ОКК"
                const customMenus = [
                    {
                        id: 5000,
                        name: 'Отчеты',
                        shortname: 'reports',
                        parent_id: 0,
                        is_root: 1,
                        icon: 'uil uil-chart-pie',
                        page: null,
                        status: 1,
                        nom: 90
                    },
                    {
                        id: 5001,
                        name: 'Статистика ОКК',
                        shortname: 'occ_stats',
                        parent_id: 5000,
                        is_root: 2,
                        icon: 'uil uil-chart-bar',
                        page: '/occ_stats',
                        status: 1,
                        nom: 10
                    }
                ];

                this.menus = [...apiMenus, ...customMenus]
                    .sort((a, b) => (a.nom ?? 0) - (b.nom ?? 0));
            },

            get rootMenus() {
                return this.menus.filter(m => m.parent_id === 0);
            },

            children(parentId) {
                return this.menus.filter(m => m.parent_id === parentId);
            },

            hasChildren(item) {
                return this.children(item.id).length > 0;
            },

            onRootClick(item) {
                if (!this.hasChildren(item)) {
                    window.location.href = this.menuUrl(item);
                    return;
                }
                this.open[item.id] = !this.open[item.id];
            },

            menuUrl(item) {
                return item.page ?? '/';
            },

            isActive(item) {
                return window.location.pathname === this.menuUrl(item);
            },

            openActiveParents() {
                const current = this.menus.find(
                    m => this.menuUrl(m) === window.location.pathname
                );
                if (current?.parent_id) {
                    this.open[current.parent_id] = true;
                }
            }

        }));
    });
</script>

