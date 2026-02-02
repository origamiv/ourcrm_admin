{{-- resources/views/components/graph/graphic.blade.php --}}

@php
    // ===== defaults =====
    $title      = $title ?? 'Revenue';
    $label      = $label ?? 'Total Profit';
    $xx         = $xx ?? 0;

    $periods    = $periods ?? ['Weekly', 'Monthly', 'Yearly'];

    // chart meta
    $chartId    = $chartId ?? ('chart_' . uniqid());

    // chart data (fallback if datasets not used)
    $series     = $series ?? [];
    $categories = $categories ?? [];

    // datasets per period
    $datasets = $datasets ?? [];

    // default selected period
    $defaultPeriod = $defaultPeriod ?? null;
@endphp

<div
    class="panel h-full xl:col-span-1"
    x-data="graphWidget({
        chartId: @js($chartId),
        series: @js($series),
        categories: @js($categories),
        periods: @js($periods),
        datasets: @js($datasets),
        defaultPeriod: @js($defaultPeriod),
    })"
    x-init="init()"
    @graph-period="setPeriod($event.detail.period)"
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
                        <a href="javascript:;"
                           @click="toggle; $dispatch('graph-period', { period: @js($p) })">
                            {{ $p }}
                        </a>
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
        // dropdown: объявляем один раз
        if (!Alpine._x_graph_dropdown_defined) {
            Alpine._x_graph_dropdown_defined = true;
            Alpine.data('dropdown', () => ({
                open: false,
                toggle() { this.open = !this.open; },
            }));
        }

        // graphWidget: объявляем один раз
        if (!window.graphWidget) {
            window.graphWidget = ({ chartId, series, categories, periods, datasets, defaultPeriod }) => ({
                chartId,
                series,
                categories,
                periods,
                datasets,
                defaultPeriod,

                period: (defaultPeriod && (periods || []).includes(defaultPeriod))
                    ? defaultPeriod
                    : ((periods && periods.length) ? periods[0] : 'Monthly'),

                chart: null,

                applyDataset(period) {
                    const ds = (this.datasets && this.datasets[period]) ? this.datasets[period] : null;
                    if (!ds) return false;

                    this.categories = ds.categories || [];
                    this.series = ds.series || [];
                    return true;
                },

                ensureApexLoaded(tries = 0) {
                    if (window.ApexCharts) return true;
                    if (tries >= 50) return false; // ~5 сек (50 * 100ms)
                    setTimeout(() => this.init(tries + 1), 100);
                    return false;
                },

                init(tries = 0) {
                    const el = this.$refs.chart;
                    if (!el) return;

                    // если ApexCharts ещё не загрузился — ждём
                    if (!window.ApexCharts) {
                        this.ensureApexLoaded(tries);
                        return;
                    }

                    // применяем датасет выбранного периода (если есть)
                    this.applyDataset(this.period);

                    // clear loader
                    el.innerHTML = '';

                    // если данных нет — покажем аккуратный плейсхолдер
                    if (!this.series || this.series.length === 0) {
                        el.innerHTML = `
                        <div class="min-h-[325px] grid place-content-center text-black/60 dark:text-white/60">
                            No data for <b>${this.chartId}</b>
                        </div>
                    `;
                        return;
                    }

                    const options = {
                        chart: { type: 'line', height: 325, toolbar: { show: false } },
                        series: this.series,
                        xaxis: { type: 'datetime', categories: this.categories || [] },
                        stroke: { curve: 'smooth', width: 2 },
                        dataLabels: { enabled: false },
                        tooltip: { x: { format: 'dd MMM HH:mm' } },
                    };

                    // если график уже был — уничтожим перед пересозданием
                    if (this.chart) {
                        try { this.chart.destroy(); } catch(e) {}
                        this.chart = null;
                    }

                    this.chart = new ApexCharts(el, options);
                    this.chart.render();
                },

                setPeriod(p) {
                    this.period = p;

                    // обновляем данные из datasets
                    this.applyDataset(p);

                    if (!this.chart) {
                        // если вдруг график ещё не создан — создадим
                        this.init();
                        return;
                    }

                    this.chart.updateOptions({ xaxis: { categories: this.categories || [] } });
                    this.chart.updateSeries(this.series || []);
                },
            });
        }
    });
</script>
