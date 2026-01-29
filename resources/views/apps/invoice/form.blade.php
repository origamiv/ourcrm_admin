<x-layout.default>

    {{-- =====================================================
        CONFIG FROM BACKEND
    ===================================================== --}}
    <script>
        window.CONFIG     = @json($config);
        window.FORM_MODE  = @json($mode);   // create | edit | show
        window.ENTITY_ID  = @json($id ?? null);
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

        .form-error {
            border-color: #ef4444 !important;
        }
    </style>

    <div
        x-data="dynamicForm"
        :class="{ 'form-show': isShow }"
    >

        {{-- =====================================================
            FORM
        ===================================================== --}}
        <div class="panel px-6 py-6 max-w-4xl">

            <div class="text-2xl font-semibold mb-6"
                 x-text="CONFIG.common.name">
            </div>

            <form class="space-y-6" @submit.prevent="submit" x-show="!loading">

                <template x-for="field in formFields" :key="field.key">
                    <div class="sm:flex justify-between items-start gap-5 md:gap-20">

                        {{-- LABEL --}}
                        <label class="font-semibold w-48 pt-2"
                               x-text="field.name">
                        </label>

                        {{-- CONTROL --}}
                        <div class="w-full">

                            {{-- LOOKUP --}}
                            <template x-if="field.is_lookup">
                                <select
                                    class="form-select w-full"
                                    :class="{ 'form-error': errors[field.key] }"
                                    x-model.number="form[field.key]"
                                    :disabled="isShow"
                                >
                                    <option value="">—</option>

                                    <template
                                        x-for="item in lookups[field.key] ?? []"
                                        :key="item[field.lookup_id]"
                                    >
                                        <option
                                            :value="item[field.lookup_id]"
                                            x-text="item[field.lookup_name]"
                                        ></option>
                                    </template>
                                </select>
                            </template>

                            {{-- TEXT / NUMBER --}}
                            <template x-if="!field.is_lookup && ['text','number'].includes(field.control)">
                                <input
                                    :type="field.control"
                                    class="form-input w-full"
                                    :class="{ 'form-error': errors[field.key] }"
                                    x-model="form[field.key]"
                                    :disabled="isShow"
                                />
                            </template>

                            {{-- TEXTAREA --}}
                            <template x-if="!field.is_lookup && field.control === 'textarea'">
                                <textarea
                                    class="form-textarea w-full"
                                    rows="3"
                                    :class="{ 'form-error': errors[field.key] }"
                                    x-model="form[field.key]"
                                    :disabled="isShow">
                                </textarea>
                            </template>

                            {{-- DATETIME --}}
                            <template x-if="!field.is_lookup && field.control === 'datetime'">
                                <input
                                    type="datetime-local"
                                    class="form-input w-full"
                                    :class="{ 'form-error': errors[field.key] }"
                                    x-model="form[field.key]"
                                    :disabled="isShow"
                                />
                            </template>

                            {{-- STATUS --}}
                            <template x-if="!field.is_lookup && field.control === 'status'">
                                <select
                                    class="form-select w-full"
                                    :class="{ 'form-error': errors[field.key] }"
                                    x-model="form[field.key]"
                                    :disabled="isShow">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </template>

                            {{-- ERROR MESSAGE --}}
                            <template x-if="errors[field.key]">
                                <div class="text-danger text-sm mt-1"
                                     x-text="errors[field.key][0]">
                                </div>
                            </template>

                        </div>
                    </div>
                </template>

                {{-- ACTIONS --}}
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

    {{-- =====================================================
        LOGIC
    ===================================================== --}}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

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

                /* =============================
                   COMPUTED
                ============================= */
                get isShow() {
                    return this.mode === 'show';
                },

                get needsLoad() {
                    return ['edit', 'show'].includes(this.mode) && this.entityId;
                },

                /* =============================
                   INIT
                ============================= */
                async init() {
                    this.initForm();
                    await this.loadLookups();

                    if (this.needsLoad) {
                        await this.loadItem();
                    }
                },

                /* =============================
                   FIELDS
                ============================= */
                get formFields() {
                    return Object.entries(this.CONFIG.fields)
                        .filter(([_, field]) =>
                            field.field_mode?.split(',').includes(this.mode)
                        )
                        .map(([key, field]) => ({
                            key,
                            ...field
                        }));
                },

                initForm() {
                    this.formFields.forEach(field => {
                        this.form[field.key] = '';
                    });
                },

                /* =============================
                   LOOKUPS
                ============================= */
                async loadLookups() {
                    const token = localStorage.getItem('access_token');
                    if (!token) return;

                    const lookupFields = this.formFields.filter(f => f.is_lookup);

                    for (const field of lookupFields) {
                        const res = await axios.post(
                            `https://ozgang.ourtest.net${field.lookup_api}/list`,
                            { page: 1, perpage: 100 },
                            {
                                headers: {
                                    'Accept': 'application/json',
                                    'Authorization': `Bearer ${token}`
                                }
                            }
                        );

                        this.lookups[field.key] = res.data?.data ?? [];
                    }
                },

                /* =============================
                   LOAD ENTITY
                ============================= */
                async loadItem() {
                    const token = localStorage.getItem('access_token');
                    if (!token) return;

                    const url = `https://ozgang.ourtest.net${this.CONFIG.common.api}/${this.entityId}`;

                    this.loading = true;

                    try {
                        const res = await axios.get(url, {
                            headers: {
                                'Accept': 'application/json',
                                'Authorization': `Bearer ${token}`
                            }
                        });

                        const item = res.data?.data ?? {};

                        Object.keys(this.form).forEach(key => {
                            if (item[key] !== undefined) {
                                this.form[key] = item[key];
                            }
                        });

                    } finally {
                        this.loading = false;
                    }
                },

                /* =============================
                   SUBMIT
                ============================= */
                async submit() {
                    if (this.isShow) return;

                    // 🔥 СБРОС ОШИБОК
                    this.errors = {};

                    const token = localStorage.getItem('access_token');
                    if (!token) return;

                    const baseUrl = `https://ozgang.ourtest.net${this.CONFIG.common.api}`;
                    const url = this.mode === 'edit'
                        ? `${baseUrl}/${this.entityId}`
                        : baseUrl;

                    const method = this.mode === 'edit' ? 'put' : 'post';

                    try {
                        await axios({
                            method,
                            url,
                            data: this.form,
                            headers: {
                                'Accept': 'application/json',
                                'Authorization': `Bearer ${token}`
                            }
                        });

                        window.location.href = this.CONFIG.common.page;

                    } catch (e) {
                        if (e.response?.status === 422) {
                            this.errors = e.response.data.errors ?? {};
                        } else {
                            console.error(e);
                        }
                    }
                },

                cancel() {
                    history.back();
                }

            }));
        });
    </script>

</x-layout.default>
