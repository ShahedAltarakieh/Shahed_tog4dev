@extends('layouts.admin.show')

@section('title') {{ __('app.activity_logs') }} @endsection

@section('content')
<div class="row mt-3 mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1" style="background:none;padding:0;">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="#">{{ __('app.system') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('app.activity_logs') }}</li>
                    </ol>
                </nav>
                <h4 class="page-title mb-0">{{ __('app.activity_logs') }}</h4>
            </div>
            <div class="d-flex align-items-center" style="gap:8px;">
                <button class="btn btn-outline-secondary btn-sm" onclick="window.location.reload();">
                    <i class="fas fa-sync-alt mr-1"></i> {{ __('app.refresh') }}
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-kpi-card">
            <div class="card-body">
                <div class="d-flex align-items-center" style="gap:12px;">
                    <div class="kpi-icon primary"><i class="fas fa-history"></i></div>
                    <div>
                        <div class="kpi-value" style="font-size:24px;">{{ $activities->count() }}</div>
                        <div class="kpi-label">{{ __('app.recent_activities') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-kpi-card">
            <div class="card-body">
                <div class="d-flex align-items-center" style="gap:12px;">
                    <div class="kpi-icon success"><i class="fas fa-credit-card"></i></div>
                    <div>
                        <div class="kpi-value" style="font-size:24px;">{{ $activities->where('type', 'payment')->count() }}</div>
                        <div class="kpi-label">{{ __('app.payments') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-kpi-card">
            <div class="card-body">
                <div class="d-flex align-items-center" style="gap:12px;">
                    <div class="kpi-icon info"><i class="fas fa-user-plus"></i></div>
                    <div>
                        <div class="kpi-value" style="font-size:24px;">{{ $activities->where('type', 'user')->count() }}</div>
                        <div class="kpi-label">{{ __('app.new_users') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-kpi-card">
            <div class="card-body">
                <div class="d-flex align-items-center" style="gap:12px;">
                    <div class="kpi-icon warning"><i class="fas fa-sync-alt"></i></div>
                    <div>
                        <div class="kpi-value" style="font-size:24px;">{{ $activities->where('type', 'subscription')->count() }}</div>
                        <div class="kpi-label">{{ __('app.subscriptions') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="header-title mb-0">{{ __('app.recent_activity_feed') }}</h5>
                    <div class="d-flex align-items-center" style="gap:8px;">
                        <select id="activityFilter" class="form-control form-control-sm" style="width:auto;" onchange="filterActivities(this.value)">
                            <option value="all">{{ __('app.all') }}</option>
                            <option value="payment">{{ __('app.payments') }}</option>
                            <option value="user">{{ __('app.users') }}</option>
                            <option value="subscription">{{ __('app.subscriptions') }}</option>
                        </select>
                    </div>
                </div>

                <div class="activity-timeline">
                    @forelse($activities as $activity)
                    <div class="activity-item" data-type="{{ $activity['type'] }}">
                        <div class="activity-icon {{ $activity['color'] }}">
                            <i class="{{ $activity['icon'] }}"></i>
                        </div>
                        <div class="activity-content">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1" style="font-size:14px;font-weight:600;">{{ $activity['title'] }}</h6>
                                    <p class="text-muted mb-0" style="font-size:13px;">{{ $activity['description'] }}</p>
                                </div>
                                <span class="text-muted" style="font-size:12px;white-space:nowrap;">{{ \Carbon\Carbon::parse($activity['time'])->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state">
                        <div class="empty-state-icon"><i class="fas fa-history"></i></div>
                        <h5>{{ __('app.no_recent_activity') }}</h5>
                        <p class="text-muted">{{ __('app.activity_will_appear_here') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('jsCode')
<script>
function filterActivities(type) {
    document.querySelectorAll('.activity-item').forEach(function(item) {
        if (type === 'all' || item.dataset.type === type) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
}
</script>
@endsection
