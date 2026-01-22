<x-layout.default>

    {{-- =====================================================
        1. CONFIG И TABLE CONFIG
        ===================================================== --}}
    <script>
        // PHP → JS (конфиг сущности)
        window.CONFIG = @json($config);

        // Общий конфиг таблицы
        window.TABLE_CONFIG = {
            api: {
                list: 'https://ozgang.ourtest.net' + window.CONFIG.common.api + '/list'
            },

            auth: {
                type: 'bearer'
            },

            primaryKey: 'id',

            perPage: 20,

            columns: [],

            actions: [
                { type: 'view', url: '/advertisers/show' },
                { type: 'edit', url: '/advertisers/edit' },
                { type: 'delete' }
            ]
        };
    </script>

    {{-- =====================================================
        2. UI
        ===================================================== --}}
    <div x-data="dataTable">
        <script src="/assets/js/simple-datatables.js"></script>

        <div class="panel px-0 border-[#e0e6ed] dark:border-[#1b2e4b]">
            <div class="invoice-table">
                <table id="myTable" class="whitespace-nowrap w-full"></table>
            </div>
        </div>
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

                /* =============================
                   INIT
                ============================= */
                async init() {
                    this.buildColumnsFromConfig();
                    await this.loadData();
                    this.buildTable();
                },

                /* =============================
                   BUILD COLUMNS FROM CONFIG
                ============================= */
                buildColumnsFromConfig() {
                    const fields = window.CONFIG.fields;

                    this.config.columns = Object.entries(fields)
                        .filter(([_, field]) =>
                            field.field_mode?.includes('index')
                        )
                        .map(([key, field]) => ({
                            key: key,
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

                    if (!token) {
                        console.warn('No access token found');
                        return;
                    }

                    const response = await fetch(this.config.api.list, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer ${token}`
                        },
                        body: JSON.stringify({
                            page: 1,
                            perpage: 0
                        })
                    });

                    const json = await response.json();
                    console.log('API response:', json);

                    this.items = Array.isArray(json.data) ? json.data : [];
                    this.setTableData();
                },

                /* =============================
                   PREPARE DATA
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
                                'Actions'
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
                            return `<span class="badge ${col.options?.[value] ?? 'badge-secondary'}">
                                ${value}
                            </span>`;

                        case 'date':
                            return value ? new Date(value).toLocaleString() : '—';

                        case 'truncate':
                            if (!value) return '—';
                            const len = col.options?.length ?? 40;
                            return `<span title="${value}">
                                ${value.length > len ? value.slice(0, len) + '…' : value}
                            </span>`;

                        case 'number':
                            return `<span class="font-semibold">${value}</span>`;

                        default:
                            return value ?? '—';
                    }
                },

                /* =============================
                   ACTION ICONS
                ============================= */
                actionIcons() {
                    return {
                        view: `
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5"
                                      d="M3.27 15.29C2.42 14.19 2 13.64 2 12
                                         C2 10.36 2.42 9.81 3.27 8.70
                                         C4.97 6.50 7.82 4 12 4
                                         C16.18 4 19.03 6.50 20.73 8.70
                                         C21.58 9.81 22 10.36 22 12
                                         C22 13.64 21.58 14.19 20.73 15.29
                                         C19.03 17.50 16.18 20 12 20
                                         C7.82 20 4.97 17.50 3.27 15.29Z"
                                      stroke="currentColor" stroke-width="1.5"/>
                                <path d="M15 12C15 13.66 13.66 15 12 15
                                         C10.34 15 9 13.66 9 12
                                         C9 10.34 10.34 9 12 9
                                         C13.66 9 15 10.34 15 12Z"
                                      stroke="currentColor" stroke-width="1.5"/>
                            </svg>`,
                        edit: `
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5"
                                      d="M22 10.5V12C22 16.71 22 19.07
                                         20.53 20.53C19.07 22 16.71 22
                                         12 22C7.29 22 4.93 22
                                         3.46 20.53C2 19.07 2 16.71
                                         2 12C2 7.29 2 4.93
                                         3.46 3.46C4.93 2 7.29 2
                                         12 2H13.5"
                                      stroke="currentColor" stroke-width="1.5"/>
                                <path d="M17.3 2.8L10.7 9.42
                                         C10.28 9.82 10.08 10.03
                                         9.91 10.25C9.70 10.51
                                         9.53 10.80 9.38 11.10
                                         C9.26 11.35 9.17 11.62
                                         8.99 12.16L8.04 15.02
                                         C7.95 15.29 8.02 15.58
                                         8.22 15.78C8.42 15.98
                                         8.71 16.05 8.98 15.96
                                         L11.84 15.01C12.38 14.83
                                         12.65 14.74 12.90 14.62
                                         C13.20 14.47 13.49 14.30
                                         13.75 14.09C13.97 13.92
                                         14.18 13.72 14.58 13.31
                                         L21.19 6.70C22.27 5.62
                                         22.27 3.88 21.19 2.81
                                         C20.12 1.73 18.38 1.73
                                         17.30 2.81Z"
                                      stroke="currentColor" stroke-width="1.5"/>
                            </svg>`,
                        delete: `
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M20.5 6H3.5"
                                      stroke="currentColor" stroke-width="1.5"
                                      stroke-linecap="round"/>
                                <path d="M18.83 8.5L18.37 15.40
                                         C18.20 18.05 18.11 19.38
                                         17.24 20.19C16.38 21
                                         15.05 21 12.39 21H11.61
                                         C8.95 21 7.62 21 6.76
                                         20.19C5.89 19.38 5.80
                                         18.05 5.63 15.40L5.17 8.5"
                                      stroke="currentColor" stroke-width="1.5"
                                      stroke-linecap="round"/>
                            </svg>`
                    };
                },

                /* =============================
                   ACTIONS
                ============================= */
                renderActions(id) {
                    const icons = this.actionIcons();

                    return `
                        <div class="flex items-center gap-3">
                            ${this.config.actions.map(action => {
                        if (action.type === 'delete') {
                            return `
                                        <button class="hover:text-danger"
                                                @click.prevent="deleteRow(${id})"
                                                title="Delete">
                                            ${icons.delete}
                                        </button>`;
                        }

                        return `
                                    <a href="${action.url}/${id}"
                                       class="hover:text-primary"
                                       title="${action.type}">
                                        ${icons[action.type]}
                                    </a>`;
                    }).join('')}
                        </div>
                    `;
                },

                deleteRow(id) {
                    if (!confirm('Delete item #' + id + '?')) return;
                    this.items = this.items.filter(i => i.id !== id);
                    this.setTableData();
                    this.buildTable();
                }

            }));
        });
    </script>

</x-layout.default>
