{{-- resources/views/components/graph/graphic.blade.php --}}

@php
    $title      = $title ?? 'Revenue';
    $label      = $label ?? 'Total Profit';
    $xx         = $xx ?? 0;

    $chartId    = $chartId ?? ('chart_' . uniqid());

    // API
    $apiUrl     = $apiUrl ?? null;
    $apiPayload = $apiPayload ?? null;

    // localStorage token key
    $tokenKey   = $tokenKey ?? 'access_token';

    // UI
    $periodLabels  = $periodLabels ?? null;     // ['hourly'=>'Hourly','daily'=>'Daily','weekly'=>'Weekly']
    $defaultPeriod = $defaultPeriod ?? null;    // 'daily'
@endphp

<div
    class="panel h-full xl:col-span-1"
    x-data="graphWidget({
        chartId: @js($chartId),

        apiUrl: @js($apiUrl),
        apiPayload: @js($apiPayload),
        tokenKey: @js($tokenKey),

        periodLabels: @js($periodLabels),
        defaultPeriod: @js($defaultPeriod),
    })"
    x-init="init()"
    @graph-period.window="setPeriod($event.detail.period)"
>
    <div class="flex items-center dark:text-white-light mb-5">
        <h5 class="font-semibold text-lg">{{ $title }}</h5>

        <div x-data="dropdown" @click.outside="open = false" class="dropdown ltr:ml-auto rtl:mr-auto">
            <a href="javascript:;" @click="toggle">
                <svg class="w-5 h-5 text-black/70 dark:text-white/70 hover:!text-primary"
                     viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="5" cy="12" r="2" stroke="currentColor" stroke-width="1.5"/>
                    <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor" stroke-width="1.5"/>
                    <circle cx="19" cy="12" r="2" stroke="currentColor" stroke-width="1.5"/>
                </svg>
            </a>

            <ul x-cloak x-show="open" x-transition x-transition.duration.300ms class="ltr:right-0 rtl:left-0">
                <template x-for="p in periods" :key="p">
                    <li>
                        <a href="javascript:;"
                           @click="toggle; $dispatch('graph-period', { period: p })">
                            <span x-text="labelForPeriod(p)"></span>
                        </a>
                    </li>
                </template>
            </ul>
        </div>
    </div>

    <p class="text-lg dark:text-white-light/90">
        {{ $label }}
        <span class="text-primary ml-2">${{ $xx }}</span>
    </p>

    <div class="relative overflow-hidden">
        <div x-ref="chart" class="bg-white dark:bg-black rounded-lg">
            <!-- loader -->
            <div class="min-h-[325px] grid place-content-center bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08]">
                <span class="animate-spin border-2 border-black dark:border-white !border-l-transparent rounded-full w-5 h-5 inline-flex"></span>
            </div>
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

        // graphWidget — один раз
        if (!window.graphWidget) {
            window.graphWidget = ({ chartId, apiUrl, apiPayload, tokenKey, periodLabels, defaultPeriod }) => ({
                chartId,
                apiUrl,
                apiPayload,
                tokenKey,
                periodLabels,
                defaultPeriod,

                // from API
                periods: [],
                datasets: {},

                // current dataset
                period: null,
                categories: [],
                series: [],

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

                // тип оси X в зависимости от периода
                xAxisTypeForPeriod(periodKey) {
                    // weekly приходит "12.01-18.01" => это НЕ datetime
                    if (periodKey === 'weekly') return 'category';
                    return 'datetime'; // hourly, daily
                },

                tooltipFormatForPeriod(periodKey) {
                    if (periodKey === 'hourly') return 'dd MMM HH:mm';
                    if (periodKey === 'daily') return 'dd MMM yyyy';
                    // weekly как категория — tooltip format для x не нужен
                    return undefined;
                },

                normalizeCategories(periodKey, cats) {
                    // weekly: оставляем как есть
                    if (periodKey === 'weekly') return (cats || []).map(v => String(v));

                    // hourly: "YYYY-MM-DD HH:mm" -> "YYYY-MM-DDTHH:mm:00"
                    // daily:  "YYYY-MM-DD" -> "YYYY-MM-DDT00:00:00"
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

                renderChart() {
                    const el = this.$refs.chart;
                    if (!el) return;

                    if (this.errorText) {
                        el.innerHTML = `
                        <div class="min-h-[325px] grid place-content-center text-red-500/90 px-4 text-center">
                            ${this.errorText}
                        </div>
                    `;
                        return;
                    }

                    el.innerHTML = '';

                    if (!this.series || this.series.length === 0) {
                        el.innerHTML = `
                        <div class="min-h-[325px] grid place-content-center text-black/60 dark:text-white/60">
                            No data for <b>${this.chartId}</b>
                        </div>
                    `;
                        return;
                    }

                    const xType = this.xAxisTypeForPeriod(this.period);
                    const xCats = this.normalizeCategories(this.period, this.categories);

                    const tooltipFmt = this.tooltipFormatForPeriod(this.period);

                    const options = {
                        chart: { type: 'line', height: 325, toolbar: { show: false } },
                        series: this.series,
                        xaxis: {
                            type: xType,
                            categories: xCats,
                        },
                        stroke: { curve: 'smooth', width: 2 },
                        dataLabels: { enabled: false },
                        tooltip: tooltipFmt ? { x: { format: tooltipFmt } } : {},
                    };

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

                    // для смены datetime<->category гарантированно пересоздаём
                    this.renderChart();
                },
            });
        }
    });
</script>
