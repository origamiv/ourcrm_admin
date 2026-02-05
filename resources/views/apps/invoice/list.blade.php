<x-layout.default>

    {{-- =====================================================
        1. CONFIG
    ===================================================== --}}
    <script>
        window.CONFIG = @json($config);

        console.log(window.CONFIG.common.api);
        window.TABLE_CONFIG = {
            api: {
                list:   '{{ config('app.api_url') }}' + window.CONFIG.common.api + '/list',
                delete: '{{ config('app.api_url') }}' + window.CONFIG.common.api
            },
            primaryKey: 'id',
            perPage: 20,
            columns: []
        };
        console.log(window.TABLE_CONFIG);
    </script>

    {{-- =====================================================
        STYLES
    ===================================================== --}}
    <style>
        .dataTable-table thead th:last-child {
            position: sticky;
            right: 0;
            z-index: 7;
            background: #ffffff;
        }

        .dataTable-table tbody td:last-child {
            position: sticky;
            right: 0;
            z-index: 6;
            background: #ffffff;
            box-shadow: -8px 0 12px -8px rgba(0,0,0,0.15);
        }

        .dark .dataTable-table thead th:last-child,
        .dark .dataTable-table tbody td:last-child {
            background: #0e1726;
        }
    </style>

    {{-- =====================================================
        2. UI
    ===================================================== --}}
    <div x-data="dataTable" x-init="init()">
        <script src="/assets/js/simple-datatables.js"></script>

        <div class="panel px-0 border-[#e0e6ed] dark:border-[#1b2e4b]">
            <div class="invoice-table overflow-x-auto">
                <table id="myTable" class="whitespace-nowrap w-full"></table>
            </div>

            {{-- SERVER PAGER --}}
            <div class="flex items-center justify-between px-4 py-3 border-t border-[#e0e6ed] dark:border-[#1b2e4b]"
                 x-show="usePagination">
                <div class="text-sm text-white-dark">
                    Страница <span class="font-semibold" x-text="page"></span>
                    из <span class="font-semibold" x-text="totalPages"></span>
                    • Всего: <span class="font-semibold" x-text="count"></span>
                </div>

                <div class="flex items-center gap-2">
                    <button class="btn btn-outline-secondary"
                            :disabled="page <= 1 || isLoading"
                            @click="goToPage(page - 1)">
                        ←
                    </button>

                    <template x-for="p in pagesToShow" :key="p">
                        <button
                            class="btn"
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

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dataTable', () => ({

                config: window.TABLE_CONFIG,

                items: [],
                datatable: null,
                dataArr: [],

                lookups: {},       // raw lookup arrays
                lookupMaps: {},    // id => name maps

                deleteModal: false,
                deleteId: null,

                // ===== SERVER PAGINATION STATE =====
                page: 1,
                perpage: window.TABLE_CONFIG.perPage ?? 20,
                count: 0,
                isLoading: false,

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

                /* =============================
                   INIT
                ============================= */
                async init() {
                    this.buildColumnsFromConfig();
                    await this.loadLookups();

                    // initial load
                    await this.loadData(1);

                    // build table once and then refresh it via rebuild (safe)
                    this.buildTable();
                    this.injectCreateButton();

                    window.addEventListener('datatable-delete', e => {
                        this.openDeleteModal(e.detail);
                    });
                },

                /* =============================
                   COLUMNS
                ============================= */
                buildColumnsFromConfig() {
                    const fields = window.CONFIG.fields;

                    this.config.columns = Object.entries(fields)
                        .filter(([_, field]) => field.field_mode?.includes('index'))
                        .map(([key, field]) => ({
                            key,
                            title: field.name,
                            formatter: field.formatter ?? null,
                            options: field.formatter_options ?? null,
                            is_lookup: field.is_lookup ?? false,
                            lookup_id: field.lookup_id,
                            lookup_name: field.lookup_name
                        }));
                },

                /* =============================
                   LOOKUPS
                ============================= */
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

                        // build map: id => name
                        this.lookupMaps[key] = {};
                        list.forEach(item => {
                            this.lookupMaps[key][item[field.lookup_id]] =
                                item[field.lookup_name];
                        });
                    }
                },

                /* =============================
                   LOAD DATA (SERVER PAGINATION)
                ============================= */
                async loadData(page = 1) {
                    const token = localStorage.getItem('access_token');
                    if (!token) return;

                    this.isLoading = true;

                    try {
                        const payload = {
                            page: Math.max(1, Number(page) || 1),
                            perpage: Number(this.perpage)
                        };

                        console.log('api2', this.config.api.list, payload);

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

                        // expected:
                        // pagination: { perpage, currentPage, count }
                        const p = json.pagination ?? {};
                        this.perpage = (p.perpage !== undefined) ? p.perpage : this.perpage;
                        this.page = (p.currentPage !== undefined) ? p.currentPage : payload.page;
                        this.count = (p.count !== undefined) ? p.count : this.items.length;

                        this.setTableData();
                        this.refreshTable();

                    } finally {
                        this.isLoading = false;
                    }
                },

                goToPage(p) {
                    const target = Math.min(this.totalPages, Math.max(1, Number(p) || 1));
                    if (target === this.page) return;
                    this.loadData(target);
                },

                /* =============================
                   DATA PREP
                ============================= */
                setTableData() {
                    this.dataArr = this.items.map(item =>
                        this.config.columns
                            .map(col => item[col.key] ?? null)
                            .concat(item[this.config.primaryKey])
                    );
                },

                /* =============================
                   TABLE
                ============================= */
                buildTable() {
                    if (this.datatable) this.datatable.destroy();

                    this.datatable = new simpleDatatables.DataTable('#myTable', {
                        data: {
                            headings: [...this.config.columns.map(c => c.title), 'Действия'],
                            data: this.dataArr
                        },

                        // IMPORTANT:
                        // simple-datatables pagination is client-side only.
                        // We "disable" it by setting huge perPage and removing {pager}.
                        perPage: 999999,

                        columns: this.buildColumns(),
                        layout: {
                            top: '{search}',
                            bottom: '{info}{select}'
                        }
                    });
                },

                refreshTable() {
                    // safe way: rebuild table with current page data
                    this.buildTable();
                    this.injectCreateButton();
                },

                buildColumns() {
                    const cols = [];

                    this.config.columns.forEach((col, index) => {
                        cols.push({
                            select: index,
                            render: value => this.format(col, value)
                        });
                    });

                    cols.push({
                        select: this.config.columns.length,
                        sortable: false,
                        render: id => this.renderActions(id)
                    });

                    return cols;
                },

                /* =============================
                   FORMAT
                ============================= */
                format(col, value) {
                    if (col.is_lookup) {
                        return this.lookupMaps[col.key]?.[value] ?? '—';
                    }

                    switch (col.formatter) {
                        case 'badge':
                            return `<span class="badge ${col.options?.[value] ?? 'badge-secondary'}">${value}</span>`;
                        case 'date':
                            return value ? new Date(value).toLocaleString() : '—';
                        case 'truncate':
                            if (!value) return '—';
                            const len = col.options?.length ?? 40;
                            return `<span title="${value}">${value.length > len ? value.slice(0, len) + '…' : value}</span>`;
                        case 'number':
                            return `<span class="font-semibold">${value}</span>`;
                        default:
                            return value ?? '—';
                    }
                },

                /* =============================
                   ACTIONS
                ============================= */
                renderActions(id) {
                    const entity = window.CONFIG.common.shortname;

                    return `
                        <div class="flex items-center gap-3 text-xl">
                            <a href="/${entity}/${id}/show" class="text-info"><i class="uil uil-eye"></i></a>
                            <a href="/${entity}/${id}/edit" class="text-warning"><i class="uil uil-edit"></i></a>
                            <button class="text-danger"
                                onclick="window.dispatchEvent(new CustomEvent('datatable-delete',{detail:${id}}))">
                                <i class="uil uil-trash-alt"></i>
                            </button>
                        </div>
                    `;
                },

                /* =============================
                   DELETE
                ============================= */
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

                    // reload current page to keep correct count/pages
                    const nextPage = (this.page > 1 && this.items.length === 1) ? (this.page - 1) : this.page;

                    this.closeDeleteModal();
                    await this.loadData(nextPage);
                },

                /* =============================
                   CREATE BUTTON
                ============================= */
                injectCreateButton() {
                    const top = document.querySelector('.dataTable-top');
                    if (!top || top.querySelector('.btn-create')) return;

                    const entity = window.CONFIG.common.shortname;

                    const btn = document.createElement('a');
                    btn.href = `/${entity}/create`;
                    btn.className = 'btn btn-primary btn-create ml-3';
                    btn.innerHTML = '<i class="uil uil-plus mr-1"></i> Добавить запись';

                    top.appendChild(btn);
                }

            }));
        });
    </script>

</x-layout.default>
