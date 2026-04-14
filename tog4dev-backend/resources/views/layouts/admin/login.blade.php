<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name', 'Tog4Dev') }} — {{ __('app.login') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/ico" href="{{ asset('favicon.ico') }}" />

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --login-primary: #13585D;
            --login-primary-light: #1a6e6e;
            --login-primary-dark: #0d4f4f;
            --login-accent: #FECD0F;
            --login-gray-50: #f9fafb;
            --login-gray-100: #f3f4f6;
            --login-gray-200: #e5e7eb;
            --login-gray-300: #d1d5db;
            --login-gray-400: #9ca3af;
            --login-gray-500: #6b7280;
            --login-gray-600: #4b5563;
            --login-gray-700: #374151;
            --login-gray-800: #1f2937;
            --login-gray-900: #111827;
            --login-radius: 12px;
            --login-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --login-shadow-lg: 0 20px 60px rgba(0,0,0,0.12), 0 4px 20px rgba(0,0,0,0.06);
            --login-font: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            font-family: var(--login-font);
            background: var(--login-gray-50);
            min-height: 100vh;
            display: flex;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .login-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        .login-brand-panel {
            flex: 0 0 45%;
            background: linear-gradient(135deg, var(--login-primary) 0%, var(--login-primary-dark) 50%, #0a3234 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }

        .login-brand-panel::before {
            content: '';
            position: absolute;
            top: -120px;
            right: -120px;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: rgba(254, 205, 15, 0.06);
        }

        .login-brand-panel::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.03);
        }

        .brand-content {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 380px;
        }

        .brand-logo {
            margin-bottom: 40px;
        }

        .brand-logo img {
            height: 72px;
        }

        .brand-title {
            font-size: 28px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 16px;
            line-height: 1.3;
        }

        .brand-subtitle {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.7;
            margin-bottom: 48px;
        }

        .brand-features {
            text-align: start;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .brand-feature {
            display: flex;
            align-items: center;
            gap: 14px;
            color: rgba(255, 255, 255, 0.85);
            font-size: 14px;
            font-weight: 500;
        }

        .brand-feature-icon {
            width: 36px;
            height: 36px;
            min-width: 36px;
            border-radius: 10px;
            background: rgba(254, 205, 15, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--login-accent);
            font-size: 14px;
        }

        .login-form-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .login-form-container {
            width: 100%;
            max-width: 400px;
        }

        .login-form-header {
            margin-bottom: 36px;
        }

        .login-form-header h1 {
            font-size: 26px;
            font-weight: 700;
            color: var(--login-gray-900);
            margin-bottom: 8px;
        }

        .login-form-header p {
            font-size: 14px;
            color: var(--login-gray-500);
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--login-gray-700);
            margin-bottom: 7px;
        }

        .form-input-wrap {
            position: relative;
        }

        .form-input-wrap i.field-icon {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 14px;
            color: var(--login-gray-400);
            font-size: 15px;
            pointer-events: none;
        }

        [dir="rtl"] .form-input-wrap i.field-icon {
            left: auto;
            right: 14px;
        }

        .form-input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border: 1.5px solid var(--login-gray-200);
            border-radius: var(--login-radius);
            font-size: 14px;
            font-family: var(--login-font);
            color: var(--login-gray-800);
            background: #fff;
            transition: all 0.2s ease;
            outline: none;
        }

        [dir="rtl"] .form-input {
            padding: 12px 42px 12px 14px;
        }

        .form-input:focus {
            border-color: var(--login-primary);
            box-shadow: 0 0 0 3px rgba(19, 88, 93, 0.08);
        }

        .form-input::placeholder {
            color: var(--login-gray-400);
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            right: 14px;
            background: none;
            border: none;
            color: var(--login-gray-400);
            cursor: pointer;
            font-size: 15px;
            padding: 4px;
            transition: color 0.2s;
        }

        [dir="rtl"] .password-toggle {
            right: auto;
            left: 14px;
        }

        .password-toggle:hover {
            color: var(--login-gray-600);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .remember-me input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--login-primary);
            cursor: pointer;
        }

        .remember-me span {
            font-size: 13px;
            color: var(--login-gray-600);
            user-select: none;
        }

        .forgot-link {
            font-size: 13px;
            color: var(--login-primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: var(--login-primary-dark);
        }

        .login-btn {
            width: 100%;
            padding: 13px;
            background: var(--login-primary);
            color: #fff;
            border: none;
            border-radius: var(--login-radius);
            font-size: 15px;
            font-weight: 600;
            font-family: var(--login-font);
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .login-btn:hover {
            background: var(--login-primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(19, 88, 93, 0.25);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .error-alert {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 10px 14px;
            border-radius: var(--login-radius);
            font-size: 13px;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .login-footer {
            text-align: center;
            margin-top: 36px;
            font-size: 12px;
            color: var(--login-gray-400);
        }

        .login-lang-switch {
            margin-top: 24px;
            text-align: center;
        }

        .login-lang-switch a {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: var(--login-gray-500);
            text-decoration: none;
            padding: 6px 14px;
            border-radius: 8px;
            border: 1px solid var(--login-gray-200);
            transition: all 0.2s;
        }

        .login-lang-switch a:hover {
            border-color: var(--login-primary);
            color: var(--login-primary);
        }

        .mobile-logo {
            display: none;
            text-align: center;
            margin-bottom: 32px;
        }

        .mobile-logo img {
            height: 48px;
        }

        @media (max-width: 991px) {
            .login-brand-panel { display: none; }
            .login-form-panel { padding: 32px 24px; }
            .mobile-logo { display: block; }
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <div class="login-brand-panel">
            <div class="brand-content">
                <div class="brand-logo">
                    <img src="{{ asset('img/logo.png') }}" alt="{{ config('app.name', 'Tog4Dev') }}">
                </div>
                <h2 class="brand-title">{{ __('app.admin_panel') }}</h2>
                <p class="brand-subtitle">{{ __('app.login_brand_description') }}</p>
                <div class="brand-features">
                    <div class="brand-feature">
                        <div class="brand-feature-icon"><i class="fas fa-chart-line"></i></div>
                        <span>{{ __('app.login_feature_analytics') }}</span>
                    </div>
                    <div class="brand-feature">
                        <div class="brand-feature-icon"><i class="fas fa-hand-holding-heart"></i></div>
                        <span>{{ __('app.login_feature_donations') }}</span>
                    </div>
                    <div class="brand-feature">
                        <div class="brand-feature-icon"><i class="fas fa-shield-alt"></i></div>
                        <span>{{ __('app.login_feature_security') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="login-form-panel">
            <div class="login-form-container">
                <div class="mobile-logo">
                    <img src="{{ asset('img/logo.png') }}" alt="{{ config('app.name', 'Tog4Dev') }}">
                </div>

                @yield('content')

                <div class="login-lang-switch">
                    @if(app()->getLocale() == 'ar')
                        <a href="{{ LaravelLocalization::getLocalizedURL('en') }}"><i class="fas fa-globe"></i> English</a>
                    @else
                        <a href="{{ LaravelLocalization::getLocalizedURL('ar') }}"><i class="fas fa-globe"></i> العربية</a>
                    @endif
                </div>

                <div class="login-footer">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Tog4Dev') }}
                </div>
            </div>
        </div>
    </div>

<script>
    function refreshToken(){
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '/refresh-csrf');
        xhr.onload = function() {
            if (xhr.status === 200) {
                var tokens = document.querySelectorAll('[name=_token]');
                tokens.forEach(function(t) { t.value = xhr.responseText; });
            }
        };
        xhr.send();
    }
    setInterval(refreshToken, 300000);
</script>

</body>
</html>
