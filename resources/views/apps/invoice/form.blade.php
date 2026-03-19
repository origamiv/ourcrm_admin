<x-layout.default>

    {{-- =====================================================
        CONFIG FROM BACKEND
    ===================================================== --}}
    <script>
        window.CONFIG     = @json($config);
        window.FORM_MODE  = @json($mode);   // create | edit | show
        window.ENTITY_ID  = @json($id ?? null);

        window.resolveUrl = function(path) {
            if (!path || /^https?:\/\//.test(path)) return path;
            return '{{ config('app.api_url') }}' + path;
        };
    </script>

    {{-- =====================================================
        SHOW MODE STYLES
    ===================================================== --}}
    <style>
        .form-show input,
        .form-show textarea,
        .form-show select {
            background-color: #f3f4f6 !important;
            color: #374151;
            cursor: not-allowed;
        }

        .dark .form-show input,
        .dark .form-show textarea,
        .dark .form-show select {
            background-color: #1f2937 !important;
            color: #9ca3af;
        }

        .field-error {
            border-color: #ef4444 !important;
        }

        .field-error-text {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 4px;
        }
    </style>

    <div x-data="dynamicForm" :class="{ 'form-show': isShow }">

        {{-- =====================================================
            МОБИЛЬНАЯ КАРТОЧКА (< md)
        ===================================================== --}}
        <div class="md:hidden">
            <div class="panel p-0 overflow-hidden">

                {{-- Заголовок --}}
                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-[#1b2e4b]">
                    <div>
                        <div class="font-semibold text-base" x-text="CONFIG.common.name"></div>
                        <div class="text-xs text-gray-400 mt-0.5"
                             x-text="isShow ? 'Просмотр' : 'Редактирование'"></div>
                    </div>
                    <button type="button" @click="cancel()"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-xl leading-none">
                        <i class="uil uil-arrow-left"></i>
                    </button>
                </div>

                {{-- Поля --}}
                <form @submit.prevent="submit()" x-show="!loading">
                    <dl class="divide-y divide-gray-100 dark:divide-[#1b2e4b]">
                        <template x-for="field in formFields" :key="field.key">
                            <div class="px-4 py-3">
                                <dt class="text-xs text-gray-500 dark:text-gray-400 mb-1"
                                    x-text="field.name"></dt>
                                <dd>
                                    {{-- LOOKUP --}}
                                    <template x-if="field.is_lookup">
                                        <select class="form-select w-full"
                                                :class="{ 'field-error': errors[field.key] }"
                                                x-model.number="form[field.key]"
                                                :disabled="isShow">
                                            <option value="">—</option>
                                            <template x-for="item in lookups[field.key] ?? []"
                                                      :key="item[field.lookup_id ?? 'id']">
                                                <option :value="item[field.lookup_id ?? 'id']"
                                                        x-text="item[field.lookup_name]"></option>
                                            </template>
                                        </select>
                                    </template>
                                    {{-- TEXTAREA / JSON in SHOW mode with modifier --}}
                                    <template x-if="!field.is_lookup && ['textarea','json'].includes(field.control) && isShow && field.modifier">
                                        <div class="py-1 text-gray-800 dark:text-gray-200"
                                             x-html="renderFieldValue(field)"></div>
                                    </template>
                                    {{-- TEXTAREA / JSON in edit/create or no modifier --}}
                                    <template x-if="!field.is_lookup && ['textarea','json'].includes(field.control) && (!isShow || !field.modifier)">
                                        <textarea class="form-textarea w-full"
                                                  :class="{ 'field-error': errors[field.key] }"
                                                  x-model="form[field.key]"
                                                  :disabled="isShow"></textarea>
                                    </template>
                                    {{-- CHECKBOX --}}
                                    <template x-if="!field.is_lookup && field.control === 'checkbox'">
                                        <input type="checkbox"
                                               class="w-5 h-5 rounded"
                                               x-model="form[field.key]"
                                               :disabled="isShow" />
                                    </template>
                                    {{-- STATUS select --}}
                                    <template x-if="!field.is_lookup && field.control === 'status'">
                                        <select class="form-select w-full"
                                                :class="{ 'field-error': errors[field.key] }"
                                                x-model.number="form[field.key]"
                                                :disabled="isShow">
                                            <template x-for="[val, label] in FieldComponents.statusSelectItems(field.field_items)" :key="val">
                                                <option :value="Number(val)" x-text="label"></option>
                                            </template>
                                        </select>
                                    </template>
                                    {{-- SELECT with field_items --}}
                                    <template x-if="!field.is_lookup && field.control === 'select' && field.field_items">
                                        <select class="form-select w-full"
                                                :class="{ 'field-error': errors[field.key] }"
                                                x-model.number="form[field.key]"
                                                :disabled="isShow">
                                            <template x-for="[val, label] in Object.entries(field.field_items)" :key="val">
                                                <option :value="Number(val)" x-text="label"></option>
                                            </template>
                                        </select>
                                    </template>
                                    {{-- IMAGE --}}
                                    <template x-if="!field.is_lookup && field.control === 'image'">
                                        <div>
                                            <template x-if="isShow">
                                                <div x-html="renderFieldValue(field)"></div>
                                            </template>
                                            <template x-if="!isShow">
                                                <div>
                                                    <template x-if="getImageUrl(field.key)">
                                                        <img :src="getImageUrl(field.key)"
                                                             class="mb-2 max-h-40 rounded object-contain w-full">
                                                    </template>
                                                    <input type="file" accept="image/*"
                                                           @change="onImageFile(field.key, $event)"
                                                           class="form-input w-full">
                                                    <template x-if="getImageUrl(field.key)">
                                                        <button type="button"
                                                                @click="form[field.key] = ''"
                                                                class="text-xs text-danger mt-1">Удалить фото</button>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                    {{-- SHOW MODE with modifier: render via FieldComponents --}}
                                    <template x-if="!field.is_lookup && !['textarea','json','checkbox','status','select','image'].includes(field.control) && isShow && field.modifier">
                                        <div class="py-1 text-gray-800 dark:text-gray-200"
                                             x-html="renderFieldValue(field)"></div>
                                    </template>
                                    {{-- FALLBACK: text / number / integer / string / email / etc. --}}
                                    <template x-if="!field.is_lookup && !['textarea','json','checkbox','status','select','image'].includes(field.control) && (!isShow || !field.modifier)">
                                        <input :type="['number','integer'].includes(field.control) ? 'number' : 'text'"
                                               class="form-input w-full"
                                               :class="{ 'field-error': errors[field.key] }"
                                               x-model="form[field.key]"
                                               :disabled="isShow" />
                                    </template>
                                    {{-- ERROR TEXT --}}
                                    <template x-if="errors[field.key]">
                                        <div class="field-error-text" x-text="errors[field.key][0]"></div>
                                    </template>
                                </dd>
                            </div>
                        </template>
                    </dl>

                    {{-- Кнопки --}}
                    <div class="flex gap-2 px-4 py-3 border-t border-gray-100 dark:border-[#1b2e4b]">
                        <button type="button" class="btn btn-outline-secondary flex-1" @click="cancel()">
                            Назад
                        </button>
                        <button type="submit" class="btn btn-primary flex-1" x-show="!isShow">
                            Сохранить
                        </button>
                    </div>
                </form>

                <div x-show="loading" class="py-10 text-center text-gray-400">
                    Загрузка...
                </div>

            </div>
        </div>

        {{-- =====================================================
            ДЕСКТОПНАЯ ФОРМА (>= md)
        ===================================================== --}}
        <div class="hidden md:block">
            <div class="panel px-6 py-6 max-w-4xl">

                <div class="text-2xl font-semibold mb-6"
                     x-text="CONFIG.common.name">
                </div>

                <form class="space-y-6" @submit.prevent="submit" x-show="!loading">

                    <template x-for="field in formFields" :key="field.key">
                        <div class="sm:flex justify-between items-start gap-5 md:gap-20">

                            <label class="font-semibold w-48 pt-2"
                                   x-text="field.name">
                            </label>

                            <div class="w-full">

                                {{-- LOOKUP --}}
                                <template x-if="field.is_lookup">
                                    <select
                                        class="form-select w-full"
                                        :class="{ 'field-error': errors[field.key] }"
                                        x-model.number="form[field.key]"
                                        :disabled="isShow"
                                    >
                                        <option value="">—</option>

                                        <template x-for="item in lookups[field.key] ?? []"
                                                  :key="item[field.lookup_id ?? 'id']">
                                            <option
                                                :value="item[field.lookup_id ?? 'id']"
                                                x-text="item[field.lookup_name]"
                                            ></option>
                                        </template>
                                    </select>
                                </template>

                                {{-- TEXTAREA / JSON in SHOW mode with modifier --}}
                                <template x-if="!field.is_lookup && ['textarea','json'].includes(field.control) && isShow && field.modifier">
                                    <div class="py-1 text-gray-800 dark:text-gray-200"
                                         x-html="renderFieldValue(field)"></div>
                                </template>
                                {{-- TEXTAREA / JSON in edit/create or no modifier --}}
                                <template x-if="!field.is_lookup && ['textarea','json'].includes(field.control) && (!isShow || !field.modifier)">
                                    <textarea
                                        class="form-textarea w-full"
                                        :class="{ 'field-error': errors[field.key] }"
                                        x-model="form[field.key]"
                                        :disabled="isShow"></textarea>
                                </template>

                                {{-- CHECKBOX --}}
                                <template x-if="!field.is_lookup && field.control === 'checkbox'">
                                    <input type="checkbox"
                                           class="w-5 h-5 rounded"
                                           x-model="form[field.key]"
                                           :disabled="isShow" />
                                </template>

                                {{-- STATUS (0/1 select) --}}
                                <template x-if="!field.is_lookup && field.control === 'status'">
                                    <select class="form-select w-full"
                                            :class="{ 'field-error': errors[field.key] }"
                                            x-model.number="form[field.key]"
                                            :disabled="isShow">
                                        <option value="0">Неактивен</option>
                                        <option value="1">Активен</option>
                                    </select>
                                </template>

                                {{-- SELECT with field_items --}}
                                <template x-if="!field.is_lookup && field.control === 'select' && field.field_items">
                                    <select class="form-select w-full"
                                            :class="{ 'field-error': errors[field.key] }"
                                            x-model.number="form[field.key]"
                                            :disabled="isShow">
                                        <template x-for="[val, label] in Object.entries(field.field_items)" :key="val">
                                            <option :value="Number(val)" x-text="label"></option>
                                        </template>
                                    </select>
                                </template>

                                {{-- IMAGE --}}
                                <template x-if="!field.is_lookup && field.control === 'image'">
                                    <div>
                                        <template x-if="isShow">
                                            <div x-html="renderFieldValue(field)"></div>
                                        </template>
                                        <template x-if="!isShow">
                                            <div>
                                                <template x-if="getImageUrl(field.key)">
                                                    <img :src="getImageUrl(field.key)"
                                                         class="mb-2 max-h-48 rounded object-contain">
                                                </template>
                                                <input type="file" accept="image/*"
                                                       @change="onImageFile(field.key, $event)"
                                                       class="form-input w-full">
                                                <template x-if="getImageUrl(field.key)">
                                                    <button type="button"
                                                            @click="form[field.key] = ''"
                                                            class="text-xs text-danger mt-1">Удалить фото</button>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                {{-- SHOW MODE with modifier: render via FieldComponents --}}
                                <template x-if="!field.is_lookup && !['textarea','json','checkbox','status','select','image'].includes(field.control) && isShow && field.modifier">
                                    <div class="py-1 text-gray-800 dark:text-gray-200"
                                         x-html="renderFieldValue(field)"></div>
                                </template>
                                {{-- FALLBACK: text / number / integer / string / email / etc. --}}
                                <template x-if="!field.is_lookup && !['textarea','json','checkbox','status','select','image'].includes(field.control) && (!isShow || !field.modifier)">
                                    <input :type="['number','integer'].includes(field.control) ? 'number' : 'text'"
                                           class="form-input w-full"
                                           :class="{ 'field-error': errors[field.key] }"
                                           x-model="form[field.key]"
                                           :disabled="isShow"
                                    />
                                </template>

                                {{-- ERROR TEXT --}}
                                <template x-if="errors[field.key]">
                                    <div class="field-error-text"
                                         x-text="errors[field.key][0]">
                                    </div>
                                </template>

                            </div>
                        </div>
                    </template>

                    <div class="flex justify-end gap-3 pt-6">
                        <button type="button"
                                class="btn btn-outline-secondary"
                                @click="cancel">
                            Back
                        </button>

                        <button type="submit"
                                class="btn btn-primary"
                                x-show="!isShow">
                            Save
                        </button>
                    </div>

                </form>

                <div x-show="loading" class="text-center py-10 text-white-dark">
                    Loading...
                </div>

            </div>
        </div>

    </div>

    {{-- =====================================================
        LOGIC
    ===================================================== --}}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/luxon@3.4.4/build/global/luxon.min.js"></script>
    <script src="/assets/js/components/text.js?v={{ filemtime(public_path('assets/js/components/text.js')) }}"></script>
    <script src="/assets/js/components/image.js?v={{ filemtime(public_path('assets/js/components/image.js')) }}"></script>
    <script src="/assets/js/components/status.js?v={{ filemtime(public_path('assets/js/components/status.js')) }}"></script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dynamicForm', () => ({

                CONFIG: window.CONFIG,
                mode: window.FORM_MODE,
                entityId: window.ENTITY_ID,

                form: {},
                lookups: {},
                errors: {},
                loading: false,

                get isShow() {
                    return this.mode === 'show';
                },

                get needsLoad() {
                    return ['edit', 'show'].includes(this.mode) && this.entityId;
                },

                async init() {
                    this.initForm();
                    await this.loadLookups();

                    if (this.needsLoad) {
                        await this.loadItem();
                    }
                },

                get formFields() {
                    return Object.entries(this.CONFIG.fields)
                        .filter(([_, field]) =>
                            !field.field_mode || field.field_mode.split(',').includes(this.mode)
                        )
                        .map(([key, field]) => ({ key, ...field }));
                },

                initForm() {
                    this.formFields.forEach(field => {
                        this.form[field.key] = field.field_default !== undefined ? field.field_default : '';
                    });
                },

                async loadLookups() {
                    const token = localStorage.getItem('access_token');
                    if (!token) return;

                    for (const field of this.formFields.filter(f => f.is_lookup && f.lookup_api)) {
                        const api = resolveUrl(field.lookup_api);

                        // Проверяем кэш справочников, затем кэш основных таблиц
                        const cached = window.LookupCache?.get(api) ?? window.MainTableCache?.get(api);
                        if (cached !== null && cached !== undefined) {
                            this.lookups[field.key] = cached;
                            continue;
                        }

                        try {
                            const res = await axios.post(
                                `${api}/list`,
                                { page: 1, perpage: 100 },
                                {
                                    headers: {
                                        'Accept': 'application/json',
                                        'Authorization': `Bearer ${token}`
                                    }
                                }
                            );

                            const list = res.data?.data ?? [];
                            this.lookups[field.key] = list;

                            // Сохраняем в кэш
                            window.LookupCache?.set(api, list);
                        } catch (e) {
                            console.warn(`loadLookups: не удалось загрузить справочник "${field.key}"`, e);
                            this.lookups[field.key] = [];
                        }
                    }
                },

                async loadItem() {
                    const token = localStorage.getItem('access_token');
                    if (!token) return;

                    this.loading = true;

                    try {
                        const res = await axios.get(
                            `${resolveUrl(this.CONFIG.common.api)}/${this.entityId}`,
                            {
                                headers: {
                                    'Accept': 'application/json',
                                    'Authorization': `Bearer ${token}`
                                }
                            }
                        );

                        const item = res.data?.data ?? {};
                        Object.keys(this.form).forEach(k => {
                            if (item[k] !== undefined) this.form[k] = item[k];
                        });

                    } finally {
                        this.loading = false;
                    }
                },

                async submit() {
                    if (this.isShow) return;

                    this.errors = {};

                    const token = localStorage.getItem('access_token');
                    if (!token) return;

                    let url;
                    let method;

                    const base = resolveUrl(this.CONFIG.common.api);

                    if (this.mode === 'create') {
                        url = `${base}/create`;
                        method = 'post';
                    } else {
                        url = `${base}/${this.entityId}`;
                        method = 'put';
                    }

                    try {
                        const response = await axios({
                            method,
                            url,
                            data: this.form,
                            headers: {
                                'Accept': 'application/json',
                                'Authorization': `Bearer ${token}`
                            }
                        });

                        // Обновляем кэш справочника и кэш основной таблицы
                        const savedItem = response.data?.data ?? this.form;
                        if (this.mode === 'create') {
                            window.LookupCache?.addItem(base, savedItem);
                            window.MainTableCache?.addItem(base, savedItem);
                        } else {
                            window.LookupCache?.updateItem(base, this.entityId, savedItem);
                            window.MainTableCache?.updateItem(base, this.entityId, savedItem);
                        }

                        window.location.href = this.CONFIG.common.page;

                    } catch (e) {
                        if (e.response?.status === 422) {
                            this.errors = e.response.data.errors ?? {};
                        }
                    }
                },

                cancel() {
                    history.back();
                },

                getImageUrl(key) {
                    const value = this.form[key];
                    if (!value) return null;
                    let parsed = value;
                    if (typeof value === 'string') {
                        try { parsed = JSON.parse(value); } catch (e) { return value; }
                    }
                    if (typeof parsed === 'object' && parsed !== null) {
                        return parsed.main ?? Object.values(parsed)[0] ?? null;
                    }
                    return typeof value === 'string' ? value : null;
                },

                onImageFile(key, event) {
                    const file = event.target.files[0];
                    if (!file) return;
                    const reader = new FileReader();
                    reader.onload = ev => {
                        this.form[key] = JSON.stringify({ main: ev.target.result });
                    };
                    reader.readAsDataURL(file);
                },

                renderFieldValue(field) {
                    const value = this.form[field.key];
                    const entity = this.CONFIG.common.shortname ?? '';
                    const control = field.control ?? 'text';
                    const cmp = (window.FieldComponents && window.FieldComponents[control])
                        ? window.FieldComponents[control]
                        : window.FieldComponents?.text;

                    if (cmp?.show) {
                        return cmp.show({
                            entity: entity,
                            name: field.key,
                            value: value,
                            config: field,
                            row: this.form
                        });
                    }
                    const v = (value === null || value === undefined) ? '' : String(value);
                    return v || '—';
                },

            }));
        });
    </script>

</x-layout.default>
