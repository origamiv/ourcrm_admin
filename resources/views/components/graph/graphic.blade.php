{{-- resources/views/components/graph/graphic.blade.php --}}

@php
    $title      = $title ?? 'Revenue';
    $label      = $label ?? 'Total Profit';
    $xx         = $xx ?? 0;

    $periods    = $periods ?? ['Weekly', 'Monthly', 'Yearly'];
    $chartId    = $chartId ?? ('chart_' . uniqid());

    $apiUrl     = $apiUrl ?? null;
    $apiPayload = $apiPayload ?? null;

    $series     = $series ?? [];
    $categories = $categories ?? [];

    // имя ключа токена в localStorage (по умолчанию как часто делают)
    // если у тебя другой ключ — передай его при include: 'tokenKey' => '...'
    $tokenKey   = $tokenKey ?? 'token';
@endphp

<div
    class="panel h-full xl:col-span-1"
    x-data="graphWidget({
        chartId: @js($chartId),
        periods: @js($periods),

        apiUrl: @js($apiUrl),
        apiPayload: @js($apiPayload),

        tokenKey: @js($tokenKey),

        series: @js($series),
        categories: @js($categories),
    })"
    x-init="init()"
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
                @foreach($periods as $p)
                    <li>
                        <a href="javascript:;" @click="toggle; $root.reload(@js($p))">{{ $p }}</a>
                    </li>
                @endforeach
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
        // dropdown: один раз
        if (!Alpine._x_graph_dropdown_defined) {
            Alpine._x_graph_dropdown_defined = true;
            Alpine.data('dropdown', () => ({
                open: false,
                toggle() { this.open = !this.open; },
            }));
        }

        // graphWidget: один раз
        if (!window.graphWidget) {
            window.graphWidget = ({ chartId, periods, apiUrl, apiPayload, tokenKey, series, categories }) => ({
                chartId,
                periods,
                apiUrl,
                apiPayload,
                tokenKey,

                series: series || [],
                categories: categories || [],

                period: (periods && periods.length) ? periods[0] : 'Monthly',

                chart: null,
                errorText: '',

                ensureApexLoaded(tries = 0) {
                    if (window.ApexCharts) return true;
                    if (tries >= 80) return false;
                    setTimeout(() => this.init(tries + 1), 100);
                    return false;
                },

                getBearerToken() {
                    try {
                        const raw = localStorage.getItem(this.tokenKey || 'token');
                        if (!raw) return null;

                        // поддержка формата: "Bearer xxx" или просто "xxx"
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

                    // 1) если есть axios — используем его
                    if (window.axios) {
                        const resp = await window.axios.post(url, payload || {}, { headers });
                        return resp.data;
                    }

                    // 2) иначе — fetch (полностью рабочая замена axios)
                    const resp = await fetch(url, {
                        method: 'POST',
                        headers,
                        body: JSON.stringify(payload || {}),
                    });

                    // если сервер вернул не-2xx
                    if (!resp.ok) {
                        let text = '';
                        try { text = await resp.text(); } catch(e) {}
                        throw new Error(`HTTP ${resp.status}: ${text || resp.statusText}`);
                    }

                    return await resp.json();
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
                        this.categories = data.categories || [];
                        this.series = data.series || [];

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

                    const xCats = (this.categories || []).map(d =>
                        (typeof d === 'string' && d.length === 10) ? `${d}T00:00:00` : d
                    );

                    const options = {
                        chart: { type: 'line', height: 325, toolbar: { show: false } },
                        series: this.series,
                        xaxis: { type: 'datetime', categories: xCats },
                        stroke: { curve: 'smooth', width: 2 },
                        dataLabels: { enabled: false },
                        tooltip: { x: { format: 'dd MMM yyyy' } },
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

                // reload по клику на пункт меню (пока просто перезагружает те же данные)
                async reload(p) {
                    this.period = p;

                    // Если API начнёт поддерживать период — добавишь, например:
                    // this.apiPayload = { ...(this.apiPayload || {}), period: p };

                    await this.fetchData();

                    if (this.chart) {
                        const xCats = (this.categories || []).map(d =>
                            (typeof d === 'string' && d.length === 10) ? `${d}T00:00:00` : d
                        );
                        this.chart.updateOptions({ xaxis: { categories: xCats } });
                        this.chart.updateSeries(this.series || []);
                    } else {
                        this.renderChart();
                    }
                },
            });
        }
    });
</script>
