@extends('layouts.admin.add')

@section('title') {{ __('app.settings') }} @endsection

@section('content')
<div class="row mt-3 mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1" style="background:none;padding:0;">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="#">{{ __('app.system') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('app.settings') }}</li>
                    </ol>
                </nav>
                <h4 class="page-title mb-0">{{ __('app.settings_center') }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-3 col-md-4">
        <div class="card">
            <div class="card-body" style="padding:0 !important;">
                <div class="settings-nav">
                    <a href="#general" class="settings-nav-item active" data-toggle="tab">
                        <i class="fas fa-cog"></i>
                        <span>{{ __('app.general_settings') }}</span>
                    </a>
                    <a href="#appearance" class="settings-nav-item" data-toggle="tab">
                        <i class="fas fa-palette"></i>
                        <span>{{ __('app.appearance') }}</span>
                    </a>
                    <a href="#security" class="settings-nav-item" data-toggle="tab">
                        <i class="fas fa-shield-alt"></i>
                        <span>{{ __('app.security') }}</span>
                    </a>
                    <a href="#profile" class="settings-nav-item" data-toggle="tab">
                        <i class="fas fa-user"></i>
                        <span>{{ __('app.my_profile') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-9 col-md-8">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="general">
                <div class="card">
                    <div class="card-body">
                        <h5 class="header-title mb-3">{{ __('app.general_settings') }}</h5>
                        <div class="alert alert-info" style="margin-bottom:16px;">
                            {{ __('app.configured_in_env') }}
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.site_name') }}</label>
                            <input type="text" class="form-control" value="{{ env('APP_NAME') }}" readonly>
                            <small class="form-text text-muted">{{ __('app.configured_in_env') }}</small>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.default_language') }}</label>
                            <select class="form-control" disabled>
                                <option selected>{{ app()->getLocale() == 'ar' ? 'العربية' : 'English' }}</option>
                            </select>
                            <small class="form-text text-muted">{{ __('app.language_set_by_url') }}</small>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.timezone') }}</label>
                            <input type="text" class="form-control" value="{{ config('app.timezone') }}" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.currency') }}</label>
                            <input type="text" class="form-control" value="JOD" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="appearance">
                <div class="card">
                    <div class="card-body">
                        <h5 class="header-title mb-3">{{ __('app.appearance') }}</h5>
                        <div class="form-group mb-3">
                            <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.admin_theme') }}</label>
                            <div class="d-flex" style="gap:12px;">
                                <div class="theme-option active" style="cursor:pointer;padding:16px 24px;border-radius:var(--admin-radius-sm);border:2px solid var(--admin-primary);background:var(--admin-primary-bg);text-align:center;">
                                    <i class="fas fa-sun" style="font-size:24px;color:var(--admin-primary);display:block;margin-bottom:8px;"></i>
                                    <span style="font-size:13px;font-weight:600;">{{ __('app.light') }}</span>
                                </div>
                                <div class="theme-option" style="cursor:pointer;padding:16px 24px;border-radius:var(--admin-radius-sm);border:2px solid var(--admin-gray-200);text-align:center;opacity:0.5;">
                                    <i class="fas fa-moon" style="font-size:24px;color:var(--admin-gray-500);display:block;margin-bottom:8px;"></i>
                                    <span style="font-size:13px;font-weight:600;">{{ __('app.dark') }}</span>
                                    <div><small class="text-muted">{{ __('app.coming_soon') }}</small></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.sidebar_style') }}</label>
                            <select class="form-control">
                                <option>{{ __('app.expanded') }}</option>
                                <option>{{ __('app.compact') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="security">
                <div class="card">
                    <div class="card-body">
                        <h5 class="header-title mb-3">{{ __('app.security_settings') }}</h5>
                        <div class="form-group mb-3">
                            <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.current_password') }}</label>
                            <input type="password" class="form-control" placeholder="********">
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.new_password') }}</label>
                            <input type="password" class="form-control" placeholder="{{ __('app.enter_new_password') }}">
                            <small class="form-text text-muted">{{ __('app.password_requirements') }}</small>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.confirm_password') }}</label>
                            <input type="password" class="form-control" placeholder="{{ __('app.confirm_new_password') }}">
                        </div>
                        <button class="btn" style="background:var(--admin-primary);color:#fff;" disabled>{{ __('app.update_password') }}</button>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="profile">
                <div class="card">
                    <div class="card-body">
                        <h5 class="header-title mb-3">{{ __('app.my_profile') }}</h5>
                        <div class="d-flex align-items-center mb-4" style="gap:16px;">
                            <div style="width:64px;height:64px;border-radius:50%;background:var(--admin-primary);display:flex;align-items:center;justify-content:center;color:#fff;font-size:24px;font-weight:700;">
                                {{ strtoupper(substr(Auth::user()->username ?? 'A', 0, 1)) }}
                            </div>
                            <div>
                                <h5 class="mb-0">{{ Auth::user()->username }}</h5>
                                <p class="text-muted mb-0">{{ Auth::user()->email ?? 'admin' }}</p>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.username') }}</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->username }}" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.email') }}</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->email ?? '' }}" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.role') }}</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->role ?? 'Admin' }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('jsCode')
<script>
document.querySelectorAll('.settings-nav-item').forEach(function(item) {
    item.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelectorAll('.settings-nav-item').forEach(function(el) { el.classList.remove('active'); });
        this.classList.add('active');
        var target = this.getAttribute('href');
        document.querySelectorAll('.tab-pane').forEach(function(pane) {
            pane.classList.remove('show', 'active');
        });
        var targetPane = document.querySelector(target);
        if (targetPane) {
            targetPane.classList.add('show', 'active');
        }
    });
});
</script>
@endsection
