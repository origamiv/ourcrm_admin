<div class="panel h-full xl:col-span-1">
    <div class="flex items-center dark:text-white-light mb-5">
        <h5 class="font-semibold text-lg">Revenue</h5>
        <div x-data="dropdown" @click.outside="open = false"
             class="dropdown ltr:ml-auto rtl:mr-auto">
            <a href="javascript:;" @click="toggle">
                <svg class="w-5 h-5 text-black/70 dark:text-white/70 hover:!text-primary"
                     viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="5" cy="12" r="2" stroke="currentColor"
                            stroke-width="1.5" />
                    <circle opacity="0.5" cx="12" cy="12" r="2"
                            stroke="currentColor" stroke-width="1.5" />
                    <circle cx="19" cy="12" r="2" stroke="currentColor"
                            stroke-width="1.5" />
                </svg>
            </a>
            <ul x-cloak x-show="open" x-transition x-transition.duration.300ms
                class="ltr:right-0 rtl:left-0">
                <li><a href="javascript:;" @click="toggle">Weekly</a></li>
                <li><a href="javascript:;" @click="toggle">Monthly</a></li>
                <li><a href="javascript:;" @click="toggle">Yearly</a></li>
            </ul>
        </div>
    </div>
    <p class="text-lg dark:text-white-light/90">Total Profit <span
            class="text-primary ml-2">${{ $xx }}</span></p>
    <div class="relative overflow-hidden">
        <div x-ref="revenueChart" class="bg-white dark:bg-black rounded-lg">
            <!-- loader -->
            <div
                class="min-h-[325px] grid place-content-center bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] ">
                                <span
                                    class="animate-spin border-2 border-black dark:border-white !border-l-transparent  rounded-full w-5 h-5 inline-flex"></span>
            </div>
        </div>
    </div>
</div>
