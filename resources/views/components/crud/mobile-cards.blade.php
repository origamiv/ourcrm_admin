{{--
    Mobile card list component.
    Must be placed inside an Alpine.js x-data="dataTable" scope.
    Renders accordion cards from `items` using `cardConfig` built by buildCardConfig().
--}}
<div class="md:hidden space-y-2 pb-2">

    {{-- Loading skeleton --}}
    <template x-if="isLoading && items.length === 0">
        <div class="space-y-2">
            <template x-for="i in [1,2,3,4,5]" :key="i">
                <div class="panel p-4 animate-pulse">
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-2"></div>
                    <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/2"></div>
                </div>
            </template>
        </div>
    </template>

    {{-- Empty state --}}
    <template x-if="!isLoading && items.length === 0">
        <div class="panel py-10 text-center text-gray-400">
            <i class="uil uil-inbox text-4xl block mb-2"></i>
            Нет данных
        </div>
    </template>

    {{-- Cards --}}
    <template x-for="(row, idx) in items" :key="row.id ?? idx">
        <div class="panel p-0 overflow-hidden" x-data="{ open: false }">

            {{-- Card header (toggle) --}}
            <button
                type="button"
                @click="open = !open"
                class="w-full flex items-start justify-between gap-2 px-4 py-3 text-left"
            >
                <div class="flex-1 min-w-0">
                    {{-- Primary name field --}}
                    <div
                        class="font-medium text-sm truncate text-gray-900 dark:text-white"
                        x-text="cardConfig ? (formatCardValue(row, cardConfig.nameField) || '—') : (row.id ?? '—')"
                    ></div>

                    {{-- Extra header fields --}}
                    <template x-if="cardConfig && cardConfig.headerFields.length > 0">
                        <div class="mt-0.5 space-y-0.5">
                            <template x-for="fKey in cardConfig.headerFields" :key="fKey">
                                <div
                                    class="text-xs text-gray-500 dark:text-gray-400 truncate"
                                    x-text="formatCardValue(row, fKey) || ''"
                                ></div>
                            </template>
                        </div>
                    </template>
                </div>

                {{-- Status icon + chevron --}}
                <div class="flex items-center gap-2 shrink-0 pt-0.5">
                    <template x-if="cardConfig && cardConfig.statusField">
                        <span x-html="statusIcon(row[cardConfig.statusField])"></span>
                    </template>

                    <svg
                        class="w-4 h-4 text-gray-400 transition-transform duration-200 shrink-0"
                        :class="{ 'rotate-180': open }"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path fill-rule="evenodd"
                              d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                              clip-rule="evenodd" />
                    </svg>
                </div>
            </button>

            {{-- Card body (accordion) --}}
            <div x-show="open" x-collapse>
                <div class="border-t border-gray-100 dark:border-[#1b2e4b]">

                    {{-- Field rows --}}
                    <dl class="px-4 py-3 space-y-2">
                        <template x-if="cardConfig">
                            <template x-for="col in cardConfig.allFields" :key="col.key">
                                <div class="flex justify-between gap-3 text-sm">
                                    <dt class="text-gray-500 dark:text-gray-400 shrink-0 text-xs pt-0.5"
                                        x-text="col.label"></dt>
                                    <dd class="text-right font-medium text-gray-800 dark:text-gray-200 min-w-0">
                                        <template x-if="col.control === 'image'">
                                            <template x-if="imageUrlFromVal(row[col.key])">
                                                <img :src="imageUrlFromVal(row[col.key])"
                                                     style="height:48px;width:auto;object-fit:cover;border-radius:4px;margin-left:auto">
                                            </template>
                                        </template>
                                        <template x-if="col.control !== 'image'">
                                            <span class="break-words" x-text="formatCardValue(row, col.key) ?? '—'"></span>
                                        </template>
                                    </dd>
                                </div>
                            </template>
                        </template>
                    </dl>

                    {{-- Actions --}}
                    <div class="flex gap-2 px-4 py-3 border-t border-gray-100 dark:border-[#1b2e4b]">
                        <a
                            :href="`/${window.CONFIG.common.shortname}/${row.id}/show`"
                            class="btn btn-outline-info btn-sm flex-1 text-center"
                        >
                            <i class="uil uil-eye mr-1"></i>Просмотр
                        </a>
                        <a
                            :href="`/${window.CONFIG.common.shortname}/${row.id}/edit`"
                            class="btn btn-outline-warning btn-sm flex-1 text-center"
                        >
                            <i class="uil uil-edit mr-1"></i>Изменить
                        </a>
                        <button
                            type="button"
                            @click="deleteModal?.open(row.id)"
                            class="btn btn-outline-danger btn-sm"
                        >
                            <i class="uil uil-trash-alt"></i>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </template>

    {{-- Pagination --}}
    <div
        class="flex flex-wrap items-center justify-between gap-3 px-1 py-2"
        x-show="usePagination && (count > 0 || isLoading)"
    >
        <div class="text-xs text-white-dark">
            Стр. <span class="font-semibold" x-text="page"></span>
            из <span class="font-semibold" x-text="totalPages"></span>
            · <span class="font-semibold" x-text="count"></span> записей
        </div>

        <div class="flex items-center gap-1.5 flex-wrap">
            <button
                class="btn btn-outline-secondary btn-sm px-2"
                :disabled="page <= 1 || isLoading"
                @click="goToPage(page - 1)"
            >←</button>

            <template x-for="p in pagesToShow" :key="p">
                <button
                    class="btn btn-sm px-2.5"
                    :class="p === page ? 'btn-primary' : 'btn-outline-secondary'"
                    :disabled="isLoading"
                    @click="goToPage(p)"
                    x-text="p"
                ></button>
            </template>

            <button
                class="btn btn-outline-secondary btn-sm px-2"
                :disabled="page >= totalPages || isLoading"
                @click="goToPage(page + 1)"
            >→</button>
        </div>
    </div>

</div>
