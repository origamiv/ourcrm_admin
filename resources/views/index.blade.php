<x-layout.default>
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
  'xx' => 2299,

  'periods' => ['Hourly', 'Daily', 'Weekly'],
  'defaultPeriod' => 'Daily',

  'chartId' => 'clicks_mix',

  // наборы данных по периодам
  'datasets' => [
    'Hourly' => [
      'categories' => [
        '2026-02-01T15:00:00+02:00','2026-02-01T16:00:00+02:00','2026-02-01T17:00:00+02:00',
        '2026-02-01T18:00:00+02:00','2026-02-01T19:00:00+02:00','2026-02-01T20:00:00+02:00',
        '2026-02-01T21:00:00+02:00','2026-02-01T22:00:00+02:00','2026-02-01T23:00:00+02:00',
        '2026-02-02T00:00:00+02:00','2026-02-02T01:00:00+02:00','2026-02-02T02:00:00+02:00',
      ],
      'series' => [
        ['name' => 'Clicks', 'data' => [12,18,9,21,16,14,17,15,18,19,20,15]],
      ],
    ],

    'Daily' => [
      'categories' => [
        '2026-01-20T00:00:00+02:00','2026-01-21T00:00:00+02:00','2026-01-22T00:00:00+02:00',
        '2026-01-23T00:00:00+02:00','2026-01-24T00:00:00+02:00','2026-01-25T00:00:00+02:00',
        '2026-01-26T00:00:00+02:00','2026-01-27T00:00:00+02:00','2026-01-28T00:00:00+02:00',
        '2026-01-29T00:00:00+02:00','2026-01-30T00:00:00+02:00','2026-01-31T00:00:00+02:00',
        '2026-02-01T00:00:00+02:00','2026-02-02T00:00:00+02:00',
      ],
      'series' => [
        ['name' => 'Clicks', 'data' => [120,134,98,160,155,142,170,165,180,175,190,210,205,195]],
      ],
    ],

    'Weekly' => [
      'categories' => [
        '2025-12-15T00:00:00+02:00','2025-12-22T00:00:00+02:00','2025-12-29T00:00:00+02:00',
        '2026-01-05T00:00:00+02:00','2026-01-12T00:00:00+02:00','2026-01-19T00:00:00+02:00',
        '2026-01-26T00:00:00+02:00','2026-02-02T00:00:00+02:00',
      ],
      'series' => [
        ['name' => 'Clicks', 'data' => [620, 710, 680, 740, 800, 760, 820, 790]],
      ],
    ],
  ],
])


                @include('components.graph.pie')


            </div>

        </div>
    </div>
    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("sales", () => {
                // чтобы options видели актуальные значения
                let isDark = false;
                let isRtl = false;

                return {
                    salesByCategory: null,

                    init() {
                        isDark = this.$store.app.theme === "dark" || this.$store.app.isDarkMode ? true : false;
                        isRtl = this.$store.app.rtlClass === "rtl" ? true : false;

                        // Инициализируем ТОЛЬКО pie, потому что на странице реально есть только x-ref="salesByCategory"
                        setTimeout(() => {
                            if (!window.ApexCharts) return;                 // apex не загрузился
                            if (!this.$refs.salesByCategory) return;        // ref отсутствует

                            // очистим loader
                            this.$refs.salesByCategory.innerHTML = "";

                            // options гарантированно объект
                            const opts = this.salesByCategoryOptions;
                            this.salesByCategory = new ApexCharts(this.$refs.salesByCategory, opts);
                            this.salesByCategory.render();
                        }, 300);

                        this.$watch('$store.app.theme', () => {
                            isDark = this.$store.app.theme === "dark" || this.$store.app.isDarkMode ? true : false;
                            if (this.salesByCategory) this.salesByCategory.updateOptions(this.salesByCategoryOptions);
                        });

                        this.$watch('$store.app.rtlClass', () => {
                            isRtl = this.$store.app.rtlClass === "rtl" ? true : false;
                            // donut обычно не зависит от rtl, но оставим на всякий
                            if (this.salesByCategory) this.salesByCategory.updateOptions(this.salesByCategoryOptions);
                        });
                    },

                    // pie options — у тебя почти готово, просто возвращаем объект
                    get salesByCategoryOptions() {
                        return {
                            series: [985, 737, 270],
                            chart: {
                                type: 'donut',
                                height: 353, // под твой min-h
                                fontFamily: 'Nunito, sans-serif',
                            },
                            dataLabels: { enabled: false },
                            stroke: {
                                show: true,
                                width: 25,
                                colors: isDark ? '#0e1726' : '#fff'
                            },
                            colors: isDark
                                ? ['#5c1ac3', '#e2a03f', '#e7515a', '#e2a03f']
                                : ['#e2a03f', '#5c1ac3', '#e7515a'],
                            legend: {
                                position: 'bottom',
                                horizontalAlign: 'center',
                                fontSize: '14px',
                                markers: { width: 10, height: 10, offsetX: -2 },
                                height: 50,
                                offsetY: 20,
                            },
                            plotOptions: {
                                pie: {
                                    donut: {
                                        size: '65%',
                                        background: 'transparent',
                                        labels: {
                                            show: true,
                                            name: { show: true, fontSize: '20px', offsetY: -10 },
                                            value: {
                                                show: true,
                                                fontSize: '18px',
                                                color: isDark ? '#bfc9d4' : undefined,
                                                offsetY: 10,
                                                formatter: (val) => val,
                                            },
                                            total: {
                                                show: true,
                                                label: 'Total',
                                                color: '#888ea8',
                                                fontSize: '18px',
                                                formatter: (w) => w.globals.seriesTotals.reduce((a, b) => a + b, 0),
                                            },
                                        },
                                    },
                                },
                            },
                            labels: ['Apparel', 'Sports', 'Others'],
                            states: {
                                hover: { filter: { type: 'none', value: 0.15 } },
                                active: { filter: { type: 'none', value: 0.15 } },
                            },
                        };
                    },
                };
            });
        });
    </script>


</x-layout.default>
