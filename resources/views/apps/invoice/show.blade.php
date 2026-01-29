<x-layout.default>

    {{-- =====================================================
        CONFIG
    ===================================================== --}}
    <script>
        window.CONFIG = @json($config);
        window.FORM_MODE = 'create'; // create | edit
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

            <form class="space-y-6" @submit.prevent="submit">

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
                                />
                            </template>

                            {{-- TEXTAREA --}}
                            <template x-if="field.control === 'textarea'">
                                <textarea
                                    class="form-textarea w-full"
                                    rows="3"
                                    x-model="form[field.key]">
                                </textarea>
                            </template>

                            {{-- DATETIME --}}
                            <template x-if="field.control === 'datetime'">
                                <input
                                    type="datetime-local"
                                    class="form-input w-full"
                                    x-model="form[field.key]"
                                />
                            </template>

                            {{-- STATUS --}}
                            <template x-if="field.control === 'status'">
                                <select class="form-select w-full"
                                        x-model="form[field.key]">
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
                        Cancel
                    </button>

                    <button type="submit"
                            class="btn btn-primary">
                        Save
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- =====================================================
        LOGIC
    ===================================================== --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dynamicForm', () => ({

                CONFIG: window.CONFIG,
                mode: window.FORM_MODE,
                form: {},

                init() {
                    this.initForm();
                },

                /* =============================
                   FIELDS FOR FORM
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
                   ACTIONS
                ============================= */
                submit() {
                    console.log('FORM DATA', this.form);

                    // fetch(this.CONFIG.common.api, {
                    //     method: 'POST',
                    //     headers: {...},
                    //     body: JSON.stringify(this.form)
                    // })
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
