@extends('layouts.admin.add')
@section('title'){{ __('app.navigation_visibility') }} @endsection

@section('content')

@include('includes.admin.header', ['label_name' => __('app.navigation_visibility')])

<div class="row">
    <div class="col-12">
        <div class="card" style="border-left:3px solid var(--admin-primary);">
            <div class="card-body" style="padding:14px 18px;">
                <div class="d-flex align-items-center" style="gap:12px;">
                    <div style="width:40px;height:40px;border-radius:10px;background:var(--admin-primary);color:var(--admin-accent);display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div>
                        <h6 class="mb-0" style="font-weight:600;color:var(--admin-primary);">{{ __('app.navigation_visibility') }}</h6>
                        <small class="text-muted">{{ __('app.nav_visibility_hint') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="nav-settings-form" method="POST" action="{{ route('nav-settings.update') }}">
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
                                    <th style="width:70px;">#</th>
                                    <th>{{ __('app.page_key') }}</th>
                                    <th>{{ __('app.title') }} (EN)</th>
                                    <th>{{ __('app.title') }} (AR)</th>
                                    <th class="text-center" style="width:140px;">{{ __('app.status') }}</th>
                                    <th style="width:110px;">{{ __('app.order') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $i => $item)
                                    <tr data-row>
                                        <td>
                                            <span style="font-size:13px;font-weight:600;color:var(--admin-gray-500);">{{ $i + 1 }}</span>
                                        </td>
                                        <td>
                                            <code style="font-size:0.78rem;background:rgba(19,88,93,0.08);color:#13585D;padding:3px 8px;border-radius:6px;">{{ $item->page_key }}</code>
                                        </td>
                                        <td>
                                            <strong style="font-size:0.9rem;">{{ $item->label_en }}</strong>
                                        </td>
                                        <td dir="rtl" lang="ar">
                                            <span style="font-size:0.9rem;">{{ $item->label_ar }}</span>
                                        </td>
                                        <td class="text-center">
                                            <input type="hidden" name="items[{{ $i }}][id]" value="{{ $item->id }}">
                                            <input type="hidden" name="items[{{ $i }}][visible]" value="0">
                                            <div class="custom-control custom-switch d-inline-flex align-items-center" style="gap:8px;">
                                                <input type="checkbox"
                                                       class="custom-control-input"
                                                       id="vis_{{ $item->id }}"
                                                       name="items[{{ $i }}][visible]"
                                                       value="1"
                                                       {{ $item->visible ? 'checked' : '' }}
                                                       data-vis-toggle>
                                                <label class="custom-control-label" for="vis_{{ $item->id }}"></label>
                                                <span class="status-text" data-status-text style="font-size:0.78rem;font-weight:600;color:{{ $item->visible ? '#13585D' : '#999' }};">
                                                    {{ $item->visible ? __('app.visible') : __('app.hidden') }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number"
                                                   class="form-control form-control-sm"
                                                   name="items[{{ $i }}][order]"
                                                   value="{{ $item->order }}"
                                                   min="0"
                                                   style="max-width:80px;text-align:center;">
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
                    <small class="text-muted"><i class="fas fa-info-circle me-1"></i> {{ __('app.nav_changes_live_hint') }}</small>
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
    // Live label toggle
    $('[data-vis-toggle]').on('change', function () {
        var $row = $(this).closest('[data-row]');
        var $txt = $row.find('[data-status-text]');
        if (this.checked) {
            $txt.text(@json(__('app.visible'))).css('color', '#13585D');
            $row.css('opacity', '1');
        } else {
            $txt.text(@json(__('app.hidden'))).css('color', '#999');
            $row.css('opacity', '0.65');
        }
    });

    // Initial dim for hidden rows
    $('[data-vis-toggle]').each(function () {
        if (!this.checked) $(this).closest('[data-row]').css('opacity', '0.65');
    });

    // Success toast on flash
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

    // Confirm before submit
    $('#nav-settings-form').on('submit', function (e) {
        e.preventDefault();
        var form = this;
        Swal.fire({
            title: @json(__('app.save_changes')),
            text: @json(__('app.confirm_save_nav')),
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
