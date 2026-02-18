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

        /* Серверная сортировка по клику на заголовок */
        .dt-server-sortable {
            cursor: pointer;
            user-select: none;
            white-space: nowrap;
        }
    </style>

    {{-- =====================================================
        2. UI
    ===================================================== --}}
    <div x-data="dataTable" x-init="init()">
        <script src="/assets/js/simple-datatables.js"></script>

        {{-- ✅ Field component: text --}}
        <script src="/assets/js/components/text.js"></script>

        <div class="panel px-0 border-[#e0e6ed] dark:border-[#1b2e4b]">
            <div class="invoice-table overflow-x-auto">
                <table id="myTable" class="whitespace-nowrap w-full"></table>
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
                    {{-- perpage --}}
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

                    {{-- pages --}}
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
                itemsById: {},       // ✅ id => row object
                datatable: null,
                dataArr: [],

                lookups: {},         // raw lookup arrays
                lookupMaps: {},      // id => name maps

                deleteModal: false,
                deleteId: null,

                // ===== SERVER PAGINATION STATE =====
                page: 1,
                perpage: window.TABLE_CONFIG.perPage ?? 20,
                count: 0,
                isLoading: false,

                // ===== SERVER SORT STATE =====
                sortBy: null,
                sortDir: 'desc',
                _headerClickHandler: null,
                _baseHeadings: [],

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

                    // ожидаем конфиг вида: 'order' => ['clicked_at' => 'desc']
                    if (order && typeof order === 'object' && !Array.isArray(order)) {
                        const entries = Object.entries(order);
                        if (entries.length > 0) {
                            this.sortBy = entries[0][0];
                            this.sortDir = String(entries[0][1] ?? 'desc').toLowerCase();
                            if (this.sortDir !== 'asc' && this.sortDir !== 'desc') this.sortDir = 'desc';
                            return;
                        }
                    }

                    // fallback: id desc
                    this.sortBy = 'id';
                    this.sortDir = 'desc';
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

                            // ✅ используем control для динамического компонента
                            control: field.control ?? 'text',

                            // ✅ прокидываем конфиг поля как есть
                            fieldConfig: field,

                            formatter: field.formatter ?? null,
                            options: field.formatter_options ?? null,
                            is_lookup: field.is_lookup ?? false,
                            lookup_id: field.lookup_id,
                            lookup_name: field.lookup_name
                        }));

                    this._baseHeadings = this.config.columns.map(c => c.title);
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

                        this.lookupMaps[key] = {};
                        list.forEach(item => {
                            this.lookupMaps[key][item[field.lookup_id]] = item[field.lookup_name];
                        });
                    }
                },

                /* =============================
                   LOAD DATA (SERVER PAGINATION + SERVER SORT)
                ============================= */
                async loadData(page = 1) {
                    const token = localStorage.getItem('access_token');
                    if (!token) return;

                    this.isLoading = true;

                    try {
                        const payload = {
                            page: Math.max(1, Number(page) || 1),
                            perpage: Number(this.perpage),

                            // Сортировка в нужном формате
                            order: [
                                {
                                    field: this.sortBy,
                                    direction: (this.sortDir || 'desc')
                                }
                            ]
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

                        // expected: pagination: { perpage, currentPage, count }
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

                changePerPage() {
                    this.loadData(1);
                },

                /* =============================
                   DATA PREP
                ============================= */
                setTableData() {
                    // ✅ карта id => объект строки
                    this.itemsById = {};
                    this.items.forEach(item => {
                        const id = item?.[this.config.primaryKey];
                        if (id !== null && id !== undefined) {
                            this.itemsById[id] = item;
                        }
                    });

                    // ✅ dataArr для datatable + кладём id последним элементом
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
                            headings: [...this._baseHeadings, 'Действия'],
                            data: this.dataArr
                        },

                        // отключаем клиентскую пагинацию/поиск
                        perPage: 999999,
                        searchable: false,

                        columns: this.buildColumns(),
                        layout: { top: '', bottom: '' }
                    });

                    this.bindServerSortHandlers();
                    this.setSortIndicators();
                },

                refreshTable() {
                    this.buildTable();
                },

                buildColumns() {
                    const cols = [];

                    this.config.columns.forEach((col, index) => {
                        cols.push({
                            select: index,
                            sortable: false, // сортировка только серверная

                            // ✅ В simple-datatables render(value, td, rowIndex, cellIndex)
                            render: (value, td, rowIndex, cellIndex) => this.format(col, value, rowIndex, cellIndex)
                        });
                    });

                    cols.push({
                        select: this.config.columns.length,
                        sortable: false,
                        render: id => this.renderActions(id)
                    });

                    return cols;
                },

                bindServerSortHandlers() {
                    const table = document.querySelector('#myTable');
                    if (!table) return;

                    const thead = table.querySelector('thead');
                    if (!thead) return;

                    if (this._headerClickHandler) {
                        thead.removeEventListener('click', this._headerClickHandler, true);
                    }

                    const ths = thead.querySelectorAll('th');
                    ths.forEach((th, idx) => {
                        if (idx < this.config.columns.length) th.classList.add('dt-server-sortable');
                        else th.classList.remove('dt-server-sortable');
                    });

                    this._headerClickHandler = async (e) => {
                        const th = e.target?.closest?.('th');
                        if (!th) return;

                        const all = Array.from(thead.querySelectorAll('th'));
                        const idx = all.indexOf(th);
                        if (idx < 0) return;

                        if (idx >= this.config.columns.length) return;

                        const key = this.config.columns[idx].key;

                        if (this.sortBy === key) {
                            this.sortDir = (this.sortDir === 'asc') ? 'desc' : 'asc';
                        } else {
                            this.sortBy = key;
                            this.sortDir = 'asc';
                        }

                        this.setSortIndicators();
                        await this.loadData(1);
                    };

                    thead.addEventListener('click', this._headerClickHandler, true);
                },

                setSortIndicators() {
                    const table = document.querySelector('#myTable');
                    if (!table) return;

                    const thead = table.querySelector('thead');
                    if (!thead) return;

                    const ths = thead.querySelectorAll('th');
                    ths.forEach((th, idx) => {
                        if (idx >= this.config.columns.length) {
                            th.textContent = 'Действия';
                            return;
                        }

                        const base = this._baseHeadings[idx] ?? th.textContent;
                        const key = this.config.columns[idx].key;

                        if (this.sortBy === key) {
                            th.textContent = `${base} ${this.sortDir === 'asc' ? '▲' : '▼'}`;
                        } else {
                            th.textContent = base;
                        }
                    });
                },

                /* =============================
                   FORMAT (DYNAMIC COMPONENTS)
                ============================= */
                format(col, value, rowIndex = null, cellIndex = null) {
                    // ✅ rowIndex — индекс строки в текущем dataArr
                    let rowId = null;

                    if (rowIndex !== null && rowIndex !== undefined && this.dataArr?.[rowIndex]) {
                        // id лежит последним элементом строки (мы его добавили в setTableData)
                        rowId = this.dataArr[rowIndex][this.config.columns.length] ?? null;
                    }

                    const row = (rowId !== null && rowId !== undefined)
                        ? (this.itemsById[rowId] ?? null)
                        : null;

                    // lookup (как было)
                    if (col.is_lookup) {
                        return this.lookupMaps[col.key]?.[value] ?? '—';
                    }

                    const control = col.control ?? 'text';

                    const cmp = (window.FieldComponents && window.FieldComponents[control])
                        ? window.FieldComponents[control]
                        : window.FieldComponents?.text;

                    // ✅ ВАЖНО: вызываем только через render()
                    if (cmp?.render) {
                        return cmp.render({
                            mode: 'index',
                            name: col.key,
                            value: value,

                            // ✅ конфиг поля
                            config: col.fieldConfig ?? null,

                            // ✅ вся строка
                            row: row,

                            // доп. если нужно компоненту
                            col: col,
                            rowIndex: rowIndex,
                            cellIndex: cellIndex,

                            disabled: true,
                            required: false,
                        });
                    }

                    // fallback
                    if (value === null || value === undefined || value === '') return '—';
                    return String(value);
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

                    const nextPage = (this.page > 1 && this.items.length === 1) ? (this.page - 1) : this.page;

                    this.closeDeleteModal();
                    await this.loadData(nextPage);
                },

            }));
        });
    </script>

</x-layout.default>
