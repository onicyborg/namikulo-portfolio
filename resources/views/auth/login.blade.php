<!DOCTYPE html>
<html lang="en">

<head>
    <title>Sign In - Namikulo Portfolio</title>
    <meta charset="utf-8" />
    <meta name="description" content="Masuk untuk mengelola konten Namikulo Portfolio." />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="canonical" href="{{ url()->current() }}" />
    <link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon.ico') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <script>
        if (window.top != window.self) {
            window.top.location.replace(window.self.location.href);
        }
    </script>
</head>

<body id="kt_body" class="app-blank bgi-size-cover bgi-attachment-fixed bgi-position-center">
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>

    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <style>
            body {
                background-image: url('{{ asset('assets/media/auth/bg10.jpeg') }}');
            }

            [data-bs-theme="dark"] body {
                background-image: url('{{ asset('assets/media/auth/bg10-dark.jpeg') }}');
            }
        </style>

        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <div class="d-flex flex-lg-row-fluid">
                <div class="d-flex flex-column flex-center pb-0 pb-lg-10 p-10 w-100">
                    <img class="theme-light-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20"
                        src="{{ asset('assets/media/auth/agency.png') }}" alt="" />
                    <img class="theme-dark-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20"
                        src="{{ asset('assets/media/auth/agency-dark.png') }}" alt="" />

                    <h1 class="text-gray-800 fs-2qx fw-bold text-center mb-7">Admin Namikulo Portfolio</h1>
                    <div class="text-gray-600 fs-base text-center fw-semibold">
                        Masuk untuk mengelola portfolio, kategori, dan testimonial.
                    </div>
                </div>
            </div>

            <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12">
                <div class="bg-body d-flex flex-column flex-center rounded-4 w-md-600px p-10">
                    <div class="d-flex flex-center flex-column align-items-stretch h-lg-100 w-md-400px">
                        <div class="d-flex flex-center flex-column flex-column-fluid pb-15 pb-lg-20">
                            <form class="form w-100" method="POST" action="{{ route('login.store') }}">
                                @csrf

                                <div class="text-center mb-11">
                                    <h1 class="text-gray-900 fw-bolder mb-3">Sign In</h1>
                                    <div class="text-gray-500 fw-semibold fs-6">Gunakan email atau username kamu</div>
                                </div>

                                @if ($errors->any())
                                    <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
                                        <i class="ki-duotone ki-information-5 fs-2hx text-danger me-4">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                        </i>
                                        <div class="d-flex flex-column">
                                            <div class="fw-semibold">Terjadi kesalahan</div>
                                            <div class="text-gray-700">
                                                {{ $errors->first() }}
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="fv-row mb-8">
                                    <input type="text" placeholder="Email atau Username" name="login"
                                        autocomplete="off" class="form-control bg-transparent"
                                        value="{{ old('login') }}" />
                                </div>

                                <div class="fv-row mb-8" data-kt-password-meter="true">
                                    <div class="position-relative mb-0">
                                        <input id="password" type="password" placeholder="Password" name="password"
                                            autocomplete="off" class="form-control bg-transparent" />
                                        <button type="button" id="toggle_password"
                                            class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                            aria-label="Toggle password visibility">
                                            <i id="toggle_password_icon" class="bi bi-eye fs-2"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                                    <label class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" name="remember" value="1"
                                            {{ old('remember') ? 'checked' : '' }} />
                                        <span class="form-check-label text-gray-700">Remember me</span>
                                    </label>
                                </div>

                                <div class="d-grid mb-10">
                                    <button type="submit" class="btn btn-primary">
                                        <span class="indicator-label">Sign In</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var hostUrl = "{{ asset('assets') }}/";
    </script>
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>

    <script>
        (function() {
            var toggleBtn = document.getElementById('toggle_password');
            var input = document.getElementById('password');
            var icon = document.getElementById('toggle_password_icon');

            if (!toggleBtn || !input || !icon) return;

            toggleBtn.addEventListener('click', function() {
                var isPassword = input.getAttribute('type') === 'password';
                input.setAttribute('type', isPassword ? 'text' : 'password');

                icon.classList.remove('bi-eye');
                icon.classList.remove('bi-eye-slash');
                icon.classList.add(isPassword ? 'bi-eye-slash' : 'bi-eye');
            });
        })();
    </script>
</body>

</html>
