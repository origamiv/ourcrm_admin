<x-layout.default>

    {{-- =====================================================
        1. CONFIG
    ===================================================== --}}
    <script>
        window.CONFIG = @json($config);

        window.TABLE_CONFIG = {
            api: {
                list:   '{{ config('app.api_url') }}' + window.CONFIG.common.api + '/list',
                delete: '{{ config('app.api_url') }}' + window.CONFIG.common.api
            },
            primaryKey: 'id',
            perPage: 20,
            columns: []
        };
    </script>

    {{-- =====================================================
        STYLES
    ===================================================== --}}
    {{-- ✅ Tabulator from CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tabulator-tables@6.3.0/dist/css/tabulator.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tabulator-tables@6.3.0/dist/css/tabulator_bootstrap5.min.css">

    <style>
        /* ===== Tabulator base ===== */
        #mainTabulator { width: 100%; }

        .dark .tabulator { background: transparent; }
        .tabulator .tabulator-header .tabulator-col { user-select: none; }
        .tabulator .tabulator-cell { vertical-align: middle; }

        /* ✅ горизонтальная прокрутка */
        #mainTabulator .tabulator-tableholder{
            overflow-x: auto !important;
        }

        /* ✅ убрать полосатость (зебру) */
        #mainTabulator .tabulator-row,
        #mainTabulator .tabulator-row.tabulator-row-even,
        #mainTabulator .tabulator-row.tabulator-row-odd {
            background-color: transparent !important;
        }
        #mainTabulator .tabulator-cell { background-color: transparent !important; }

        /* ✅ "серверная сортировка" — курсор у заголовков */
        #mainTabulator .tabulator-col.dt-server-sortable {
            cursor: pointer;
        }

        /* ✅ фиксированная колонка "Действия": непрозрачный фон + разделитель */
        #mainTabulator .tabulator-frozen,
        #mainTabulator .tabulator-frozen .tabulator-cell {
            background: #ffffff !important;
        }
        .dark #mainTabulator .tabulator-frozen,
        .dark #mainTabulator .tabulator-frozen .tabulator-cell {
            background: #0e1726 !important;
        }

        #mainTabulator .tabulator-frozen {
            box-shadow: -10px 0 14px -12px rgba(0,0,0,0.35);
        }
        .dark #mainTabulator .tabulator-frozen {
            box-shadow: -10px 0 14px -12px rgba(0,0,0,0.6);
        }

        /* ✅ чтобы в "Действия" не раздувало ширину из-за шрифтов/иконок */
        #mainTabulator .dt-actions-wrap{
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 12px;
            white-space: nowrap;
        }
        #mainTabulator .dt-actions-wrap a,
        #mainTabulator .dt-actions-wrap button{
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        #mainTabulator .dt-actions-wrap button{
            background: transparent;
            border: 0;
            padding: 0;
            line-height: 1;
        }

        /* =====================================================
           ✅ FIXED HEIGHT
           ===================================================== */
        #mainTabulator{
            height: 70vh;
            max-height: 70vh;
        }
        #mainTabulator .tabulator-tableholder{
            overflow-y: auto !important;
        }

        /* =====================================================
           ✅ Gear in header "Действия"
           ===================================================== */
        #mainTabulator .dt-actions-header{
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            width: 100%;
        }
        #mainTabulator .tabulator-col[tabulator-field="__actions"] .tabulator-col-title{
            width: 100%;
        }

        #mainTabulator .dt-colmenu-btn{
            width: 26px;
            height: 26px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            background: rgba(0,0,0,0.04);
        }
        .dark #mainTabulator .dt-colmenu-btn{
            background: rgba(255,255,255,0.08);
        }

        /* dropdown */
        .dt-colmenu{
            position: fixed;
            z-index: 99999;
            min-width: 240px;
            max-height: 340px;
            overflow: auto;
            border-radius: 12px;
            padding: 10px;
            background: #ffffff;
            border: 1px solid rgba(0,0,0,0.08);
            box-shadow: 0 16px 40px rgba(0,0,0,0.15);
        }
        .dark .dt-colmenu{
            background: #0e1726;
            border: 1px solid rgba(255,255,255,0.12);
            box-shadow: 0 16px 40px rgba(0,0,0,0.45);
        }

        .dt-colmenu-title{
            font-weight: 600;
            font-size: 13px;
            margin-bottom: 8px;
            color: rgba(0,0,0,0.75);
        }
        .dark .dt-colmenu-title{
            color: rgba(255,255,255,0.8);
        }

        .dt-colmenu-item{
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 8px;
            border-radius: 10px;
            cursor: pointer;
            user-select: none;
        }
        .dt-colmenu-item:hover{
            background: rgba(0,0,0,0.05);
        }
        .dark .dt-colmenu-item:hover{
            background: rgba(255,255,255,0.08);
        }
    </style>

    {{-- =====================================================
        2. UI
    ===================================================== --}}
    <div x-data="dataTable" x-init="init()">

        {{-- ✅ Field component: text --}}
        <script src="/assets/js/components/text.js"></script>

        <div class="panel px-0 border-[#e0e6ed] dark:border-[#1b2e4b]">
            <div class="invoice-table overflow-x-auto px-4 pt-4">
                <div id="mainTabulator"></div>
            </div>

            {{-- ⚙️ Dropdown выбора колонок --}}
            <div
                x-show="colMenuOpen"
                x-cloak
                class="dt-colmenu"
                :style="`left:${colMenuPos.x}px; top:${colMenuPos.y}px;`"
            >
                <div class="dt-colmenu-title">Колонки</div>

                <template x-for="c in colMenuItems" :key="c.field">
                    <div class="dt-colmenu-item" @click="toggleColumnVisibility(c.field)">
                        {{-- важно: чекбокс сам переключает видимость --}}
                        <input type="checkbox"
                               :checked="c.visible"
                               @click.stop="toggleColumnVisibility(c.field)" />
                        <div class="text-sm" x-text="c.title"></div>
                    </div>
                </template>
            </div>

            {{-- SERVER PAGER --}}
            <div class="flex flex-wrap items-center justify-between gap-3 px-4 py-3 border-t border-[#e0e6ed] dark:border-[#1b2e4b]"
                 x-show="usePagination">

                <div class="text-sm text-white-dark">
                    Страница <span class="font-semibold" x-text="page"></span>
                    из <span class="font-semibold" x-text="totalPages"></span>
                    • Всего: <span class="font-semibold" x-text="count"></span>
                </div>

                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-white-dark">На странице</span>
                        <select class="form-select form-select-sm w-[110px]"
                                x-model.number="perpage"
                                :disabled="isLoading"
                                @change="changePerPage()">
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="200">200</option>
                            <option value="-1">Все</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <button class="btn btn-outline-secondary"
                                :disabled="page <= 1 || isLoading"
                                @click="goToPage(page - 1)">
                            ←
                        </button>

                        <template x-for="p in pagesToShow" :key="p">
                            <button class="btn"
                                    :class="p === page ? 'btn-primary' : 'btn-outline-secondary'"
                                    :disabled="isLoading"
                                    @click="goToPage(p)"
                                    x-text="p">
                            </button>
                        </template>

                        <button class="btn btn-outline-secondary"
                                :disabled="page >= totalPages || isLoading"
                                @click="goToPage(page + 1)">
                            →
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- DELETE MODAL --}}
        <template x-teleport="body">
            <div x-show="deleteModal" x-cloak
                 class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50">
                <div @click.away="closeDeleteModal"
                     class="bg-white dark:bg-[#0e1726] rounded-xl shadow-xl max-w-md p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <i class="uil uil-exclamation-triangle text-danger text-2xl"></i>
                        <div class="text-lg font-semibold">Подтверждение удаления</div>
                    </div>

                    <div class="text-white-dark mb-6">
                        Вы действительно хотите удалить эту запись?
                    </div>

                    <div class="flex justify-end gap-3">
                        <button class="btn btn-outline-secondary" @click="closeDeleteModal">Нет</button>
                        <button class="btn btn-danger" @click="confirmDelete">Да</button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- =====================================================
        3. LOGIC
    ===================================================== --}}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tabulator-tables@6.3.0/dist/js/tabulator.min.js"></script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dataTable', () => ({

                config: window.TABLE_CONFIG,

                items: [],
                tabulator: null,

                lookups: {},
                lookupMaps: {},

                deleteModal: false,
                deleteId: null,

                page: 1,
                perpage: window.TABLE_CONFIG.perPage ?? 20,
                count: 0,
                isLoading: false,

                sortBy: null,
                sortDir: 'desc',

                // ⚙️ column chooser
                colMenuOpen: false,
                colMenuItems: [],
                colMenuPos: { x: 0, y: 0 },

                get usePagination() {
                    return (this.perpage ?? 0) !== -1;
                },

                get totalPages() {
                    if (!this.usePagination) return 1;
                    const pp = Number(this.perpage) || 1;
                    return Math.max(1, Math.ceil((Number(this.count) || 0) / pp));
                },

                get pagesToShow() {
                    const total = this.totalPages;
                    const cur = this.page;

                    const windowSize = 5;
                    let start = Math.max(1, cur - Math.floor(windowSize / 2));
                    let end = start + windowSize - 1;

                    if (end > total) {
                        end = total;
                        start = Math.max(1, end - windowSize + 1);
                    }

                    const pages = [];
                    for (let i = start; i <= end; i++) pages.push(i);
                    return pages;
                },

                async init() {
                    this.buildColumnsFromConfig();
                    this.initSortFromConfig();

                    await this.loadLookups();

                    await this.loadData(1);
                    this.buildTable();

                    window.addEventListener('datatable-delete', e => {
                        this.openDeleteModal(e.detail);
                    });

                    window.addEventListener('datatable-colmenu-toggle', (e) => {
                        this.toggleColMenu(e.detail?.x, e.detail?.y);
                    });

                    // ✅ закрытие по "клику в пустоту" (CAPTURE — Tabulator иногда гасит bubbling)
                    document.addEventListener('mousedown', (e) => {
                        if (!this.colMenuOpen) return;

                        if (!e.target.closest('.dt-colmenu') && !e.target.closest('.dt-colmenu-btn')) {
                            this.closeColMenu();
                        }
                    }, true);
                },

                initSortFromConfig() {
                    const order = window.CONFIG.order ?? null;

                    if (order && typeof order === 'object' && !Array.isArray(order)) {
                        const entries = Object.entries(order);
                        if (entries.length > 0) {
                            this.sortBy = entries[0][0];
                            this.sortDir = String(entries[0][1] ?? 'desc').toLowerCase();
                            if (this.sortDir !== 'asc' && this.sortDir !== 'desc') this.sortDir = 'desc';
                            return;
                        }
                    }

                    this.sortBy = 'id';
                    this.sortDir = 'desc';
                },

                buildColumnsFromConfig() {
                    const fields = window.CONFIG.fields;

                    this.config.columns = Object.entries(fields)
                        .filter(([_, field]) => field.field_mode?.includes('index'))
                        .map(([key, field]) => ({
                            key,
                            title: field.name,
                            control: field.control ?? 'text',
                            fieldConfig: field,
                            formatter: field.formatter ?? null,
                            options: field.formatter_options ?? null,
                            is_lookup: field.is_lookup ?? false,
                            lookup_id: field.lookup_id,
                            lookup_name: field.lookup_name
                        }));
                },

                async loadLookups() {
                    const token = localStorage.getItem('access_token');
                    if (!token) return;

                    const lookupFields = Object.entries(window.CONFIG.fields)
                        .filter(([_, f]) => f.is_lookup);

                    for (const [key, field] of lookupFields) {
                        const res = await axios.post(
                            `{{ config('app.api_url') }}${field.lookup_api}/list`,
                            { page: 1, perpage: 100 },
                            {
                                headers: {
                                    'Accept': 'application/json',
                                    'Authorization': `Bearer ${token}`
                                }
                            }
                        );

                        const list = res.data?.data ?? [];
                        this.lookups[key] = list;

                        this.lookupMaps[key] = {};
                        list.forEach(item => {
                            this.lookupMaps[key][item[field.lookup_id]] = item[field.lookup_name];
                        });
                    }
                },

                async loadData(page = 1) {
                    const token = localStorage.getItem('access_token');
                    if (!token) return;

                    this.isLoading = true;

                    try {
                        const payload = {
                            page: Math.max(1, Number(page) || 1),
                            perpage: Number(this.perpage),
                            order: [{ field: this.sortBy, direction: (this.sortDir || 'desc') }]
                        };

                        const response = await fetch(this.config.api.list, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'Authorization': `Bearer ${token}`
                            },
                            body: JSON.stringify(payload)
                        });

                        const json = await response.json();

                        this.items = Array.isArray(json.data) ? json.data : [];

                        const p = json.pagination ?? {};
                        this.perpage = (p.perpage !== undefined) ? p.perpage : this.perpage;
                        this.page = (p.currentPage !== undefined) ? p.currentPage : payload.page;
                        this.count = (p.count !== undefined) ? p.count : this.items.length;

                        if (this.tabulator) {
                            await this.tabulator.replaceData(this.items);
                            this.syncTabulatorSortIndicator();
                            if (this.colMenuOpen) this.refreshColMenuItems();
                        }

                    } finally {
                        this.isLoading = false;
                    }
                },

                goToPage(p) {
                    const target = Math.min(this.totalPages, Math.max(1, Number(p) || 1));
                    if (target === this.page) return;
                    this.loadData(target);
                },

                changePerPage() {
                    this.loadData(1);
                },

                async onHeaderSort(field) {
                    if (this.isLoading) return;
                    if (!field || field === '__actions') return;

                    if (this.sortBy === field) {
                        this.sortDir = (this.sortDir === 'asc') ? 'desc' : 'asc';
                    } else {
                        this.sortBy = field;
                        this.sortDir = 'asc';
                    }

                    await this.loadData(1);
                },

                buildTable() {
                    if (!window.Tabulator) {
                        console.error('Tabulator not loaded');
                        return;
                    }

                    if (this.tabulator) {
                        try { this.tabulator.destroy(); } catch (e) {}
                        this.tabulator = null;
                    }

                    const entity = window.CONFIG.common.shortname;
                    const primaryKey = this.config.primaryKey;

                    const cols = this.config.columns.map((col) => {
                        return {
                            title: col.title,
                            field: col.key,
                            headerSort: false,
                            cssClass: "dt-server-sortable",
                            headerClick: async () => {
                                await this.onHeaderSort(col.key);
                            },
                            formatter: (cell) => {
                                const value = cell.getValue();
                                const row = cell.getRow()?.getData?.() ?? null;

                                if (col.is_lookup) {
                                    return this.lookupMaps[col.key]?.[value] ?? '—';
                                }

                                const control = col.control ?? 'text';
                                const cmp = (window.FieldComponents && window.FieldComponents[control])
                                    ? window.FieldComponents[control]
                                    : window.FieldComponents?.text;

                                const payload = {
                                    mode: 'index',
                                    entity: entity,
                                    name: col.key,
                                    value: value,
                                    config: col.fieldConfig ?? null,
                                    row: row,
                                    col: col,
                                    disabled: true,
                                    required: false,
                                };

                                if (cmp?.render) return cmp.render(payload);
                                if (cmp?.index)  return cmp.index(payload);

                                if (value === null || value === undefined || value === '') return '—';
                                return String(value);
                            }
                        };
                    });

                    // ✅ Actions column with gear IN HEADER
                    cols.push({
                        title: 'Действия',
                        field: '__actions',
                        headerSort: false,
                        frozen: true,
                        width: 140,
                        minWidth: 140,
                        maxWidth: 140,
                        widthGrow: 0,
                        widthShrink: 0,
                        hozAlign: 'right',
                        headerHozAlign: 'right',

                        titleFormatter: () => {
                            return `
                                <div class="dt-actions-header">
                                    <span>Действия</span>
                                    <button class="dt-colmenu-btn text-primary" title="Колонки" type="button">
                                        <i class="uil uil-setting"></i>
                                    </button>
                                </div>
                            `;
                        },

                        headerClick: (e) => {
                            const btn = e.target.closest('.dt-colmenu-btn');
                            if (!btn) return;

                            e.preventDefault();
                            e.stopPropagation();

                            const r = btn.getBoundingClientRect();
                            window.dispatchEvent(new CustomEvent('datatable-colmenu-toggle', {
                                detail: {
                                    x: Math.round(r.right - 6 - 240),
                                    y: Math.round(r.bottom + 8)
                                }
                            }));
                        },

                        formatter: (cell) => {
                            const row = cell.getRow()?.getData?.() ?? {};
                            const id = row?.[primaryKey];

                            return `
                                <div class="dt-actions-wrap text-xl">
                                    <a href="/${entity}/${id}/show" class="text-info"><i class="uil uil-eye"></i></a>
                                    <a href="/${entity}/${id}/edit" class="text-warning"><i class="uil uil-edit"></i></a>
                                    <button class="text-danger"
                                        onclick="window.dispatchEvent(new CustomEvent('datatable-delete',{detail:${id}}))">
                                        <i class="uil uil-trash-alt"></i>
                                    </button>
                                </div>
                            `;
                        }
                    });

                    this.tabulator = new Tabulator('#mainTabulator', {
                        data: this.items,
                        layout: "fitDataTable",
                        responsiveLayout: false,
                        reactiveData: false,
                        index: primaryKey,
                        columns: cols,
                        pagination: false,
                        height: "70vh",
                        columnDefaults: {
                            minWidth: 140,
                            resizable: true
                        },
                    });

                    // ✅ ещё одна гарантия закрытия по клику "в пустоту" внутри табличной области
                    this.tabulator.on("tableClick", () => {
                        this.closeColMenu();
                    });
                    this.tabulator.on("headerClick", () => {
                        // headerClick по другим колонкам — тоже закрываем
                        // (шестерёнка сама откроет, а event там stopPropagation)
                        this.closeColMenu();
                    });

                    this.syncTabulatorSortIndicator();
                    this.refreshColMenuItems();
                },

                syncTabulatorSortIndicator() {
                    if (!this.tabulator) return;

                    const holder = document.querySelector('#mainTabulator');
                    if (!holder) return;

                    const headers = holder.querySelectorAll('.tabulator-col');
                    headers.forEach((h) => {
                        const field = h.getAttribute('tabulator-field');
                        const titleEl = h.querySelector('.tabulator-col-title');
                        if (!titleEl) return;

                        let baseTitle = titleEl.textContent.replace(/[▲▼]\s*$/, '').trim();

                        const cfg = this.config.columns.find(c => c.key === field);
                        if (cfg?.title) baseTitle = cfg.title;
                        if (field === '__actions') baseTitle = 'Действия';

                        if (field && field === this.sortBy) {
                            titleEl.textContent = `${baseTitle} ${this.sortDir === 'asc' ? '▲' : '▼'}`;
                        } else {
                            titleEl.textContent = baseTitle;
                        }
                    });
                },

                // ============================
                // ⚙️ Column chooser
                // ============================
                toggleColMenu(x = null, y = null) {
                    if (x !== null && y !== null) {
                        const w = 240;
                        const pad = 8;
                        const maxX = window.innerWidth - w - pad;
                        this.colMenuPos.x = Math.max(pad, Math.min(Number(x) || pad, maxX));
                        this.colMenuPos.y = Math.max(pad, Number(y) || pad);
                    }

                    this.colMenuOpen = !this.colMenuOpen;
                    if (this.colMenuOpen) this.refreshColMenuItems();
                },

                closeColMenu() {
                    this.colMenuOpen = false;
                },

                refreshColMenuItems() {
                    if (!this.tabulator) return;

                    const columns = this.tabulator.getColumns()
                        .map(c => c.getDefinition())
                        .filter(def => def.field && def.field !== '__actions');

                    this.colMenuItems = columns.map(def => {
                        const col = this.tabulator.getColumn(def.field);
                        return {
                            field: def.field,
                            title: def.title ?? def.field,
                            visible: col ? col.isVisible() : true
                        };
                    });
                },

                toggleColumnVisibility(field) {
                    if (!this.tabulator) return;

                    const col = this.tabulator.getColumn(field);
                    if (!col) return;

                    if (col.isVisible()) col.hide();
                    else col.show();

                    this.refreshColMenuItems();
                },

                // ============================
                // 🗑 Delete
                // ============================
                openDeleteModal(id) {
                    this.deleteId = id;
                    this.deleteModal = true;
                },

                closeDeleteModal() {
                    this.deleteId = null;
                    this.deleteModal = false;
                },

                async confirmDelete() {
                    const token = localStorage.getItem('access_token');
                    if (!token) return;

                    await fetch(`${this.config.api.delete}/${this.deleteId}/destroy`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${token}`
                        }
                    });

                    const nextPage = (this.page > 1 && this.items.length === 1) ? (this.page - 1) : this.page;

                    this.closeDeleteModal();
                    await this.loadData(nextPage);
                },

            }));
        });
    </script>

</x-layout.default>
