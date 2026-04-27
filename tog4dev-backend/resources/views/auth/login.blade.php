@extends('layouts.admin.login')

@section('content')
<div class="card bg-pattern">

    <div class="card-body p-4">

        <div class="text-center w-75 m-auto">
            <div class="auth-logo">

                <a href="javascript:void(0)" class="logo  text-center">
                    <span class="logo-lg" style="background:unset;">
                        <img src="{{ asset('img/logo.png') }}" alt="" height="100">
                    </span>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="emailaddress" class="form-label">{{ __('app.email') }}</label>
                <input class="form-control" type="email" name="email" id="emailaddress" value="{{ old('email') }}" placeholder="{{ __('app.email') }}">
                @error('email')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">{{ __('app.password') }}</label>
                <div class="input-group input-group-merge">
                    <input type="password" name="password" id="password" class="form-control" placeholder="{{ __('app.password') }}">
                    <div class="input-group-text" data-password="false">
                        <span class="password-eye"></span>
                    </div>
                </div>
                @error('password')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>


            <div class="text-center d-grid">
                <button class="btn btn-primary" type="submit"> {{ __('app.login') }} </button>
            </div>

        </form>

    </div> <!-- end card-body -->
</div>
<!-- end card -->
@endsection
