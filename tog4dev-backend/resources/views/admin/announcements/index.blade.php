@extends('layouts.admin.add')

@section('title') {{ __('app.announcements') }} @endsection

@section('content')
@include('includes.admin.header', [
    'label_name' => __('app.announcement_management'),
    'add_button' => route('announcements.create')
])

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="announcements-table">
                        <thead>
                            <tr>
                                <th style="width:40px;">#</th>
                                <th>{{ __('app.text') }}</th>
                                <th style="width:80px;">{{ __('app.type') }}</th>
                                <th style="width:80px;">{{ __('app.source') }}</th>
                                <th style="width:90px;">{{ __('app.target') }}</th>
                                <th style="width:80px;">{{ __('app.status') }}</th>
                                <th style="width:60px;">{{ __('app.order') }}</th>
                                <th style="width:140px;">{{ __('app.dates') }}</th>
                                <th style="width:100px;">{{ __('app.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody id="sortable-announcements">
                            @forelse($announcements as $item)
                            <tr data-id="{{ $item->id }}">
                                <td>
                                    <i class="fas fa-grip-vertical" style="cursor:grab;color:var(--admin-gray-400);"></i>
                                </td>
                                <td>
                                    <div>
                                        @if($item->title || $item->title_ar)
                                        <strong style="font-size:12px;color:var(--admin-gray-500);display:block;">
                                            @if($item->title)<span title="EN">{{ $item->title }}</span>@endif
                                            @if($item->title && $item->title_ar) · @endif
                                            @if($item->title_ar)<span dir="rtl" lang="ar" title="AR">{{ $item->title_ar }}</span>@endif
                                        </strong>
                                        @endif
                                        @if($item->text)
                                        <span style="font-size:13px;display:block;">
                                            <span class="badge badge-soft-primary" style="font-size:9px;margin-right:4px;">EN</span>{{ Str::limit($item->text, 70) }}
                                        </span>
                                        @endif
                                        @if($item->text_ar)
                                        <span style="font-size:13px;display:block;" dir="rtl" lang="ar">
                                            <span class="badge badge-soft-warning" style="font-size:9px;margin-left:4px;">AR</span>{{ Str::limit($item->text_ar, 70) }}
                                        </span>
                                        @endif
                                        @if($item->link)
                                        <small class="text-muted"><i class="fas fa-link"></i> {{ Str::limit($item->link, 30) }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $badgeColors = ['LIVE' => 'danger', 'INFO' => 'info', 'ALERT' => 'warning', 'NEW' => 'success'];
                                    @endphp
                                    <span class="badge badge-soft-{{ $badgeColors[$item->badge_type] ?? 'info' }}">{{ $item->badge_type }}</span>
                                </td>
                                <td>
                                    <span class="text-muted" style="font-size:12px;">{{ ucfirst($item->source_type) }}</span>
                                </td>
                                <td>
                                    <span class="text-muted" style="font-size:12px;">
                                        @if($item->target_view == 'desktop')
                                            <i class="fas fa-desktop"></i> {{ __('app.desktop') }}
                                        @elseif($item->target_view == 'mobile')
                                            <i class="fas fa-mobile-alt"></i> {{ __('app.mobile') }}
                                        @else
                                            <i class="fas fa-globe"></i> {{ __('app.all') }}
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input status-toggle" id="status-{{ $item->id }}" data-id="{{ $item->id }}" {{ $item->is_active ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="status-{{ $item->id }}"></label>
                                    </div>
                                </td>
                                <td style="text-align:center;">
                                    <span style="font-size:13px;font-weight:600;">{{ $item->order_no }}</span>
                                </td>
                                <td>
                                    @if($item->start_date || $item->end_date)
                                    <small class="text-muted" style="font-size:11px;">
                                        @if($item->start_date){{ $item->start_date->format('M d') }}@endif
                                        @if($item->start_date && $item->end_date) — @endif
                                        @if($item->end_date){{ $item->end_date->format('M d') }}@endif
                                    </small>
                                    @else
                                    <small class="text-muted">{{ __('app.always') }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex" style="gap:4px;">
                                        <a href="{{ route('announcements.edit', $item->id) }}" class="btn btn-sm btn-info" title="{{ __('app.edit') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $item->id }}" title="{{ __('app.delete') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-bullhorn" style="font-size:32px;color:var(--admin-gray-300);margin-bottom:12px;display:block;"></i>
                                        <p class="text-muted">{{ __('app.no_announcements') }}</p>
                                        <a href="{{ route('announcements.create') }}" class="btn btn-primary btn-sm">{{ __('app.add_announcement') }}</a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-12">
        <div class="card" style="border-left:3px solid var(--admin-primary);">
            <div class="card-body" style="padding:16px 20px;">
                <h6 class="mb-2" style="font-weight:600;">{{ __('app.announcement_preview') }}</h6>
                <div id="announcement-preview" style="background:var(--admin-primary);color:#fff;padding:10px 24px;border-radius:var(--admin-radius-sm);display:flex;align-items:center;justify-content:center;gap:12px;font-size:14px;">
                    <span class="badge" style="background:rgba(255,255,255,0.2);font-size:11px;padding:3px 8px;">LIVE</span>
                    <span>{{ __('app.preview_sample_text') }}</span>
                    <a href="#" style="color:var(--admin-accent);font-weight:600;font-size:13px;text-decoration:none;">{{ __('app.explore_now') }} →</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('jsCode')
<script>
document.querySelectorAll('.status-toggle').forEach(function(toggle) {
    toggle.addEventListener('change', function() {
        var id = this.dataset.id;
        fetch('/{{ app()->getLocale() }}/announcements/change-status/' + id, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        }).then(r => r.json()).then(data => {
            if (window.AdminToast) {
                window.AdminToast.show(data.success ? 'Status updated' : 'Error', data.success ? 'success' : 'error');
            }
        });
    });
});

document.querySelectorAll('.delete-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var id = this.dataset.id;
        var row = this.closest('tr');
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '{{ __("app.are_you_sure") }}',
                text: '{{ __("app.confirm_delete") }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: '{{ __("app.delete") }}',
                cancelButtonText: '{{ __("app.cancel") }}'
            }).then(function(result) {
                if (result.isConfirmed) { deleteAnnouncement(id, row); }
            });
        } else {
            if (confirm('{{ __("app.are_you_sure") }}')) { deleteAnnouncement(id, row); }
        }
    });
});

function deleteAnnouncement(id, row) {
    fetch('/{{ app()->getLocale() }}/announcements/' + id, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    }).then(r => r.json()).then(function(data) {
        if (data.success) {
            row.remove();
            if (window.AdminToast) window.AdminToast.show('Deleted', 'success');
        }
    });
}
</script>
@endsection
