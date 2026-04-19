@extends('layouts.admin.add')
@section('title'){{ __('app.navigation_visibility') }} @endsection

@section('content')

@include('includes.admin.header', ['label_name' => __('app.navigation_visibility')])

<style>
    .nav-vis-wrap { max-width: 1100px; margin: 0 auto; }
    .nav-vis-intro {
        background: linear-gradient(135deg, rgba(19,88,93,0.06), rgba(254,205,15,0.06));
        border: 1px solid rgba(19,88,93,0.12);
        border-radius: 14px;
        padding: 18px 22px;
        margin-bottom: 22px;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .nav-vis-intro .ico {
        width: 46px; height: 46px;
        border-radius: 12px;
        background: #13585D;
        color: #FECD0F;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .nav-vis-intro h5 { margin: 0; font-weight: 700; color: #13585D; font-size: 1rem; }
    .nav-vis-intro p { margin: 2px 0 0; color: #6c7a89; font-size: 0.85rem; }

    .nav-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 14px;
    }
    .nav-item-card {
        background: #fff;
        border: 1px solid #e6e9ee;
        border-radius: 12px;
        padding: 16px 18px;
        transition: all .2s ease;
        position: relative;
    }
    .nav-item-card:hover {
        border-color: #13585D;
        box-shadow: 0 4px 14px rgba(19,88,93,0.08);
        transform: translateY(-2px);
    }
    .nav-item-card.is-hidden { opacity: .65; background: #f8f9fb; }
    .nav-item-card .nav-key {
        font-family: monospace;
        font-size: 0.72rem;
        background: rgba(19,88,93,0.08);
        color: #13585D;
        padding: 3px 8px;
        border-radius: 6px;
        display: inline-block;
        margin-bottom: 10px;
    }
    .nav-item-card .nav-labels { margin-bottom: 14px; }
    .nav-item-card .nav-labels .lbl-en { font-weight: 600; color: #1f2937; font-size: 0.95rem; }
    .nav-item-card .nav-labels .lbl-ar { color: #6c7a89; font-size: 0.88rem; margin-top: 2px; }
    .nav-item-card .nav-controls {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding-top: 12px;
        border-top: 1px dashed #e6e9ee;
    }
    .nav-item-card .nav-controls .order-input {
        width: 70px;
        text-align: center;
    }
    .nav-item-card .form-switch .form-check-input {
        width: 2.6em;
        height: 1.4em;
        cursor: pointer;
    }
    .nav-item-card .form-switch .form-check-input:checked {
        background-color: #13585D;
        border-color: #13585D;
    }
    .nav-item-card .switch-label {
        font-size: 0.78rem;
        color: #6c7a89;
        margin-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}: 8px;
        font-weight: 500;
    }
    .nav-item-card .switch-label.active { color: #13585D; }

    .nav-actions {
        position: sticky;
        bottom: 16px;
        background: #fff;
        padding: 14px 18px;
        margin-top: 22px;
        border-radius: 12px;
        box-shadow: 0 -2px 14px rgba(0,0,0,0.06);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }
    .nav-actions .hint { color: #6c7a89; font-size: 0.85rem; }
    .nav-actions .btn-save {
        background: #13585D;
        border-color: #13585D;
        color: #fff;
        padding: 8px 24px;
        font-weight: 600;
    }
    .nav-actions .btn-save:hover { background: #0e4448; border-color: #0e4448; }
</style>

<div class="nav-vis-wrap">
    <div class="nav-vis-intro">
        <div class="ico"><i class="fas fa-eye"></i></div>
        <div>
            <h5>{{ __('app.navigation_visibility') }}</h5>
            <p>{{ __('app.nav_visibility_hint') ?? 'Toggle which pages appear in the public site navigation. Reorder by changing the order number.' }}</p>
        </div>
    </div>

    <form id="nav-settings-form" method="POST" action="{{ route('nav-settings.update') }}">
        @csrf
        @method('PUT')

        <div class="nav-grid">
            @foreach($items as $i => $item)
                <div class="nav-item-card {{ $item->visible ? '' : 'is-hidden' }}" data-card>
                    <span class="nav-key">{{ $item->page_key }}</span>
                    <div class="nav-labels">
                        <div class="lbl-en">{{ $item->label_en }}</div>
                        <div class="lbl-ar" dir="rtl">{{ $item->label_ar }}</div>
                    </div>
                    <div class="nav-controls">
                        <div class="d-flex align-items-center">
                            <input type="hidden" name="items[{{ $i }}][id]" value="{{ $item->id }}">
                            <input type="hidden" name="items[{{ $i }}][visible]" value="0">
                            <div class="form-check form-switch m-0 p-0 d-flex align-items-center">
                                <input class="form-check-input m-0" type="checkbox"
                                       id="vis_{{ $item->id }}"
                                       name="items[{{ $i }}][visible]" value="1"
                                       {{ $item->visible ? 'checked' : '' }}
                                       data-vis-toggle>
                                <label class="switch-label {{ $item->visible ? 'active' : '' }}" for="vis_{{ $item->id }}" data-switch-label>
                                    {{ $item->visible ? __('app.visible') : __('app.hidden') ?? 'Hidden' }}
                                </label>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <small class="text-muted">{{ __('app.order') }}</small>
                            <input type="number" class="form-control form-control-sm order-input"
                                   name="items[{{ $i }}][order]" value="{{ $item->order }}" min="0">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="nav-actions">
            <span class="hint"><i class="fas fa-info-circle me-1"></i> {{ __('app.nav_changes_live_hint') ?? 'Changes apply to the public site immediately after saving.' }}</span>
            <button type="submit" class="btn btn-save">
                <i class="fas fa-save me-1"></i> {{ __('app.save_changes') ?? __('app.save') }}
            </button>
        </div>
    </form>
</div>

<script>
$(function () {
    // Toggle visual hidden state + label live
    $('[data-vis-toggle]').on('change', function () {
        var $card = $(this).closest('[data-card]');
        var $label = $card.find('[data-switch-label]');
        if (this.checked) {
            $card.removeClass('is-hidden');
            $label.addClass('active').text(@json(__('app.visible')));
        } else {
            $card.addClass('is-hidden');
            $label.removeClass('active').text(@json(__('app.hidden') ?? 'Hidden'));
        }
    });

    // Confirm + show success alert after save
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

    $('#nav-settings-form').on('submit', function (e) {
        e.preventDefault();
        var form = this;
        Swal.fire({
            title: @json(__('app.save_changes') ?? __('app.save')),
            text: @json(__('app.confirm_save_nav') ?? 'Apply navigation visibility changes to the public site?'),
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#13585D',
            cancelButtonColor: '#888',
            confirmButtonText: @json(__('app.save') ?? 'Save'),
            cancelButtonText: @json(__('app.cancel') ?? 'Cancel')
        }).then(function (result) {
            if (result.isConfirmed) form.submit();
        });
    });
});
</script>

@endsection
