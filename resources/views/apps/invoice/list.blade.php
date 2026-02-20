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
           ✅ Header UI (gear + filter)
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

        #mainTabulator .dt-colmenu-btn,
        #mainTabulator .dt-filter-btn{
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
        }
        .dark #mainTabulator .dt-colmenu-btn,
        .dark #mainTabulator .dt-filter-btn{
            background: rgba(255,255,255,0.08);
        }

        #mainTabulator .dt-filter-btn.dt-active{
            background: rgba(0, 123, 255, 0.14);
        }
        .dark #mainTabulator .dt-filter-btn.dt-active{
            background: rgba(0, 123, 255, 0.22);
        }

        /* =====================================================
           ✅ Column chooser + filter menu (pure DOM, appended to body)
           ===================================================== */
        .dt-menu{
            position: fixed;
            z-index: 2147483647;
            min-width: 280px;
            max-width: 360px;
            max-height: 420px;
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
            padding: 6px 8px;
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

        .dt-menu-form{
            display: grid;
            gap: 8px;
        }
        .dt-menu-row{
            display: grid;
            grid-template-columns: 1fr;
            gap: 6px;
        }
        .dt-menu-row-2{
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        .dt-menu-label{
            font-size: 12px;
            opacity: .75;
        }
        .dt-menu-actions{
            display: flex;
            gap: 8px;
            justify-content: flex-end;
            padding-top: 6px;
        }

        .dt-menu input,
        .dt-menu select{
            width: 100%;
            border: 1px solid rgba(0,0,0,0.12);
            border-radius: 10px;
            padding: 8px 10px;
            outline: none;
            background: transparent;
        }
        .dark .dt-menu input,
        .dark .dt-menu select{
            border: 1px solid rgba(255,255,255,0.14);
        }

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

                // ✅ DOM menus
                colMenuEl: null,
                colMenuOpen: false,
                colMenuDocHandler: null,

                filterMenuEl: null,
                filterMenuOpen: false,
                filterMenuDocHandler: null,
                activeFilterField: null,

                // фильтры (UI state)
                filters: {},

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

                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'Escape') {
                            this.closeColMenu();
                            this.closeFilterMenu();
                        }
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

                // =========================================================
                // ✅ API FILTER MAPPING (NEW FORMAT)
                // payload.filter = [{field, op, val}]
                // =========================================================
                buildApiFilterArray() {
                    const arr = [];

                    Object.entries(this.filters || {}).forEach(([field, f]) => {
                        if (!f || !f.kind) return;

                        // TEXT
                        if (f.kind === 'text') {
                            const v = String(f.value ?? '').trim();
                            if (!v) return;

                            // map UI op -> API op
                            // contains (case-insensitive by default) => icontain
                            // eq => =
                            // starts => ilike
                            // ends => icontain (нет ends в API; делаем contain как максимально близко)
                            let op = String(f.op || 'icontain');

                            // позволяем хранить сразу API op
                            const allowed = ['=','>','<','>=','<=','!=','like','ilike','contain','icontain','~','in'];
                            if (!allowed.includes(op)) {
                                // legacy UI values:
                                if (op === 'contains') op = 'icontain';
                                else if (op === 'eq') op = '=';
                                else if (op === 'starts') op = 'ilike';
                                else if (op === 'ends') op = 'icontain';
                                else op = 'icontain';
                            }

                            arr.push({ field, op, val: v });
                            return;
                        }

                        // NUMBER (between as 2 conditions)
                        if (f.kind === 'number') {
                            const hasMin = !(f.min === '' || f.min === null || f.min === undefined);
                            const hasMax = !(f.max === '' || f.max === null || f.max === undefined);

                            if (!hasMin && !hasMax) return;

                            if (hasMin) arr.push({ field, op: '>=', val: Number(f.min) });
                            if (hasMax) arr.push({ field, op: '<=', val: Number(f.max) });
                            return;
                        }

                        // DATE (between as 2 conditions)
                        if (f.kind === 'date') {
                            const from = String(f.from ?? '').trim();
                            const to = String(f.to ?? '').trim();
                            if (!from && !to) return;

                            if (from) arr.push({ field, op: '>=', val: from });
                            if (to)   arr.push({ field, op: '<=', val: to });
                            return;
                        }

                        // BOOL (= 0/1)
                        if (f.kind === 'bool') {
                            if (f.value === '' || f.value === null || f.value === undefined) return;
                            const v = (String(f.value) === '1') ? 1 : 0;
                            arr.push({ field, op: '=', val: v });
                            return;
                        }

                        // SELECT (=) or IN (array)
                        if (f.kind === 'select') {
                            const v = f.value;

                            if (Array.isArray(v)) {
                                if (v.length === 0) return;
                                arr.push({ field, op: 'in', val: v });
                                return;
                            }

                            if (v === '' || v === null || v === undefined) return;
                            arr.push({ field, op: '=', val: v });
                            return;
                        }

                        // JSON (~) (если вдруг будешь использовать)
                        if (f.kind === 'json') {
                            const v = String(f.value ?? '').trim();
                            if (!v) return;
                            arr.push({ field, op: '~', val: v });
                            return;
                        }
                    });

                    return arr;
                },

                hasActiveFilter(field) {
                    const f = this.filters?.[field];
                    if (!f) return false;

                    if (f.kind === 'text') return String(f.value ?? '').trim().length > 0;
                    if (f.kind === 'number') return (f.min !== '' && f.min !== null && f.min !== undefined) || (f.max !== '' && f.max !== null && f.max !== undefined);
                    if (f.kind === 'date') return String(f.from ?? '').trim() || String(f.to ?? '').trim();
                    if (f.kind === 'bool') return !(f.value === '' || f.value === null || f.value === undefined);
                    if (f.kind === 'select') return Array.isArray(f.value) ? f.value.length > 0 : !(f.value === '' || f.value === null || f.value === undefined);

                    return false;
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
                            filter: this.buildApiFilterArray(), // ✅ FIX HERE
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

                // =====================================================
                // ✅ Column chooser menu (gear)
                // =====================================================
                ensureColMenu() {
                    if (this.colMenuEl) return;

                    const el = document.createElement('div');
                    el.className = 'dt-menu';
                    el.style.display = 'none';

                    el.addEventListener('mousedown', (e) => e.stopPropagation());
                    el.addEventListener('click', (e) => e.stopPropagation());

                    document.body.appendChild(el);
                    this.colMenuEl = el;
                },

                renderColMenu() {
                    if (!this.tabulator || !this.colMenuEl) return;

                    const cols = this.tabulator.getColumns()
                        .filter(c => (c.getField && c.getField()) && c.getField() !== '__actions');

                    let html = `<div class="dt-menu-title">Колонки</div>`;

                    cols.forEach((c) => {
                        const def = c.getDefinition();
                        const field = c.getField();
                        const title = def.title ?? field;
                        const checked = c.isVisible();

                        html += `
                            <div class="dt-menu-item" data-field="${String(field)}">
                                <input type="checkbox" ${checked ? 'checked' : ''} />
                                <div class="text-sm">${this.escapeHtml(title)}</div>
                            </div>
                        `;
                    });

                    this.colMenuEl.innerHTML = html;

                    this.colMenuEl.querySelectorAll('.dt-menu-item').forEach((rowEl) => {
                        rowEl.addEventListener('click', () => {
                            const field = rowEl.getAttribute('data-field');
                            if (!field) return;

                            const col = this.tabulator.getColumn(field);
                            if (!col) return;

                            if (col.isVisible()) col.hide();
                            else col.show();

                            const cb = rowEl.querySelector('input[type="checkbox"]');
                            if (cb) cb.checked = col.isVisible();
                        });

                        const cb = rowEl.querySelector('input[type="checkbox"]');
                        if (cb) {
                            cb.addEventListener('click', (e) => {
                                e.stopPropagation();
                                rowEl.click();
                            });
                        }
                    });
                },

                openColMenuAt(x, y) {
                    this.ensureColMenu();
                    this.renderColMenu();

                    const w = 280;
                    const pad = 8;
                    const left = Math.max(pad, Math.min((Number(x) || pad), window.innerWidth - w - pad));
                    const top  = Math.max(pad, (Number(y) || pad));

                    this.colMenuEl.style.left = left + 'px';
                    this.colMenuEl.style.top  = top + 'px';
                    this.colMenuEl.style.display = 'block';
                    this.colMenuOpen = true;

                    if (this.colMenuDocHandler) {
                        document.removeEventListener('mousedown', this.colMenuDocHandler, true);
                    }

                    setTimeout(() => {
                        this.colMenuDocHandler = (e) => {
                            if (!this.colMenuOpen) return;
                            const inMenu = this.colMenuEl && this.colMenuEl.contains(e.target);
                            const onBtn = e.target.closest && e.target.closest('.dt-colmenu-btn');
                            if (!inMenu && !onBtn) this.closeColMenu();
                        };
                        document.addEventListener('mousedown', this.colMenuDocHandler, true);
                    }, 0);
                },

                closeColMenu() {
                    this.colMenuOpen = false;
                    if (this.colMenuEl) this.colMenuEl.style.display = 'none';
                    if (this.colMenuDocHandler) {
                        document.removeEventListener('mousedown', this.colMenuDocHandler, true);
                        this.colMenuDocHandler = null;
                    }
                },

                // =====================================================
                // ✅ Filter menu
                // =====================================================
                ensureFilterMenu() {
                    if (this.filterMenuEl) return;

                    const el = document.createElement('div');
                    el.className = 'dt-menu';
                    el.style.display = 'none';

                    el.addEventListener('mousedown', (e) => e.stopPropagation());
                    el.addEventListener('click', (e) => e.stopPropagation());

                    document.body.appendChild(el);
                    this.filterMenuEl = el;
                },

                inferFilterKind(col) {
                    if (col.is_lookup) return 'select';

                    const control = String(col.control || '').toLowerCase();

                    if (['checkbox','switch','bool','boolean'].includes(control)) return 'bool';
                    if (['number','int','integer','float','double','decimal'].includes(control)) return 'number';
                    if (['date','datetime','datetime_local','datetimelocal','time'].includes(control)) return 'date';
                    if (['select','dropdown','radio'].includes(control)) return 'select';

                    return 'text';
                },

                getSelectOptionsFor(col) {
                    const field = col.key;

                    if (col.is_lookup) {
                        const list = this.lookups?.[field] || [];
                        const idKey = col.lookup_id;
                        const nameKey = col.lookup_name;

                        return list.map(item => ({
                            value: item?.[idKey],
                            label: item?.[nameKey] ?? String(item?.[idKey] ?? '')
                        })).filter(o => o.value !== undefined && o.value !== null);
                    }

                    const opt = col.options || col.fieldConfig?.formatter_options || null;
                    if (opt?.items && Array.isArray(opt.items)) {
                        return opt.items.map(i => ({ value: i.value, label: i.label ?? i.name ?? String(i.value) }));
                    }
                    if (opt?.options && typeof opt.options === 'object') {
                        return Object.entries(opt.options).map(([v, l]) => ({ value: v, label: String(l) }));
                    }

                    return [];
                },

                renderFilterMenu(field) {
                    if (!this.filterMenuEl) return;

                    const col = this.config.columns.find(c => c.key === field);
                    if (!col) return;

                    const kind = this.inferFilterKind(col);

                    if (!this.filters[field] || this.filters[field].kind !== kind) {
                        // ✅ операции под твой API
                        if (kind === 'text')   this.filters[field] = { kind, op: 'icontain', value: '' };
                        if (kind === 'number') this.filters[field] = { kind, min: '', max: '' };
                        if (kind === 'date')   this.filters[field] = { kind, from: '', to: '' };
                        if (kind === 'bool')   this.filters[field] = { kind, value: '' };
                        if (kind === 'select') this.filters[field] = { kind, value: '' };
                    }

                    const state = this.filters[field];
                    const title = col.title ?? field;

                    let body = `<div class="dt-menu-title">Фильтр: ${this.escapeHtml(title)}</div>`;
                    body += `<div class="dt-menu-form">`;

                    if (kind === 'text') {
                        body += `
                            <div class="dt-menu-row">
                                <div class="dt-menu-label">Операция</div>
                                <select data-k="op">
                                    <option value="like" ${state.op==='like'?'selected':''}>Начинается (с учётом регистра)</option>
                                    <option value="ilike" ${state.op==='ilike'?'selected':''}>Начинается (без учёта регистра)</option>
                                    <option value="contain" ${state.op==='contain'?'selected':''}>Содержит (с учётом регистра)</option>
                                    <option value="icontain" ${state.op==='icontain'?'selected':''}>Содержит (без учёта регистра)</option>
                                    <option value="=" ${state.op==='='?'selected':''}>Точное совпадение</option>
                                    <option value="!=" ${state.op==='!='?'selected':''}>Не равно</option>
                                </select>
                            </div>
                            <div class="dt-menu-row">
                                <div class="dt-menu-label">Значение</div>
                                <input data-k="value" type="text" value="${this.escapeAttr(state.value ?? '')}" placeholder="Введите текст">
                            </div>
                        `;
                    }

                    if (kind === 'number') {
                        body += `
                            <div class="dt-menu-row-2">
                                <div class="dt-menu-row">
                                    <div class="dt-menu-label">От (>=)</div>
                                    <input data-k="min" type="number" value="${this.escapeAttr(state.min ?? '')}" placeholder="min">
                                </div>
                                <div class="dt-menu-row">
                                    <div class="dt-menu-label">До (<=)</div>
                                    <input data-k="max" type="number" value="${this.escapeAttr(state.max ?? '')}" placeholder="max">
                                </div>
                            </div>
                        `;
                    }

                    if (kind === 'date') {
                        body += `
                            <div class="dt-menu-row-2">
                                <div class="dt-menu-row">
                                    <div class="dt-menu-label">С (>=)</div>
                                    <input data-k="from" type="date" value="${this.escapeAttr(state.from ?? '')}">
                                </div>
                                <div class="dt-menu-row">
                                    <div class="dt-menu-label">По (<=)</div>
                                    <input data-k="to" type="date" value="${this.escapeAttr(state.to ?? '')}">
                                </div>
                            </div>
                        `;
                    }

                    if (kind === 'bool') {
                        body += `
                            <div class="dt-menu-row">
                                <div class="dt-menu-label">Значение</div>
                                <select data-k="value">
                                    <option value="" ${state.value===''?'selected':''}>Любое</option>
                                    <option value="1" ${String(state.value)==='1'?'selected':''}>Да</option>
                                    <option value="0" ${String(state.value)==='0'?'selected':''}>Нет</option>
                                </select>
                            </div>
                        `;
                    }

                    if (kind === 'select') {
                        const opts = this.getSelectOptionsFor(col);
                        let optionsHtml = `<option value="">Любое</option>`;
                        opts.forEach(o => {
                            const sel = String(o.value) === String(state.value) ? 'selected' : '';
                            optionsHtml += `<option value="${this.escapeAttr(o.value)}" ${sel}>${this.escapeHtml(o.label)}</option>`;
                        });

                        body += `
                            <div class="dt-menu-row">
                                <div class="dt-menu-label">Значение (=)</div>
                                <select data-k="value">
                                    ${optionsHtml}
                                </select>
                            </div>
                        `;
                    }

                    body += `
                        <div class="dt-menu-actions">
                            <button class="btnx" type="button" data-action="clear">Сбросить</button>
                            <button class="btnx primary" type="button" data-action="apply">Применить</button>
                        </div>
                    `;
                    body += `</div>`;

                    this.filterMenuEl.innerHTML = body;

                    // bind inputs -> state
                    const el = this.filterMenuEl;

                    el.querySelectorAll('[data-k]').forEach(inp => {
                        const k = inp.getAttribute('data-k');
                        if (!k) return;

                        const handler = () => { this.filters[field][k] = inp.value; };

                        inp.addEventListener('input', handler);
                        inp.addEventListener('change', handler);
                    });

                    const btnApply = el.querySelector('[data-action="apply"]');
                    const btnClear = el.querySelector('[data-action="clear"]');

                    if (btnApply) {
                        btnApply.addEventListener('click', async () => {
                            this.closeFilterMenu();
                            await this.loadData(1);
                        });
                    }

                    if (btnClear) {
                        btnClear.addEventListener('click', async () => {
                            delete this.filters[field];
                            this.closeFilterMenu();
                            await this.loadData(1);
                        });
                    }
                },

                openFilterMenuAt(field, x, y) {
                    this.ensureFilterMenu();
                    this.activeFilterField = field;

                    this.renderFilterMenu(field);

                    const w = 280;
                    const pad = 8;
                    const left = Math.max(pad, Math.min((Number(x) || pad), window.innerWidth - w - pad));
                    const top  = Math.max(pad, (Number(y) || pad));

                    this.filterMenuEl.style.left = left + 'px';
                    this.filterMenuEl.style.top  = top + 'px';
                    this.filterMenuEl.style.display = 'block';
                    this.filterMenuOpen = true;

                    if (this.filterMenuDocHandler) {
                        document.removeEventListener('mousedown', this.filterMenuDocHandler, true);
                    }

                    setTimeout(() => {
                        this.filterMenuDocHandler = (e) => {
                            if (!this.filterMenuOpen) return;

                            const inMenu = this.filterMenuEl && this.filterMenuEl.contains(e.target);
                            const onBtn = e.target.closest && e.target.closest('.dt-filter-btn');
                            if (!inMenu && !onBtn) this.closeFilterMenu();
                        };
                        document.addEventListener('mousedown', this.filterMenuDocHandler, true);
                    }, 0);
                },

                closeFilterMenu() {
                    this.filterMenuOpen = false;
                    this.activeFilterField = null;

                    if (this.filterMenuEl) this.filterMenuEl.style.display = 'none';
                    if (this.filterMenuDocHandler) {
                        document.removeEventListener('mousedown', this.filterMenuDocHandler, true);
                        this.filterMenuDocHandler = null;
                    }
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

                    const cols = this.config.columns.map((col) => {
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

                                    this.closeColMenu();

                                    const field = fb.getAttribute('data-field');
                                    const r = fb.getBoundingClientRect();

                                    if (this.filterMenuOpen && this.activeFilterField === field) {
                                        this.closeFilterMenu();
                                    } else {
                                        this.openFilterMenuAt(
                                            field,
                                            Math.round(r.right - 8 - 280),
                                            Math.round(r.bottom + 8)
                                        );
                                    }
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

                    // ✅ Actions column with gear (NO filter)
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
                                <div class="dt-col-header">
                                    <span class="dt-col-title">Действия</span>
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

                            this.closeFilterMenu();

                            const r = btn.getBoundingClientRect();
                            const x = Math.round(r.right - 8 - 280);
                            const y = Math.round(r.bottom + 8);

                            if (this.colMenuOpen) this.closeColMenu();
                            else this.openColMenuAt(x, y);
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

                    this.tabulator.on("tableClick", () => {
                        this.closeColMenu();
                        this.closeFilterMenu();
                    });

                    this.tabulator.on("headerClick", (e) => {
                        if (!e.target.closest('.dt-colmenu-btn') && !e.target.closest('.dt-filter-btn')) {
                            this.closeColMenu();
                            this.closeFilterMenu();
                        }
                    });

                    this.syncTabulatorSortIndicator();
                    this.syncFilterIcons();
                },

                // ============================
                // (остальной код без изменений — col menu + delete)
                // ============================
                // ✅ Column chooser menu (gear) — implementation
                ensureColMenu() {
                    if (this.colMenuEl) return;

                    const el = document.createElement('div');
                    el.className = 'dt-menu';
                    el.style.display = 'none';

                    el.addEventListener('mousedown', (e) => e.stopPropagation());
                    el.addEventListener('click', (e) => e.stopPropagation());

                    document.body.appendChild(el);
                    this.colMenuEl = el;
                },

                renderColMenu() {
                    if (!this.tabulator || !this.colMenuEl) return;

                    const cols = this.tabulator.getColumns()
                        .filter(c => (c.getField && c.getField()) && c.getField() !== '__actions');

                    let html = `<div class="dt-menu-title">Колонки</div>`;

                    cols.forEach((c) => {
                        const def = c.getDefinition();
                        const field = c.getField();
                        const title = def.title ?? field;
                        const checked = c.isVisible();

                        html += `
                            <div class="dt-menu-item" data-field="${String(field)}">
                                <input type="checkbox" ${checked ? 'checked' : ''} />
                                <div class="text-sm">${this.escapeHtml(title)}</div>
                            </div>
                        `;
                    });

                    this.colMenuEl.innerHTML = html;

                    this.colMenuEl.querySelectorAll('.dt-menu-item').forEach((rowEl) => {
                        rowEl.addEventListener('click', () => {
                            const field = rowEl.getAttribute('data-field');
                            if (!field) return;

                            const col = this.tabulator.getColumn(field);
                            if (!col) return;

                            if (col.isVisible()) col.hide();
                            else col.show();

                            const cb = rowEl.querySelector('input[type="checkbox"]');
                            if (cb) cb.checked = col.isVisible();
                        });

                        const cb = rowEl.querySelector('input[type="checkbox"]');
                        if (cb) {
                            cb.addEventListener('click', (e) => {
                                e.stopPropagation();
                                rowEl.click();
                            });
                        }
                    });
                },

                openColMenuAt(x, y) {
                    this.ensureColMenu();
                    this.renderColMenu();

                    const w = 280;
                    const pad = 8;
                    const left = Math.max(pad, Math.min((Number(x) || pad), window.innerWidth - w - pad));
                    const top  = Math.max(pad, (Number(y) || pad));

                    this.colMenuEl.style.left = left + 'px';
                    this.colMenuEl.style.top  = top + 'px';
                    this.colMenuEl.style.display = 'block';
                    this.colMenuOpen = true;

                    if (this.colMenuDocHandler) {
                        document.removeEventListener('mousedown', this.colMenuDocHandler, true);
                    }

                    setTimeout(() => {
                        this.colMenuDocHandler = (e) => {
                            if (!this.colMenuOpen) return;
                            const inMenu = this.colMenuEl && this.colMenuEl.contains(e.target);
                            const onBtn = e.target.closest && e.target.closest('.dt-colmenu-btn');
                            if (!inMenu && !onBtn) this.closeColMenu();
                        };
                        document.addEventListener('mousedown', this.colMenuDocHandler, true);
                    }, 0);
                },

                closeColMenu() {
                    this.colMenuOpen = false;
                    if (this.colMenuEl) this.colMenuEl.style.display = 'none';
                    if (this.colMenuDocHandler) {
                        document.removeEventListener('mousedown', this.colMenuDocHandler, true);
                        this.colMenuDocHandler = null;
                    }
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
