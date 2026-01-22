<x-layout.auth>

    <div x-data="auth">
        <div class="absolute inset-0">
            <img src="/assets/images/auth/bg-gradient.png" alt="image" class="h-full w-full object-cover" />
        </div>

        <div class="relative flex min-h-screen items-center justify-center bg-[url(/assets/images/auth/map.png)] bg-cover bg-center bg-no-repeat px-6 py-10 dark:bg-[#060818] sm:px-16">

            <img src="/assets/images/auth/coming-soon-object1.png" alt="image" class="absolute left-0 top-1/2 h-full max-h-[893px] -translate-y-1/2" />
            <img src="/assets/images/auth/coming-soon-object2.png" alt="image" class="absolute left-24 top-0 h-40 md:left-[30%]" />
            <img src="/assets/images/auth/coming-soon-object3.png" alt="image" class="absolute right-0 top-0 h-[300px]" />
            <img src="/assets/images/auth/polygon-object.svg" alt="image" class="absolute bottom-0 end-[28%]" />

            <div
                class="relative w-full max-w-[870px] rounded-md bg-[linear-gradient(45deg,#fff9f9_0%,rgba(255,255,255,0)_25%,rgba(255,255,255,0)_75%,#fff9f9_100%)] p-2 dark:bg-[linear-gradient(52.22deg,#0E1726_0%,rgba(14,23,38,0)_18.66%,rgba(14,23,38,0)_51.04%,rgba(14,23,38,0)_80.07%,#0E1726_100%)]">

                <div class="relative flex flex-col justify-center rounded-md bg-white/60 backdrop-blur-lg dark:bg-black/50 px-6 lg:min-h-[758px] py-20">

                    <div class="mx-auto w-full max-w-[440px]">

                        <div class="mb-10">
                            <h1 class="text-3xl font-extrabold uppercase !leading-snug text-primary md:text-4xl">
                                Sign in
                            </h1>
                            <p class="text-base font-bold leading-normal text-white-dark">
                                Enter your email and password to login
                            </p>
                        </div>

                        <!-- FORM -->
                        <form class="space-y-5 dark:text-white" @submit.prevent="submit">

                            <!-- EMAIL -->
                            <div>
                                <label for="Email">Email</label>
                                <div class="relative text-white-dark">
                                    <input
                                        id="Email"
                                        type="email"
                                        placeholder="Enter Email"
                                        class="form-input ps-10 placeholder:text-white-dark"
                                        x-model="email"
                                        required
                                    />
                                </div>
                            </div>

                            <!-- PASSWORD -->
                            <div>
                                <label for="Password">Password</label>
                                <div class="relative text-white-dark">
                                    <input
                                        id="Password"
                                        type="password"
                                        placeholder="Enter Password"
                                        class="form-input ps-10 placeholder:text-white-dark"
                                        x-model="password"
                                        required
                                    />
                                </div>
                            </div>

                            <!-- ERROR -->
                            <template x-if="error">
                                <div class="text-red-500 text-sm" x-text="error"></div>
                            </template>

                            <!-- SUBMIT -->
                            <button
                                type="submit"
                                class="btn btn-gradient !mt-6 w-full border-0 uppercase shadow-[0_10px_20px_-10px_rgba(67,97,238,0.44)]"
                                :disabled="loading"
                            >
                                <span x-show="!loading">Sign in</span>
                                <span x-show="loading">Signing in...</span>
                            </button>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ALPINE LOGIC -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('auth', () => ({
                email: '',
                password: '',
                loading: false,
                error: null,

                async submit() {
                    this.loading = true;
                    this.error = null;

                    try {
                        const { data } = await api.post('/login', {
                            email: this.email,
                            password: this.password,
                        });

                        // ВАЖНО: токен приходит как data.data.token
                        Auth.login(data.data.token);

                        window.location.href = '/';

                    } catch (e) {
                        this.error = 'Invalid email or password';
                    } finally {
                        this.loading = false;
                    }
                }
            }));
        });
    </script>

</x-layout.auth>
