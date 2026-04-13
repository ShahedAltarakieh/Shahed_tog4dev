@extends('layouts.admin.show')

@section('title') {{ __('app.notifications') }} @endsection

@section('content')
<div class="row mt-3 mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1" style="background:none;padding:0;">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="#">{{ __('app.system') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('app.notifications') }}</li>
                    </ol>
                </nav>
                <h4 class="page-title mb-0">{{ __('app.notifications_center') }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-4 col-xl">
        <div class="card dashboard-kpi-card">
            <div class="card-body text-center" style="padding:16px !important;">
                <div class="kpi-icon danger mx-auto mb-2" style="width:40px;height:40px;font-size:16px;"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="kpi-value" style="font-size:22px;">{{ $failedPayments }}</div>
                <div class="kpi-label">{{ __('app.failed_payments') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-xl">
        <div class="card dashboard-kpi-card">
            <div class="card-body text-center" style="padding:16px !important;">
                <div class="kpi-icon warning mx-auto mb-2" style="width:40px;height:40px;font-size:16px;"><i class="fas fa-envelope"></i></div>
                <div class="kpi-value" style="font-size:22px;">{{ $pendingContacts }}</div>
                <div class="kpi-label">{{ __('app.unread_messages') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-xl">
        <div class="card dashboard-kpi-card">
            <div class="card-body text-center" style="padding:16px !important;">
                <div class="kpi-icon success mx-auto mb-2" style="width:40px;height:40px;font-size:16px;"><i class="fas fa-user-plus"></i></div>
                <div class="kpi-value" style="font-size:22px;">{{ $newUsers }}</div>
                <div class="kpi-label">{{ __('app.new_users') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-xl">
        <div class="card dashboard-kpi-card">
            <div class="card-body text-center" style="padding:16px !important;">
                <div class="kpi-icon info mx-auto mb-2" style="width:40px;height:40px;font-size:16px;"><i class="fas fa-newspaper"></i></div>
                <div class="kpi-value" style="font-size:22px;">{{ $newSubscribers }}</div>
                <div class="kpi-label">{{ __('app.subscribers') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-xl">
        <div class="card dashboard-kpi-card">
            <div class="card-body text-center" style="padding:16px !important;">
                <div class="kpi-icon warning mx-auto mb-2" style="width:40px;height:40px;font-size:16px;"><i class="fas fa-pause-circle"></i></div>
                <div class="kpi-value" style="font-size:22px;">{{ $inactiveSubscriptions }}</div>
                <div class="kpi-label">{{ __('app.cancelled') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="header-title mb-3">{{ __('app.alerts_and_notifications') }}</h5>

                @forelse($notifications as $notification)
                <div class="notification-item mb-3" style="display:flex;align-items:flex-start;gap:16px;padding:16px;border-radius:var(--admin-radius-sm);border:1px solid var(--admin-gray-200);background:var(--admin-gray-50);">
                    <div class="kpi-icon {{ $notification['color'] }}" style="flex-shrink:0;width:40px;height:40px;font-size:16px;">
                        <i class="{{ $notification['icon'] }}"></i>
                    </div>
                    <div style="flex:1;">
                        <h6 style="font-size:14px;font-weight:600;margin-bottom:4px;">{{ $notification['title'] }}</h6>
                        <p class="text-muted mb-2" style="font-size:13px;">{{ $notification['description'] }}</p>
                        <a href="{{ $notification['action_url'] }}" class="btn btn-sm" style="background:var(--admin-primary);color:#fff;font-size:12px;padding:4px 16px;border-radius:6px;">
                            {{ $notification['action_label'] }} <i class="fas fa-arrow-right ml-1" style="font-size:10px;"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <div class="empty-state-icon"><i class="fas fa-bell-slash"></i></div>
                    <h5>{{ __('app.no_notifications') }}</h5>
                    <p class="text-muted">{{ __('app.all_caught_up') }}</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
