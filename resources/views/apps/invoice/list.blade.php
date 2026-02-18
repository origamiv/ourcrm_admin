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
        .dark .tabulator { background: transparent; }
        .tabulator .tabulator-header .tabulator-col { user-select: none; }
        .tabulator .tabulator-cell { vertical-align: middle; }

        /* ✅ убрать полосатость (зебру) */
        #mainTabulator .tabulator-row,
        #mainTabulator .tabulator-row.tabulator-row-even,
        #mainTabulator .tabulator-row.tabulator-row-odd {
            background-color: transparent !important;
        }
        #mainTabulator .tabulator-cell { background-color: transparent !important; }

        /* ✅ непрозрачный фон для фиксированной колонки "Действия" (чтобы не было "каши") */
        #mainTabulator .tabulator-frozen,
        #mainTabulator .tabulator-frozen .tabulator-cell {
            background: #ffffff !important;
        }
        .dark #mainTabulator .tabulator-frozen,
        .dark #mainTabulator .tabulator-frozen .tabulator-cell {
            background: #0e1726 !important;
        }

        /* небольшой разделитель слева от фиксированной колонки */
        #mainTabulator .tabulator-frozen {
            box-shadow: -10px 0 14px -12px rgba(0,0,0,0.35);
        }
        .dark #mainTabulator .tabulator-frozen {
            box-shadow: -10px 0 14px -12px rgba(0,0,0,0.6);
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
                            this.tabulator.replaceData(this.items);
                            this.syncTabulatorSortIndicator();
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
                            headerSort: true,

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
                                    name: col.key,
                                    value: value,
                                    config: col.fieldConfig ?? null,
                                    row: row,
                                    col: col,
                                    disabled: true,
                                    required: false,
                                };

                                if (cmp?.render) return cmp.render(payload);
                                if (value === null || value === undefined || value === '') return '—';
                                return String(value);
                            }
                        };
                    });

                    // ✅ fixed actions column
                    cols.push({
                        title: 'Действия',
                        field: '__actions',
                        headerSort: false,
                        frozen: true,
                        width: 140,
                        minWidth: 120,
                        hozAlign: 'right',
                        headerHozAlign: 'right',

                        formatter: (cell) => {
                            const row = cell.getRow()?.getData?.() ?? {};
                            const id = row?.[primaryKey];

                            return `
                                <div class="flex items-center justify-end gap-3 text-xl">
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
                        layout: 'fitColumns',
                        reactiveData: false,
                        index: primaryKey,
                        columns: cols,
                        pagination: false,
                        height: "auto",
                    });

                    this.tabulator.on("headerClick", async (e, column) => {
                        if (this.isLoading) return;
                        if (!column) return;

                        const field = column.getField();
                        if (!field || field === '__actions') return;

                        if (this.sortBy === field) {
                            this.sortDir = (this.sortDir === 'asc') ? 'desc' : 'asc';
                        } else {
                            this.sortBy = field;
                            this.sortDir = 'asc';
                        }

                        await this.loadData(1);
                    });

                    this.syncTabulatorSortIndicator();
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
