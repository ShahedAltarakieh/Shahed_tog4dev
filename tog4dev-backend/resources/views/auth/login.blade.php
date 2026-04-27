@extends('layouts.admin.login')

@section('content')
<div class="login-form-header">
    <h1>{{ __('app.welcome_back') }}</h1>
    <p>{{ __('app.login_subtitle') }}</p>
</div>

@if($errors->any())
<div class="error-alert">
    <i class="fas fa-exclamation-circle"></i>
    <span>{{ $errors->first() }}</span>
</div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="form-group">
        <label for="emailaddress">{{ __('app.email') }}</label>
        <div class="form-input-wrap">
            <i class="fas fa-envelope field-icon"></i>
            <input class="form-input" type="email" name="email" id="emailaddress" value="{{ old('email') }}" placeholder="{{ __('app.enter_email') }}" required autofocus>
        </div>
    </div>

    <div class="form-group">
        <label for="password">{{ __('app.password') }}</label>
        <div class="form-input-wrap">
            <i class="fas fa-lock field-icon"></i>
            <input class="form-input" type="password" name="password" id="password" placeholder="{{ __('app.enter_password') }}" required>
            <button type="button" class="password-toggle" onclick="togglePassword()" aria-label="{{ __('app.toggle_password') }}">
                <i class="fas fa-eye" id="toggleIcon"></i>
            </button>
        </div>
    </div>

    <div class="form-options">
        <label class="remember-me">
            <input type="checkbox" name="remember">
            <span>{{ __('app.remember_me') }}</span>
        </label>
    </div>

    <button class="login-btn" type="submit">
        <i class="fas fa-sign-in-alt"></i>
        {{ __('app.login') }}
    </button>
</form>

<script>
function togglePassword() {
    var input = document.getElementById('password');
    var icon = document.getElementById('toggleIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endsection
