<x-layout.default>

    {{-- =====================================================
        1. КОНФИГУРАЦИЯ ТАБЛИЦЫ (МЕНЯЕТСЯ ТОЛЬКО ЭТО)
        ===================================================== --}}
    <script>
        window.TABLE_CONFIG = {
            api: {
                list: 'https://ozgang.ourtest.net/api/v1/advertisers/list'
            },

            auth: {
                type: 'bearer',
                token: '1|GObXOn2UN2My5d5o4NaqXONWH2y7QfzdM06ZaTzwb00c099e'
            },

            primaryKey: 'id',

            perPage: 20,

            columns: [
                {
                    key: 'id',
                    title: 'ID'
                },
                {
                    key: 'name',
                    title: 'Name'
                },
                {
                    key: 'state',
                    title: 'State',
                    formatter: 'badge',
                    options: {
                        active: 'badge-outline-success',
                        inactive: 'badge-outline-danger'
                    }
                },
                {
                    key: 'offers',
                    title: 'Offers',
                    formatter: 'number'
                },
                {
                    key: 'postback_url',
                    title: 'Postback',
                    formatter: 'truncate',
                    options: { length: 40 }
                },
                {
                    key: 'template_name',
                    title: 'Template'
                },
                {
                    key: 'ext_id',
                    title: 'Ext ID'
                },
                {
                    key: 'created_at',
                    title: 'Created',
                    formatter: 'date'
                }
            ],

            actions: [
                { type: 'view', url: '/advertisers/view' },
                { type: 'edit', url: '/advertisers/edit' }
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
        3. ЛОГИКА (УНИВЕРСАЛЬНАЯ)
        ===================================================== --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dataTable', () => ({
                config: window.TABLE_CONFIG,

                items: [],
                datatable: null,
                dataArr: [],

                async init() {
                    await this.loadData();
                    this.buildTable();
                },

                async loadData() {
                    const response = await fetch(this.config.api.list, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer ' + this.config.auth.token
                        },
                        body: JSON.stringify({
                            page: 1,
                            perpage: 0
                        })
                    });

                    const json = await response.json();

                    console.log('API advertisers response:', json);

                    // ✅ твой реальный формат
                    if (json && Array.isArray(json.data)) {
                        this.items = json.data;
                    } else {
                        console.error('Unexpected API format', json);
                        this.items = [];
                    }

                    this.setTableData();
                },


                setTableData() {
                    this.dataArr = this.items.map(item =>
                        this.config.columns
                            .map(col => item[col.key] ?? '—')
                            .concat(item[this.config.primaryKey])
                    );
                },

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

                format(col, value) {
                    switch (col.formatter) {

                        case 'badge':
                            return `<span class="badge ${col.options[value] ?? 'badge-secondary'}">
                                        ${value}
                                    </span>`;

                        case 'date':
                            return value
                                ? new Date(value).toLocaleString()
                                : '—';

                        case 'truncate':
                            if (!value) return '—';
                            const len = col.options?.length ?? 30;
                            const short = value.length > len
                                ? value.substring(0, len) + '…'
                                : value;

                            return `<span title="${value}">${short}</span>`;

                        case 'number':
                            return `<span class="font-semibold">${value}</span>`;

                        default:
                            return value;
                    }
                },

                renderActions(id) {
                    return this.config.actions.map(a =>
                        `<a href="${a.url}?id=${id}"
                            class="text-primary mr-3">
                            ${a.type}
                        </a>`
                    ).join('');
                }
            }));
        });
    </script>

</x-layout.default>
