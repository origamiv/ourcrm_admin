<x-layout.default>

    {{-- =====================================================
        CONFIG FROM BACKEND
    ===================================================== --}}
    <script>
        window.CONFIG     = @json($config);
        window.FORM_MODE  = @json($mode);   // create | edit | show
        window.ENTITY_ID  = @json($id ?? null);
    </script>

    <div x-data="dynamicForm">

        {{-- =====================================================
            TOP ACTION BUTTONS (НЕ ТРОГАЕМ)
        ===================================================== --}}
        <div class="flex items-center lg:justify-end justify-center flex-wrap gap-4 mb-6">
            <button type="button" class="btn btn-info">Send</button>
            <button type="button" class="btn btn-primary" @click="print()">Print</button>
            <button type="button" class="btn btn-success">Download</button>
            <a href="#" class="btn btn-secondary">Create</a>
            <a href="#" class="btn btn-warning">Edit</a>
        </div>

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

                            {{-- TEXT / NUMBER --}}
                            <template x-if="['text','number'].includes(field.control)">
                                <input
                                    :type="field.control"
                                    class="form-input w-full"
                                    x-model="form[field.key]"
                                    :disabled="isShow"
                                />
                            </template>

                            {{-- TEXTAREA --}}
                            <template x-if="field.control === 'textarea'">
                                <textarea
                                    class="form-textarea w-full"
                                    rows="3"
                                    x-model="form[field.key]"
                                    :disabled="isShow">
                                </textarea>
                            </template>

                            {{-- DATETIME --}}
                            <template x-if="field.control === 'datetime'">
                                <input
                                    type="datetime-local"
                                    class="form-input w-full"
                                    x-model="form[field.key]"
                                    :disabled="isShow"
                                />
                            </template>

                            {{-- STATUS --}}
                            <template x-if="field.control === 'status'">
                                <select
                                    class="form-select w-full"
                                    x-model="form[field.key]"
                                    :disabled="isShow">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </template>

                        </div>
                    </div>
                </template>

                {{-- ACTIONS --}}
                <div class="flex justify-end gap-3 pt-6">
                    <button type="button"
                            class="btn btn-outline-danger"
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

                    if (this.needsLoad) {
                        await this.loadItem();
                    }
                },

                /* =============================
                   FIELDS BY MODE
                ============================= */
                get formFields() {
                    return Object.entries(this.CONFIG.fields)
                        .filter(([_, field]) =>
                            field.field_mode
                                ?.split(',')
                                .includes(this.mode)
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
                   LOAD ENTITY (edit / show)
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

                    } catch (e) {
                        console.error('Load error', e);
                    } finally {
                        this.loading = false;
                    }
                },

                /* =============================
                   SUBMIT (create / edit)
                ============================= */
                async submit() {
                    if (this.isShow) return;

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

                        alert('Saved');

                    } catch (e) {
                        console.error('Save error', e);
                    }
                },

                cancel() {
                    history.back();
                },

                print() {
                    window.print();
                }

            }));
        });
    </script>

</x-layout.default>
