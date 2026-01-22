<x-layout.auth>

    <div x-data="auth">
        <div class="absolute inset-0">
            <img src="/assets/images/auth/bg-gradient.png" alt="image" class="h-full w-full object-cover" />
        </div>

        <div
            class="relative flex min-h-screen items-center justify-center
                   bg-[url(/assets/images/auth/map.png)] bg-cover bg-center bg-no-repeat
                   px-6 py-10 dark:bg-[#060818] sm:px-16">

            <img src="/assets/images/auth/coming-soon-object1.png"
                 class="absolute left-0 top-1/2 h-full max-h-[893px] -translate-y-1/2" />
            <img src="/assets/images/auth/coming-soon-object2.png"
                 class="absolute left-24 top-0 h-40 md:left-[30%]" />
            <img src="/assets/images/auth/coming-soon-object3.png"
                 class="absolute right-0 top-0 h-[300px]" />
            <img src="/assets/images/auth/polygon-object.svg"
                 class="absolute bottom-0 end-[28%]" />

            <div
                class="relative w-full max-w-[870px] rounded-md
                       bg-[linear-gradient(45deg,#fff9f9_0%,rgba(255,255,255,0)_25%,rgba(255,255,255,0)_75%,#fff9f9_100%)]
                       p-2 dark:bg-[linear-gradient(52.22deg,#0E1726_0%,rgba(14,23,38,0)_18.66%,rgba(14,23,38,0)_51.04%,rgba(14,23,38,0)_80.07%,#0E1726_100%)]">

                <div
                    class="relative flex flex-col justify-center rounded-md
                           bg-white/60 backdrop-blur-lg dark:bg-black/50
                           px-6 lg:min-h-[758px] py-20">

                    <div class="mx-auto w-full max-w-[440px]">

                        <!-- HEADER -->
                        <div class="mb-10">
                            <h1 class="text-3xl font-extrabold uppercase !leading-snug text-primary md:text-4xl">
                                Вход
                            </h1>
                            <p class="text-base font-bold leading-normal text-white-dark">
                                Введите email и пароль для входа
                            </p>
                        </div>

                        <!-- FORM -->
                        <form class="space-y-5 dark:text-white" @submit.prevent="submit">

                            <!-- EMAIL -->
                            <div>
                                <label>Email</label>
                                <input
                                    type="email"
                                    class="form-input"
                                    placeholder="Введите email"
                                    x-model="email"
                                    required
                                />
                            </div>

                            <!-- PASSWORD -->
                            <div>
                                <label>Пароль</label>
                                <input
                                    type="password"
                                    class="form-input"
                                    placeholder="Введите пароль"
                                    x-model="password"
                                    required
                                />
                            </div>

                            <!-- ERROR -->
                            <template x-if="error">
                                <div class="text-sm text-red-500" x-text="error"></div>
                            </template>

                            <!-- SUBMIT -->
                            <button
                                type="submit"
                                class="btn btn-gradient w-full uppercase"
                                :disabled="loading"
                            >
                                <span x-show="!loading">Войти</span>
                                <span x-show="loading">Вход...</span>
                            </button>

                        </form>

                        <!-- LINKS -->
                        <div class="mt-6 text-center space-y-3">

                            <div>
                                <a href="/forgot-password"
                                   class="text-sm text-primary hover:underline">
                                    Забыли пароль?
                                </a>
                            </div>

                            <div class="text-sm text-white-dark">
                                Нет аккаунта?
                                <a href="/register"
                                   class="font-semibold text-primary hover:underline">
                                    Зарегистрироваться
                                </a>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- LOGIC -->
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
                        const res = await fetch('https://ozgang.ourtest.net/api/login', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                email: this.email,
                                password: this.password
                            })
                        });

                        const json = await res.json();

                        if (!json.success || !json.data?.token) {
                            throw new Error();
                        }

                        localStorage.setItem('access_token', json.data.token);
                        window.location.href = '/';

                    } catch {
                        this.error = 'Неверный email или пароль';
                    } finally {
                        this.loading = false;
                    }
                }
            }));
        });
    </script>

</x-layout.auth>
