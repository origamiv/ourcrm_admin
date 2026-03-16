<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $title ?? 'VRISTO - Multipurpose Tailwind Dashboard Template' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.png"/>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    @vite(['resources/css/app.css'])

    <script src="/assets/js/perfect-scrollbar.min.js"></script>
    <script defer src="/assets/js/popper.min.js"></script>
    <script defer src="/assets/js/tippy-bundle.umd.min.js"></script>
    <script defer src="/assets/js/sweetalert.min.js"></script>
</head>

<body
    x-data="main"
    class="antialiased relative font-nunito text-sm font-normal overflow-x-hidden"
    :class="[
        $store.app.sidebar ? 'toggle-sidebar' : '',
        $store.app.theme === 'dark' || $store.app.isDarkMode ? 'dark' : '',
        $store.app.menu,
        $store.app.layout,
        $store.app.rtlClass
    ]"
>

<!-- screen loader -->
<div class="screen_loader fixed inset-0 bg-[#fafafa] dark:bg-[#060818] z-[60] grid place-content-center animate__animated">
    <svg width="64" height="64" viewBox="0 0 135 135" xmlns="http://www.w3.org/2000/svg" fill="#4361ee">
        <path
            d="M67.447 58c5.523 0 10-4.477 10-10s-4.477-10-10-10-10 4.477-10 10 4.477 10 10 10zm9.448 9.447c0 5.523 4.477 10 10 10 5.522 0 10-4.477 10-10s-4.478-10-10-10c-5.523 0-10 4.477-10 10zm-9.448 9.448c-5.523 0-10 4.477-10 10 0 5.522 4.477 10 10 10s10-4.478 10-10c0-5.523-4.477-10-10-10zM58 67.447c0-5.523-4.477-10-10-10s-10 4.477-10 10 4.477 10 10 10 10-4.477 10-10z">
            <animateTransform attributeName="transform" type="rotate"
                              from="0 67 67" to="-360 67 67"
                              dur="2.5s" repeatCount="indefinite"/>
        </path>
    </svg>
</div>

<div class="main-container text-black dark:text-white-dark min-h-screen">
    {{ $slot }}
</div>

<script src="/assets/js/alpine-collaspe.min.js"></script>
<script src="/assets/js/alpine-persist.min.js"></script>
<script defer src="/assets/js/alpine-ui.min.js"></script>
<script defer src="/assets/js/alpine-focus.min.js"></script>
<script defer src="/assets/js/alpine.min.js"></script>
<script src="/assets/js/custom.js"></script>

<!-- ===================== -->
<!-- AUTH / API SECTION -->
<!-- ===================== -->

<!-- Axios -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    // =====================
    // Auth helper
    // =====================
    window.Auth = {
        getToken() {
            return localStorage.getItem('access_token');
        },

        isAuthenticated() {
            return !!this.getToken();
        },

        login(token) {
            localStorage.setItem('access_token', token);
        },

        logout() {
            localStorage.removeItem('access_token');
        }
    };

    // =====================
    // API instance
    // =====================
    window.api = axios.create({
        baseURL: '{{ config('app.api_url') }}/api',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
    });

    // Bearer token interceptor
    api.interceptors.request.use(config => {
        const token = Auth.getToken();
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    });

    // Global 401 handler
    api.interceptors.response.use(
        response => response,
        error => {
            if (error.response && error.response.status === 401) {
                Auth.logout();
            }
            return Promise.reject(error);
        }
    );

    // =====================
    // Auto redirect if already authenticated
    // =====================
    document.addEventListener('DOMContentLoaded', async () => {
        if (Auth.isAuthenticated()) {
            try {
                await api.get('/me');
                window.location.href = '/';
            } catch (e) {
                Auth.logout();
            }
        }
    });
</script>

</body>
</html>
