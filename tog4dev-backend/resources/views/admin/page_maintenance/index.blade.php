@extends('layouts.admin.add')
@section('title'){{ __('app.page_maintenance') }} @endsection

@section('content')

@include('includes.admin.header', ['label_name' => __('app.page_maintenance')])

<div class="row">
    <div class="col-12">
        <div class="card" style="border-left:3px solid var(--admin-primary);">
            <div class="card-body" style="padding:14px 18px;">
                <div class="d-flex align-items-center" style="gap:12px;">
                    <div style="width:40px;height:40px;border-radius:10px;background:var(--admin-primary);color:var(--admin-accent);display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div>
                        <h6 class="mb-0" style="font-weight:600;color:var(--admin-primary);">{{ __('app.page_maintenance') }}</h6>
                        <small class="text-muted">{{ __('app.page_maintenance_hint') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="page-maintenance-form" method="POST" action="{{ route('page-maintenance.update') }}">
    @csrf
    @method('PUT')

    <div class="row mt-2">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th style="width:50px;">#</th>
                                    <th>{{ __('app.page') }}</th>
                                    <th class="text-center" style="width:160px;">{{ __('app.under_update') }}</th>
                                    <th>{{ __('app.message') }} (EN)</th>
                                    <th>{{ __('app.message') }} (AR)</th>
                                    <th style="width:170px;">{{ __('app.starts_at') }}</th>
                                    <th style="width:170px;">{{ __('app.ends_at') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $i => $item)
                                    <tr data-row>
                                        <td><span style="font-size:13px;font-weight:600;color:var(--admin-gray-500);">{{ $i + 1 }}</span></td>
                                        <td>
                                            <div><strong style="font-size:0.92rem;">{{ $item->label_en }}</strong></div>
                                            <div dir="rtl" lang="ar" style="font-size:0.85rem;color:#555;">{{ $item->label_ar }}</div>
                                            <code style="font-size:0.72rem;background:rgba(19,88,93,0.08);color:#13585D;padding:2px 6px;border-radius:5px;">{{ $item->page_key }}</code>
                                        </td>
                                        <td class="text-center">
                                            <input type="hidden" name="items[{{ $i }}][id]" value="{{ $item->id }}">
                                            <input type="hidden" name="items[{{ $i }}][is_under_update]" value="0">
                                            <div class="custom-control custom-switch d-inline-flex align-items-center" style="gap:8px;">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="upd_{{ $item->id }}"
                                                       name="items[{{ $i }}][is_under_update]"
                                                       value="1"
                                                       {{ $item->is_under_update ? 'checked' : '' }}
                                                       data-upd-toggle>
                                                <label class="custom-control-label" for="upd_{{ $item->id }}"></label>
                                                <span class="status-text" data-status-text style="font-size:0.78rem;font-weight:600;color:{{ $item->is_under_update ? '#c0392b' : '#999' }};">
                                                    {{ $item->is_under_update ? __('app.on') : __('app.off') }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <textarea class="form-control form-control-sm" name="items[{{ $i }}][message_en]" rows="2" placeholder="{{ __('app.maintenance_message_placeholder_en') }}">{{ $item->message_en }}</textarea>
                                        </td>
                                        <td>
                                            <textarea class="form-control form-control-sm" dir="rtl" lang="ar" name="items[{{ $i }}][message_ar]" rows="2" placeholder="{{ __('app.maintenance_message_placeholder_ar') }}">{{ $item->message_ar }}</textarea>
                                        </td>
                                        <td>
                                            <input type="datetime-local" class="form-control form-control-sm"
                                                   name="items[{{ $i }}][starts_at]"
                                                   value="{{ $item->starts_at ? $item->starts_at->format('Y-m-d\TH:i') : '' }}">
                                        </td>
                                        <td>
                                            <input type="datetime-local" class="form-control form-control-sm"
                                                   name="items[{{ $i }}][ends_at]"
                                                   value="{{ $item->ends_at ? $item->ends_at->format('Y-m-d\TH:i') : '' }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center" style="padding:14px 20px;">
                    <small class="text-muted"><i class="fas fa-info-circle me-1"></i> {{ __('app.page_maintenance_changes_live_hint') }}</small>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> {{ __('app.save_changes') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@section('jsCode')
<script>
$(function () {
    $('[data-upd-toggle]').on('change', function () {
        var $row = $(this).closest('[data-row]');
        var $txt = $row.find('[data-status-text]');
        if (this.checked) {
            $txt.text(@json(__('app.on'))).css('color', '#c0392b');
            $row.css('background', 'rgba(192,57,43,0.05)');
        } else {
            $txt.text(@json(__('app.off'))).css('color', '#999');
            $row.css('background', '');
        }
    });

    $('[data-upd-toggle]').each(function () {
        if (this.checked) $(this).closest('[data-row]').css('background', 'rgba(192,57,43,0.05)');
    });

    @if(session('success'))
        Swal.fire({
            toast: true,
            position: '{{ app()->getLocale() === "ar" ? "top-start" : "top-end" }}',
            icon: 'success',
            title: @json(session('success')),
            showConfirmButton: false,
            timer: 2800,
            timerProgressBar: true
        });
    @endif

    $('#page-maintenance-form').on('submit', function (e) {
        e.preventDefault();
        var form = this;
        Swal.fire({
            title: @json(__('app.save_changes')),
            text: @json(__('app.confirm_save_page_maintenance')),
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#13585D',
            cancelButtonColor: '#888',
            confirmButtonText: @json(__('app.save')),
            cancelButtonText: @json(__('app.cancel'))
        }).then(function (result) {
            if (result.isConfirmed) form.submit();
        });
    });
});
</script>
@endsection
