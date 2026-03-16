{{-- resources/views/components/graph/graphic.blade.php --}}

@php
    $title      = $title ?? 'Revenue';
    $label      = $label ?? 'Total Profit';

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

        // fallback total до первого ответа API
        initialTotal: 0,
        chartType: @js($chartType ?? 'line'),  // 'line' или 'column'
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

    {{-- total берём из dataset.total, fallback = initialTotal --}}
    <p class="text-lg dark:text-white-light/90">
        {{ $label }}
        <span class="text-primary ml-2" x-text="total"></span>
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
