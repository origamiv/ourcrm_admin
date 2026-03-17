<x-layout.default>

    {{-- =====================================================
        SCRIPTS (подключаем сверху)
    ===================================================== --}}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tabulator-tables@6.3.0/dist/js/tabulator.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/luxon@3.4.4/build/global/luxon.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ru.js"></script>
    {{-- field components --}}
    <script src="/assets/js/components/text.js"></script>

    {{-- menus/components --}}
    <script src="/assets/js/components/tabulatorFilterMenu.js"></script>
    <script src="/assets/js/components/tabulatorColumnsMenu.js"></script>
    <script src="/assets/js/components/deleteModal.js"></script>

    {{-- =====================================================
        1. CONFIG
    ===================================================== --}}
    <script>
        window.CONFIG = @json($config);

        window.resolveUrl = function(path) {
            if (!path || /^https?:\/\//.test(path)) return path;
            return '{{ config('app.api_url') }}' + path;
        };

        window.TABLE_CONFIG = {
            api: {
                list:   resolveUrl(window.CONFIG.common.api) + '/list',
                delete: resolveUrl(window.CONFIG.common.api)
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
           ✅ Header UI
           ===================================================== */
        #mainTabulator .dt-col-header{
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            width: 100%;
        }
        #mainTabulator .dt-col-header .dt-col-title{
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        #mainTabulator .dt-filter-btn,
        #mainTabulator .dt-colmenu-btn,
        #mainTabulator .dt-clearfilters-btn{
            width: 26px;
            height: 26px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            background: rgba(0,0,0,0.04);
            border: 0;
            padding: 0;
            flex: 0 0 auto;
        }
        .dark #mainTabulator .dt-filter-btn,
        .dark #mainTabulator .dt-colmenu-btn,
        .dark #mainTabulator .dt-clearfilters-btn{
            background: rgba(255,255,255,0.08);
        }

        #mainTabulator .dt-filter-btn.dt-active{
            background: rgba(0, 123, 255, 0.14);
        }
        .dark #mainTabulator .dt-filter-btn.dt-active{
            background: rgba(0, 123, 255, 0.22);
        }

        #mainTabulator .dt-actions-header-btns{
            display: inline-flex;
            align-items: center;
            gap: 6px;
            flex: 0 0 auto;
        }

        #mainTabulator .dt-clearfilters-btn[disabled]{
            opacity: .45;
            cursor: default;
        }

        /* =====================================================
           ✅ Menus (custom, NO <input> checkboxes)
           ===================================================== */
        .dt-menu{
            position: fixed;
            z-index: 2147483647;
            min-width: 320px;
            max-width: 460px;
            max-height: 520px;
            overflow: auto;
            border-radius: 12px;
            padding: 10px;
            background: #ffffff;
            border: 1px solid rgba(0,0,0,0.08);
            box-shadow: 0 16px 40px rgba(0,0,0,0.15);
        }
        .dark .dt-menu{
            background: #0e1726;
            border: 1px solid rgba(255,255,255,0.12);
            box-shadow: 0 16px 40px rgba(0,0,0,0.45);
        }

        .dt-menu-title{
            font-weight: 600;
            font-size: 13px;
            margin-bottom: 8px;
            color: rgba(0,0,0,0.75);
        }
        .dark .dt-menu-title{
            color: rgba(255,255,255,0.8);
        }

        .dt-menu-item{
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            border-radius: 10px;
            cursor: pointer;
            user-select: none;
        }
        .dt-menu-item:hover{
            background: rgba(0,0,0,0.05);
        }
        .dark .dt-menu-item:hover{
            background: rgba(255,255,255,0.08);
        }
        .dt-menu-mark{
            width: 18px;
            height: 18px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(0,0,0,0.06);
            font-weight: 800;
            font-size: 12px;
        }
        .dark .dt-menu-mark{
            background: rgba(255,255,255,0.10);
        }

        .dt-menu-form{ display: grid; gap: 8px; }
        .dt-menu-row{ display: grid; grid-template-columns: 1fr; gap: 6px; }
        .dt-menu-row-2{ display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
        .dt-menu-label{ font-size: 12px; opacity: .75; }
        .dt-menu-actions{ display: flex; gap: 8px; justify-content: flex-end; padding-top: 6px; flex-wrap: wrap; }

        .dt-menu input,
        .dt-menu select,
        .dt-menu textarea{
            width: 100%;
            border: 1px solid rgba(0,0,0,0.12);
            border-radius: 10px;
            padding: 8px 10px;
            outline: none;
            background: transparent;
        }
        .dark .dt-menu input,
        .dark .dt-menu select,
        .dark .dt-menu textarea{
            border: 1px solid rgba(255,255,255,0.14);
        }
        .dt-menu textarea{ min-height: 78px; resize: vertical; }

        .dt-menu .btnx{
            border-radius: 10px;
            padding: 8px 10px;
            border: 1px solid rgba(0,0,0,0.12);
            background: transparent;
            cursor: pointer;
        }
        .dark .dt-menu .btnx{
            border: 1px solid rgba(255,255,255,0.14);
        }
        .dt-menu .btnx.primary{
            border-color: rgba(0, 123, 255, 0.45);
        }
    </style>

    {{-- =====================================================
        2. UI
    ===================================================== --}}
    <div x-data="dataTable" x-init="init()">

        {{-- Кнопка добавления новой записи --}}
        <div class="mb-3 flex justify-end">
            <a :href="`/${CONFIG.common.shortname}/create`" class="btn btn-success gap-1">
                <i class="uil uil-plus"></i>Добавить запись
            </a>
        </div>

        {{-- Mobile card layout (shown on screens < md) --}}
        <x-crud.mobile-cards />

        <div class="hidden md:block panel px-0 border-[#e0e6ed] dark:border-[#1b2e4b]">
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
                                @click="goToPage(page - 1)">←</button>

                        <template x-for="p in pagesToShow" :key="p">
                            <button class="btn"
                                    :class="p === page ? 'btn-primary' : 'btn-outline-secondary'"
                                    :disabled="isLoading"
                                    @click="goToPage(p)"
                                    x-text="p"></button>
                        </template>

                        <button class="btn btn-outline-secondary"
                                :disabled="page >= totalPages || isLoading"
                                @click="goToPage(page + 1)">→</button>
                    </div>
                </div>
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
                tabulator: null,

                lookups: {},
                lookupMaps: {},

                page: 1,
                perpage: window.TABLE_CONFIG.perPage ?? 20,
                count: 0,
                isLoading: false,

                sortBy: null,
                sortDir: 'desc',

                // ✅ components
                filterMenu: null,
                columnsMenu: null,
                deleteModal: null,

                // ✅ mobile card config (auto-detected from CONFIG)
                cardConfig: null,

                // filters ui state: {field: {kind, ...}}
                filters: {},

                get usePagination() { return (this.perpage ?? 0) !== -1; },

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

                get hasAnyFilters() {
                    return Object.keys(this.filters || {}).some(f => this.hasActiveFilter(f));
                },

                async init() {
                    this.buildColumnsFromConfig();
                    this.buildCardConfig();
                    this.initSortFromConfig();

                    this.initComponents();

                    await this.loadLookups();

                    await this.loadData(1);
                    this.buildTable();

                    window.addEventListener('datatable-delete', e => {
                        const id = e.detail;
                        this.deleteModal?.open(id);
                    });
                },

                initComponents() {
                    // filter menu
                    if (window.DTFilterMenu) {
                        this.filterMenu = new window.DTFilterMenu({
                            escapeHtml: (s) => this.escapeHtml(s),
                            escapeAttr: (s) => this.escapeAttr(s),

                            getCol: (field) => this.config.columns.find(c => c.key === field) || null,
                            inferKind: (col) => this.inferFilterKindByDbType(col),

                            getState: (field) => this.filters?.[field] ?? null,
                            setState: (field, state) => { this.filters[field] = state; },
                            deleteState: (field) => { delete this.filters[field]; },

                            onApply: async () => {
                                await this.loadData(1);
                                this.syncFilterIcons();
                            },

                            onClearField: async () => {
                                await this.loadData(1);
                                this.syncFilterIcons();
                            },
                        });
                    }

                    // columns menu
                    if (window.DTColumnsMenu) {
                        this.columnsMenu = new window.DTColumnsMenu({
                            escapeHtml: (s) => this.escapeHtml(s),
                            escapeAttr: (s) => this.escapeAttr(s),
                            getTabulator: () => this.tabulator
                        });
                    }

                    // delete modal
                    if (window.DeleteModal) {
                        this.deleteModal = new window.DeleteModal({
                            onConfirm: async (id) => {
                                await this.doDelete(id);
                            }
                        });
                    }

                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'Escape') {
                            this.columnsMenu?.close?.();
                            this.filterMenu?.close?.();
                            this.deleteModal?.close?.();
                        }
                    });
                },

                closeMenus() {
                    this.columnsMenu?.close?.();
                    this.filterMenu?.close?.();
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
                    const groups = window.CONFIG.column_groups || [];

                    const flatColumns = Object.entries(fields)
                        .filter(([_, field]) => !field.field_mode || field.field_mode.includes('index'))
                        .map(([key, field]) => ({
                            key,
                            title: field.name,
                            control: field.control ?? 'text',
                            db_type: String(field.db_type ?? '').toLowerCase(),
                            fieldConfig: field,
                            formatter: field.formatter ?? null,
                            options: field.formatter_options ?? null,
                            is_lookup: field.is_lookup ?? false,
                            lookup_id: field.lookup_id,
                            lookup_name: field.lookup_name,
                            column_group: field.column_group ?? null
                        }));

                    this.config.columns = flatColumns;
                },

                // =====================================================
                // Mobile card config (auto-detected from window.CONFIG)
                // =====================================================
                buildCardConfig() {
                    const entries = Object.entries(window.CONFIG.fields)
                        .filter(([, f]) => !f.field_mode || f.field_mode.includes('index'));

                    // Primary display field: prefer common name keys, otherwise first field
                    const NAME_KEYS = ['name', 'title', 'subject', 'fio', 'fullname', 'caption'];
                    const nameEntry = entries.find(([k]) => NAME_KEYS.includes(k)) ?? entries[0];

                    // Status field: bool type or named status/state/active
                    const STATUS_KEYS = ['status', 'state', 'active', 'enabled', 'is_active'];
                    const statusEntry = entries.find(([k, f]) =>
                        STATUS_KEYS.includes(k) ||
                        String(f.db_type ?? '').toLowerCase().includes('bool')
                    );

                    // Additional header fields: up to 2, excluding name, status, id
                    const excluded = new Set([nameEntry?.[0], statusEntry?.[0], 'id'].filter(Boolean));
                    const headerFields = entries
                        .filter(([k]) => !excluded.has(k))
                        .slice(0, 2)
                        .map(([k]) => k);

                    // All fields for the card body (excluding id)
                    const allFields = entries
                        .filter(([k]) => k !== 'id')
                        .map(([k, f]) => ({ key: k, label: f.name ?? k }));

                    this.cardConfig = {
                        nameField: nameEntry?.[0] ?? 'id',
                        statusField: statusEntry?.[0] ?? null,
                        headerFields,
                        allFields,
                    };
                },

                // Render status as a coloured icon
                statusIcon(val) {
                    const v = String(val ?? '').toLowerCase();
                    if (val === true || val === 1 || v === '1' || v === 'active' || v === 'yes' || v === 'true')
                        return '<span class="text-success font-bold text-base leading-none">✓</span>';
                    if (val === false || val === 0 || v === '0' || v === 'inactive' || v === 'no' || v === 'false')
                        return '<span class="text-danger font-bold text-base leading-none">✗</span>';
                    if (v === '' || val === null || val === undefined)
                        return '<span class="text-gray-400 font-bold text-base leading-none">·</span>';
                    return '<span class="text-warning font-bold text-base leading-none">⚠</span>';
                },

                // Format a single cell value for card display (datetime + lookup aware)
                formatCardValue(row, key) {
                    const value = row[key];
                    if (value === null || value === undefined || value === '') return null;

                    // Boolean / checkbox
                    const fieldConf = window.CONFIG.fields[key];
                    if (fieldConf?.control === 'checkbox' || String(fieldConf?.db_type ?? '').toLowerCase().includes('bool')) {
                        return (value === true || value === 1 || value === '1') ? '✓' : '✗';
                    }

                    // Lookup field
                    if (this.lookupMaps[key] !== undefined) {
                        return this.lookupMaps[key][value] ?? String(value);
                    }

                    // Datetime formatting with Luxon
                    const col = this.config.columns.find(c => c.key === key);
                    if (col?.formatter === 'datetime' && value && window.luxon) {
                        try {
                            const outputFormat = col.options?.outputFormat || 'DD.MM.YY HH:mm';
                            const luxonFormat = outputFormat
                                .replace('DD', 'dd').replace('MM', 'MM')
                                .replace('YYYY', 'yyyy').replace('YY', 'yy');
                            const dt = luxon.DateTime.fromISO(value);
                            if (dt.isValid) return dt.toFormat(luxonFormat);
                            const dt2 = luxon.DateTime.fromSQL(value);
                            if (dt2.isValid) return dt2.toFormat(luxonFormat);
                        } catch (e) { /* fall through */ }
                    }

                    return String(value);
                },

                async loadLookups() {
                    const token = localStorage.getItem('access_token');
                    if (!token) return;

                    const lookupFields = Object.entries(window.CONFIG.fields)
                        .filter(([_, f]) => f.is_lookup);

                    for (const [key, field] of lookupFields) {
                        try {
                            const res = await axios.post(
                                `${resolveUrl(field.lookup_api)}/list`,
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
                                this.lookupMaps[key][item[field.lookup_id ?? 'id']] = item[field.lookup_name];
                            });
                        } catch (e) {
                            console.warn(`loadLookups: не удалось загрузить справочник "${key}"`, e);
                            this.lookups[key] = [];
                            this.lookupMaps[key] = {};
                        }
                    }
                },

                // =========================================================
                // ✅ API FILTER ARRAY: filter: [{field, op, val}]
                // =========================================================
                buildApiFilterArray() {
                    const arr = [];
                    const push = (field, op, val) => arr.push({ field, op, val });

                    Object.entries(this.filters || {}).forEach(([field, f]) => {
                        if (!f || !f.kind) return;

                        if (f.kind === 'text') {
                            const v = String(f.value ?? '').trim();
                            if (!v) return;
                            push(field, String(f.op || 'icontain'), v);
                            return;
                        }

                        if (f.kind === 'bool') {
                            if (f.value === '' || f.value === null || f.value === undefined) return;

                            if (String(f.value) === 'null') push(field, '=', null);
                            else if (String(f.value) === '1') push(field, '=', 1);
                            else if (String(f.value) === '0') push(field, '=', 0);
                            return;
                        }

                        if (f.kind === 'datetime') {
                            const op = String(f.op || '');
                            const v = String(f.value ?? '').trim();
                            const from = String(f.from ?? '').trim();
                            const to = String(f.to ?? '').trim();

                            if (op && v) push(field, op, v);
                            if (from) push(field, '>=', from);
                            if (to)   push(field, '<=', to);
                            return;
                        }

                        if (f.kind === 'int') {
                            const mode = String(f.mode || 'cmp');

                            if (mode === 'cmp') {
                                const op = String(f.op || '=');
                                const vRaw = (f.value ?? '');
                                if (vRaw === '' || vRaw === null || vRaw === undefined) return;
                                const v = Number(vRaw);
                                if (Number.isNaN(v)) return;
                                push(field, op, v);
                                return;
                            }

                            const raw = String(f.list ?? '').trim();
                            if (!raw) return;

                            const parts = raw
                                .split(/[\s,;]+/g)
                                .map(s => s.trim())
                                .filter(Boolean);

                            const nums = parts.map(x => Number(x)).filter(n => !Number.isNaN(n));
                            if (nums.length === 0) return;

                            if (mode === 'in') push(field, 'in', nums);
                            if (mode === 'not_in') push(field, 'not in', nums);
                            return;
                        }
                    });

                    return arr;
                },

                inferFilterKindByDbType(col) {
                    const t = String(col.db_type || '').toLowerCase();

                    if (t.includes('bool')) return 'bool';
                    if (t.includes('timestamp') || t.includes('datetime') || t === 'date' || t === 'time') return 'datetime';
                    if (t.includes('int') || t.includes('bigint') || t.includes('smallint')) return 'int';

                    return 'text';
                },

                hasActiveFilter(field) {
                    const f = this.filters?.[field];
                    if (!f) return false;

                    if (f.kind === 'text') return String(f.value ?? '').trim().length > 0;

                    if (f.kind === 'bool') {
                        return !(f.value === '' || f.value === null || f.value === undefined);
                    }

                    if (f.kind === 'datetime') {
                        return (
                            (String(f.value ?? '').trim().length > 0 && String(f.op ?? '').trim().length > 0) ||
                            String(f.from ?? '').trim().length > 0 ||
                            String(f.to ?? '').trim().length > 0
                        );
                    }

                    if (f.kind === 'int') {
                        const mode = String(f.mode || 'cmp');
                        if (mode === 'cmp') return !(f.value === '' || f.value === null || f.value === undefined);
                        if (mode === 'in' || mode === 'not_in') return String(f.list ?? '').trim().length > 0;
                    }

                    return false;
                },

                async resetAllFilters() {
                    this.filters = {};
                    this.filterMenu?.close?.();
                    await this.loadData(1);
                    this.syncFilterIcons();
                    this.tabulator?.redraw(true);
                },

                async loadData(page = 1) {
                    const token = localStorage.getItem('access_token');
                    if (!token) return;

                    this.isLoading = true;

                    try {
                        const payload = {
                            page: Math.max(1, Number(page) || 1),
                            perpage: Number(this.perpage),
                            order: [{ field: this.sortBy, direction: (this.sortDir || 'desc') }],
                            filter: this.buildApiFilterArray(),
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
                            this.syncFilterIcons();
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

                syncFilterIcons() {
                    const holder = document.querySelector('#mainTabulator');
                    if (!holder) return;

                    holder.querySelectorAll('.dt-filter-btn').forEach(btn => {
                        const field = btn.getAttribute('data-field');
                        if (!field) return;

                        if (this.hasActiveFilter(field)) btn.classList.add('dt-active');
                        else btn.classList.remove('dt-active');
                    });

                    const resetBtn = holder.querySelector('.dt-clearfilters-btn');
                    if (resetBtn) {
                        if (this.hasAnyFilters) resetBtn.removeAttribute('disabled');
                        else resetBtn.setAttribute('disabled', 'disabled');
                    }
                },

                // =====================================================
                // Tabulator build
                // =====================================================
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

                    const buildColDef = (col) => {
                        return {
                            title: col.title,
                            field: col.key,
                            headerSort: false,
                            cssClass: "dt-server-sortable",

                            titleFormatter: () => {
                                const active = this.hasActiveFilter(col.key) ? 'dt-active' : '';
                                return `
                                    <div class="dt-col-header">
                                        <span class="dt-col-title">${this.escapeHtml(col.title)}</span>
                                        <button class="dt-filter-btn ${active}" type="button" title="Фильтр" data-field="${this.escapeAttr(col.key)}">
                                            <i class="uil uil-filter"></i>
                                        </button>
                                    </div>
                                `;
                            },

                            headerClick: async (e) => {
                                const fb = e.target.closest && e.target.closest('.dt-filter-btn');
                                if (fb) {
                                    e.preventDefault();
                                    e.stopPropagation();

                                    this.columnsMenu?.close?.();
                                    this.filterMenu?.toggleNear?.(fb);
                                    return;
                                }

                                await this.onHeaderSort(col.key);
                            },

                            formatter: (cell) => {
                                const value = cell.getValue();
                                const row = cell.getRow()?.getData?.() ?? null;

                                if (col.is_lookup) {
                                    return this.lookupMaps[col.key]?.[value] ?? '—';
                                }

                                // ✅ Форматирование даты/времени через Luxon, если задан formatter=datetime
                                if (col.formatter === 'datetime' && value) {
                                    try {
                                        const outputFormat = col.options?.outputFormat || 'DD.MM.YY HH:mm';
                                        // Tabulator/Luxon format mapping: DD.MM.YY -> dd.MM.yy, HH:mm -> HH:mm
                                        const luxonFormat = outputFormat
                                            .replace('DD', 'dd')
                                            .replace('MM', 'MM')
                                            .replace('YY', 'yy')
                                            .replace('YYYY', 'yyyy');

                                        const dt = luxon.DateTime.fromISO(value);
                                        if (dt.isValid) {
                                            return dt.toFormat(luxonFormat);
                                        }

                                        // Если не ISO, пробуем как SQL date/time
                                        const dt2 = luxon.DateTime.fromSQL(value);
                                        if (dt2.isValid) {
                                            return dt2.toFormat(luxonFormat);
                                        }
                                    } catch (e) {
                                        console.error('Date format error', e);
                                    }
                                }

                                // Boolean / checkbox
                                if (col.control === 'checkbox' || col.db_type.includes('bool')) {
                                    const checked = value === true || value === 1 || value === '1';
                                    return `<input type="checkbox" disabled ${checked ? 'checked' : ''} style="width:16px;height:16px;cursor:default;pointer-events:none;margin:auto;display:block">`;
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
                    };

                    const columnGroups = window.CONFIG.column_groups || [];
                    let cols = [];

                    if (columnGroups.length > 0) {
                        // Группируем колонки
                        const groupMap = {};
                        columnGroups.forEach(g => {
                            groupMap[g.id] = {
                                title: g.name,
                                columns: []
                            };
                        });

                        this.config.columns.forEach(col => {
                            if (col.column_group && groupMap[col.column_group]) {
                                groupMap[col.column_group].columns.push(buildColDef(col));
                            } else {
                                cols.push(buildColDef(col));
                            }
                        });

                        // Добавляем группы в общий список колонок (в конец или по порядку?)
                        // Для простоты добавим их туда, где была первая колонка группы, или просто в конец.
                        // Но лучше сохранить порядок из конфига.

                        // Пересоберем cols с учетом групп
                        cols = [];
                        const processedGroups = new Set();

                        this.config.columns.forEach(col => {
                            if (col.column_group && groupMap[col.column_group]) {
                                if (!processedGroups.has(col.column_group)) {
                                    cols.push(groupMap[col.column_group]);
                                    processedGroups.add(col.column_group);
                                }
                            } else {
                                cols.push(buildColDef(col));
                            }
                        });
                    } else {
                        cols = this.config.columns.map(buildColDef);
                    }

                    cols.push({
                        title: 'Действия',
                        field: '__actions',
                        headerSort: false,
                        frozen: true,
                        width: 170,
                        minWidth: 170,
                        maxWidth: 170,
                        widthGrow: 0,
                        widthShrink: 0,
                        hozAlign: 'right',
                        headerHozAlign: 'right',

                        titleFormatter: () => {
                            const disabled = this.hasAnyFilters ? '' : 'disabled';
                            return `
                                <div class="dt-col-header">
                                    <span class="dt-col-title">Действия</span>
                                    <span class="dt-actions-header-btns">
                                        <button class="dt-clearfilters-btn text-danger" type="button" title="Сбросить все фильтры" ${disabled}>
                                            <i class="uil uil-filter-slash"></i>
                                        </button>
                                        <button class="dt-colmenu-btn text-primary" type="button" title="Колонки">
                                            <i class="uil uil-setting"></i>
                                        </button>
                                    </span>
                                </div>
                            `;
                        },

                        headerClick: async (e) => {
                            const clearBtn = e.target.closest && e.target.closest('.dt-clearfilters-btn');
                            if (clearBtn) {
                                e.preventDefault();
                                e.stopPropagation();
                                if (!this.hasAnyFilters) return;
                                await this.resetAllFilters();
                                return;
                            }

                            const gearBtn = e.target.closest && e.target.closest('.dt-colmenu-btn');
                            if (gearBtn) {
                                e.preventDefault();
                                e.stopPropagation();

                                this.filterMenu?.close?.();
                                this.columnsMenu?.toggleNear?.(gearBtn);
                                return;
                            }
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

                    const groupConfig = window.CONFIG.group ?? null;

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
                        groupBy: groupConfig ? groupConfig.field : false,
                        groupHeader: (value, count, data, group) => {
                            // Если есть форматтер для этого поля, используем его
                            const col = this.config.columns.find(c => c.key === groupConfig.field);
                            let displayValue = value;

                            if (col && col.formatter === 'datetime' && value) {
                                try {
                                    const outputFormat = col.options?.outputFormat || 'DD.MM.YY';
                                    const luxonFormat = outputFormat
                                        .replace('DD', 'dd')
                                        .replace('MM', 'MM')
                                        .replace('YY', 'yy')
                                        .replace('YYYY', 'yyyy');

                                    const dt = luxon.DateTime.fromISO(value);
                                    if (dt.isValid) {
                                        displayValue = dt.toFormat(luxonFormat);
                                    } else {
                                        const dt2 = luxon.DateTime.fromSQL(value);
                                        if (dt2.isValid) {
                                            displayValue = dt2.toFormat(luxonFormat);
                                        }
                                    }
                                } catch (e) {
                                    console.error('Group header date format error', e);
                                }
                            }

                            return `<span>${displayValue}</span> <span style='color:#d00; margin-left:10px;'>(${count} записей)</span>`;
                        },
                    });

                    // ✅ закрытие по клику "в пустоту"
                    this.tabulator.on("tableClick", () => {
                        this.closeMenus();
                    });
                    this.tabulator.on("headerClick", (e) => {
                        if (!e.target.closest('.dt-colmenu-btn') && !e.target.closest('.dt-filter-btn') && !e.target.closest('.dt-clearfilters-btn')) {
                            this.closeMenus();
                        }
                    });

                    this.syncTabulatorSortIndicator();
                    this.syncFilterIcons();
                },

                syncTabulatorSortIndicator() {
                    if (!this.tabulator) return;

                    const holder = document.querySelector('#mainTabulator');
                    if (!holder) return;

                    const headers = holder.querySelectorAll('.tabulator-col');
                    headers.forEach((h) => {
                        const field = h.getAttribute('tabulator-field');
                        const titleSpan = h.querySelector('.dt-col-title');
                        if (!titleSpan) return;

                        let baseTitle = null;
                        if (field === '__actions') baseTitle = 'Действия';
                        else {
                            const cfg = this.config.columns.find(c => c.key === field);
                            if (cfg?.title) baseTitle = cfg.title;
                        }
                        if (!baseTitle) return;

                        if (field && field === this.sortBy) {
                            titleSpan.textContent = `${baseTitle} ${this.sortDir === 'asc' ? '▲' : '▼'}`;
                        } else {
                            titleSpan.textContent = baseTitle;
                        }
                    });
                },

                escapeHtml(str) {
                    return String(str)
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#039;');
                },
                escapeAttr(str) {
                    return this.escapeHtml(str).replace(/`/g, '&#096;');
                },

                // ============================
                // 🗑 Delete
                // ============================
                async doDelete(id) {
                    const token = localStorage.getItem('access_token');
                    if (!token) return;

                    await fetch(`${this.config.api.delete}/${id}/destroy`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${token}`
                        }
                    });

                    const nextPage = (this.page > 1 && this.items.length === 1) ? (this.page - 1) : this.page;

                    this.deleteModal?.close?.();
                    await this.loadData(nextPage);
                },

            }));
        });
    </script>

</x-layout.default>
