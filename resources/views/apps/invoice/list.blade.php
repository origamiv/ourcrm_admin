<x-layout.default>

    {{-- =====================================================
        1. CONFIG
    ===================================================== --}}
    <script>
        window.CONFIG = @json($config);

        window.TABLE_CONFIG = {
            api: {
                list:   'https://ozgang.ourtest.net' + window.CONFIG.common.api + '/list',
                delete: 'https://ozgang.ourtest.net' + window.CONFIG.common.api
            },
            primaryKey: 'id',
            perPage: 20,
            columns: []
        };
    </script>

    {{-- =====================================================
        2. UI
    ===================================================== --}}
    <div x-data="dataTable" x-init="init()">
        <script src="/assets/js/simple-datatables.js"></script>

        <div class="panel px-0 border-[#e0e6ed] dark:border-[#1b2e4b]">
            <div class="invoice-table">
                <table id="myTable" class="whitespace-nowrap w-full"></table>
            </div>
        </div>

        {{-- =============================
            DELETE CONFIRM MODAL
        ============================= --}}
        <!-- DELETE CONFIRM MODAL -->
        <template x-teleport="body">
            <div
                x-show="deleteModal"
                x-cloak
                class="fixed inset-0 z-[99999]
               flex items-center justify-center
               bg-black/50"
            >
                <div
                    @click.away="closeDeleteModal"
                    class="bg-white dark:bg-[#0e1726]
                   rounded-xl shadow-xl
                   w-full max-w-md
                   mx-4 p-6"
                    style="width:auto"
                >
                    <div class="flex items-center gap-3 mb-4">
                        <i class="uil uil-exclamation-triangle text-danger text-2xl"></i>
                        <div class="text-lg font-semibold">
                            Подтверждение удаления
                        </div>
                    </div>

                    <div class="text-white-dark mb-6">
                        Вы действительно хотите удалить эту запись?
                    </div>

                    <div class="flex justify-end gap-3">
                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            @click="closeDeleteModal"
                        >
                            Нет
                        </button>

                        <button
                            type="button"
                            class="btn btn-danger"
                            @click="confirmDelete"
                        >
                            Да
                        </button>
                    </div>
                </div>
            </div>
        </template>


    </div>

    {{-- =====================================================
        3. LOGIC
    ===================================================== --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dataTable', () => ({

                config: window.TABLE_CONFIG,

                items: [],
                datatable: null,
                dataArr: [],

                deleteModal: false,
                deleteId: null,

                /* =============================
                   INIT
                ============================= */
                async init() {
                    this.buildColumnsFromConfig();
                    await this.loadData();
                    this.buildTable();
                    this.injectCreateButton();

                    window.addEventListener('datatable-delete', (e) => {
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
                            options: field.formatter_options ?? null
                        }));
                },

                /* =============================
                   LOAD DATA
                ============================= */
                async loadData() {
                    const token = localStorage.getItem('access_token');
                    if (!token) return;

                    const response = await fetch(this.config.api.list, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer ${token}`
                        },
                        body: JSON.stringify({ page: 1, perpage: 0 })
                    });

                    const json = await response.json();
                    this.items = Array.isArray(json.data) ? json.data : [];
                    this.setTableData();
                },

                /* =============================
                   DATA PREP
                ============================= */
                setTableData() {
                    this.dataArr = this.items.map(item =>
                        this.config.columns
                            .map(col => item[col.key] ?? '—')
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
                            headings: [
                                ...this.config.columns.map(c => c.title),
                                'Действия'
                            ],
                            data: this.dataArr
                        },
                        perPage: this.config.perPage,
                        columns: this.buildColumns(),
                        layout: {
                            top: '{search}',
                            bottom: '{info}{select}{pager}'
                        }
                    });
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
                   FORMATTERS
                ============================= */
                format(col, value) {
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

                            <a href="/${entity}/${id}/show"
                               class="text-info"
                               title="Просмотр">
                                <i class="uil uil-eye"></i>
                            </a>

                            <a href="/${entity}/${id}/edit"
                               class="text-warning"
                               title="Редактировать">
                                <i class="uil uil-edit"></i>
                            </a>

                            <button class="text-danger"
                                title="Удалить"
                                onclick="window.dispatchEvent(
                                    new CustomEvent('datatable-delete', { detail: ${id} })
                                )">
                                <i class="uil uil-trash-alt"></i>
                            </button>

                        </div>
                    `;
                },

                /* =============================
                   DELETE FLOW
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
                    if (!this.deleteId) return;

                    const token = localStorage.getItem('access_token');
                    if (!token) return;

                    await fetch(
                        `${this.config.api.delete}/${this.deleteId}/destroy`,
                        {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'Authorization': `Bearer ${token}`
                            }
                        }
                    );

                    this.items = this.items.filter(
                        item => item.id !== this.deleteId
                    );

                    this.setTableData();
                    this.buildTable();
                    this.injectCreateButton();

                    this.closeDeleteModal();
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
