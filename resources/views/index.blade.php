<x-layout.default>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="/assets/js/apexcharts.js"></script>
    <div x-data="sales">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Sales</span>
            </li>
        </ul>

        <div class="pt-5">
            <div class="grid xl:grid-cols-3 gap-6 mb-6">
                @include('components.graph.graphic', [
                'title' => 'Clicks',
                'label' => 'Total clicks',
                'chartId' => 'clicks_api',

                // важно: в Blade конкатенация через "." ок, просто следи, чтобы не было двойных слэшей
                'apiUrl' => rtrim(config('app.api_url'), '/') . '/api/v1/facts/list',

                // важно: ключ называется periods (или mode — если у тебя реально так на бэке).
                // По твоему новому ответу сервер возвращает data.periods, поэтому логично отправлять periods.
                'apiPayload' => [
                'mode' => ['15min','hourly', 'daily', 'weekly', 'monthly'],
                'perpage' => -1,
                'filter' => [
                ['field' => 'shortname', 'op' => '=', 'val' => 'click'],
                ],
                ],

                // токен из localStorage
                'tokenKey' => 'access_token',

                // подписи в дропдауне (необязательно, но удобно)
                'periodLabels' => [
                'hourly' => 'Hourly',
                'daily'  => 'Daily',
                ],

                // стартовый период
                'defaultPeriod' => 'daily',
                ])

                @include('components.graph.column', [
                'title' => 'Leads',
                'label' => 'Total leads',
                'chartId' => 'leads',

                // важно: в Blade конкатенация через "." ок, просто следи, чтобы не было двойных слэшей
                'apiUrl' => rtrim(config('app.api_url'), '/') . '/api/v1/facts/list',

                // важно: ключ называется periods (или mode — если у тебя реально так на бэке).
                // По твоему новому ответу сервер возвращает data.periods, поэтому логично отправлять periods.
                'apiPayload' => [
                    'mode' => ['15min','hourly', 'daily', 'weekly', 'monthly'],
                    'perpage' => -1,
                    'filter' => [
                        ['field' => 'shortname', 'op' => '=', 'val' => 'lead'],
                    ],
                ],

                // токен из localStorage
                'tokenKey' => 'access_token',

                // подписи в дропдауне (необязательно, но удобно)
                'periodLabels' => [
                    'hourly' => 'Hourly',
                    'daily'  => 'Daily',
                ],

                // стартовый период
                'defaultPeriod' => 'daily',
                ])




            </div>

        </div>
    </div>
    <script>
        document.addEventListener('alpine:init', () => {

            // dropdown — один раз
            if (!Alpine._x_graph_dropdown_defined) {
                Alpine._x_graph_dropdown_defined = true;
                Alpine.data('dropdown', () => ({
                    open: false,
                    toggle() { this.open = !this.open; },
                }));
            }

            if (!window.graphWidget) {
                window.graphWidget = (cfg) => {
                    // ✅ нормализация типа: принимаем 'column'|'bar'|'line'
                    const rawType = (cfg?.chartType || 'line').toString().toLowerCase();
                    const isColumn = (rawType === 'column' || rawType === 'bar');

                    return {
                        ...cfg,

                        // ✅ единый флаг (без сравнений строк в разных местах)
                        isColumn,

                        // from API
                        periods: [],
                        datasets: {},

                        // current dataset
                        period: null,
                        categories: [],
                        series: [],
                        total: (cfg?.initialTotal ?? 0),

                        chart: null,
                        errorText: '',

                        labelForPeriod(p) {
                            if (this.periodLabels && this.periodLabels[p]) return this.periodLabels[p];
                            const s = (p || '').toString();
                            return s ? (s.charAt(0).toUpperCase() + s.slice(1)) : '';
                        },

                        ensureApexLoaded(tries = 0) {
                            if (window.ApexCharts) return true;
                            if (tries >= 80) return false;
                            setTimeout(() => this.init(tries + 1), 100);
                            return false;
                        },

                        getBearerToken() {
                            try {
                                const raw = localStorage.getItem(this.tokenKey || 'access_token');
                                if (!raw) return null;
                                return raw.startsWith('Bearer ') ? raw.slice(7) : raw;
                            } catch (e) {
                                return null;
                            }
                        },

                        buildHeaders() {
                            const headers = { 'Content-Type': 'application/json' };
                            const token = this.getBearerToken();
                            if (token) headers['Authorization'] = `Bearer ${token}`;
                            return headers;
                        },

                        async requestJson(url, payload) {
                            const headers = this.buildHeaders();

                            if (window.axios) {
                                const resp = await window.axios.post(url, payload || {}, { headers });
                                return resp.data;
                            }

                            const resp = await fetch(url, {
                                method: 'POST',
                                headers,
                                body: JSON.stringify(payload || {}),
                            });

                            if (!resp.ok) {
                                let text = '';
                                try { text = await resp.text(); } catch(e) {}
                                throw new Error(`HTTP ${resp.status}: ${text || resp.statusText}`);
                            }

                            return await resp.json();
                        },

                        // тип оси X
                        xAxisTypeForPeriod(periodKey) {
                            // ✅ для column-chart всегда category
                            if (this.isColumn) return 'category';

                            // ✅ line-chart: weekly не datetime
                            if (periodKey === 'weekly') return 'category';
                            return 'datetime';
                        },

                        tooltipFormatForPeriod(periodKey) {
                            if (this.isColumn) return undefined;
                            if (periodKey === 'hourly') return 'dd MMM HH:mm';
                            if (periodKey === 'daily') return 'dd MMM yyyy';
                            return undefined;
                        },

                        normalizeCategories(periodKey, cats) {
                            // ✅ column-chart: всегда строки
                            if (this.isColumn) return (cats || []).map(v => String(v));

                            // line-chart
                            if (periodKey === 'weekly') return (cats || []).map(v => String(v));

                            return (cats || []).map(v => {
                                const s = String(v);

                                if (periodKey === 'hourly') {
                                    return s.includes(' ') ? (s.replace(' ', 'T') + ':00') : s;
                                }
                                if (periodKey === 'daily' && s.length === 10) {
                                    return s + 'T00:00:00';
                                }
                                return s;
                            });
                        },

                        applyDataset(periodKey) {
                            const ds = (this.datasets && this.datasets[periodKey]) ? this.datasets[periodKey] : null;
                            if (!ds) return false;

                            this.period = periodKey;
                            this.categories = ds.categories || [];
                            this.series = ds.series || [];

                            // total из ds.total
                            if (ds.total !== undefined && ds.total !== null) {
                                this.total = ds.total;
                            } else {
                                const arr = (this.series?.[0]?.data && Array.isArray(this.series[0].data)) ? this.series[0].data : [];
                                this.total = arr.reduce((a, b) => a + (Number(b) || 0), 0);
                            }

                            return true;
                        },

                        async fetchData() {
                            if (!this.apiUrl) return;

                            try {
                                this.errorText = '';

                                const payload = await this.requestJson(this.apiUrl, this.apiPayload);

                                if (!payload || payload.success !== true) {
                                    this.errorText = payload?.message || 'API error';
                                    return;
                                }

                                const data = payload.data || {};
                                this.periods = Array.isArray(data.periods) ? data.periods : [];
                                this.datasets = (data.datasets && typeof data.datasets === 'object') ? data.datasets : {};

                                const first = this.periods[0] || null;
                                const wanted = this.defaultPeriod && this.periods.includes(this.defaultPeriod)
                                    ? this.defaultPeriod
                                    : first;

                                if (wanted) this.applyDataset(wanted);

                            } catch (e) {
                                this.errorText = e?.message || 'Request failed';
                            }
                        },

                        buildOptions() {
                            const xType = this.xAxisTypeForPeriod(this.period);
                            const xCats = this.normalizeCategories(this.period, this.categories);
                            const tooltipFmt = this.tooltipFormatForPeriod(this.period);

                            // ✅ column chart как в демо basic column:
                            // chart.type = 'bar', plotOptions.bar.horizontal=false
                            if (this.isColumn) {
                                return {
                                    series: this.series,
                                    chart: {
                                        type: 'bar',
                                        height: 325,
                                        toolbar: { show: false },
                                    },
                                    plotOptions: {
                                        bar: {
                                            horizontal: false,
                                            columnWidth: '55%',
                                            endingShape: 'rounded',
                                        }
                                    },
                                    dataLabels: { enabled: false },
                                    stroke: { show: true, width: 2, colors: ['transparent'] },
                                    xaxis: { type: 'category', categories: xCats },
                                    yaxis: { title: { text: '' } },
                                    fill: { opacity: 1 },
                                    tooltip: { y: { formatter: (val) => val } },
                                };
                            }

                            // ✅ line chart
                            return {
                                chart: { type: 'line', height: 325, toolbar: { show: false } },
                                series: this.series,
                                xaxis: { type: xType, categories: xCats },
                                stroke: { curve: 'smooth', width: 2 },
                                dataLabels: { enabled: false },
                                tooltip: tooltipFmt ? { x: { format: tooltipFmt } } : {},
                            };
                        },

                        renderChart() {
                            const el = this.$refs.chart;
                            if (!el) return;

                            if (this.errorText) {
                                el.innerHTML = `
              <div class="min-h-[325px] grid place-content-center text-red-500/90 px-4 text-center">
                ${this.errorText}
              </div>`;
                                return;
                            }

                            el.innerHTML = '';

                            if (!this.series || this.series.length === 0) {
                                el.innerHTML = `
              <div class="min-h-[325px] grid place-content-center text-black/60 dark:text-white/60">
                No data for <b>${this.chartId}</b>
              </div>`;
                                return;
                            }

                            const options = this.buildOptions();

                            if (this.chart) {
                                try { this.chart.destroy(); } catch(e) {}
                                this.chart = null;
                            }

                            this.chart = new ApexCharts(el, options);
                            this.chart.render();
                        },

                        async init(tries = 0) {
                            const el = this.$refs.chart;
                            if (!el) return;

                            if (!window.ApexCharts) {
                                this.ensureApexLoaded(tries);
                                return;
                            }

                            await this.fetchData();
                            this.renderChart();
                        },

                        async setPeriod(p) {
                            if (!this.applyDataset(p)) return;
                            this.renderChart();
                        },
                    };
                };
            }
        });
    </script>




</x-layout.default>
