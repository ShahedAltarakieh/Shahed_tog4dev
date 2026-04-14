@extends('layouts.admin.add')

@section('title') {{ __('app.add_announcement') }} @endsection

@section('content')
<div class="row mt-3 mb-3">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1" style="background:none;padding:0;">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('announcements.index') }}">{{ __('app.announcements') }}</a></li>
                <li class="breadcrumb-item active">{{ __('app.add_announcement') }}</li>
            </ol>
        </nav>
        <h4 class="page-title mb-0">{{ __('app.add_announcement') }}</h4>
    </div>
</div>

<form action="{{ route('announcements.store') }}" method="POST">
    @csrf

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="header-title mb-3">{{ __('app.announcement_content') }}</h5>

                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.source') }}</label>
                        <select id="source_type" name="source_type" class="form-control">
                            <option value="manual" {{ old('source_type', 'manual') == 'manual' ? 'selected' : '' }}>{{ __('app.manual_announcement') }}</option>
                            <option value="news" {{ old('source_type') == 'news' ? 'selected' : '' }}>{{ __('app.linked_news') }}</option>
                        </select>
                    </div>

                    <div id="news-link-section" style="display:none;">
                        <div class="form-group mb-3">
                            <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.select_news_article') }}</label>
                            <select id="news_id" name="news_id" class="form-control">
                                <option value="">— {{ __('app.choose') }} —</option>
                                @foreach($newsItems as $news)
                                    <option value="{{ $news->id }}"
                                        data-title="{{ $news->getLocalizationTitle() }}"
                                        data-excerpt="{{ Str::limit(strip_tags($news->getLocalizationBody()), 200) }}"
                                        data-slug-en="{{ $news->slug_en ?? $news->slug }}"
                                        data-slug-ar="{{ $news->slug }}"
                                        {{ old('news_id') == $news->id ? 'selected' : '' }}>
                                        {{ $news->getLocalizationTitle() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.title') }} <small class="text-muted">({{ __('app.optional') }})</small></label>
                        <input type="text" id="ann-title" name="title" class="form-control" value="{{ old('title') }}" placeholder="{{ __('app.announcement_title_placeholder') }}">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.text') }} <span class="text-danger">*</span></label>
                        <textarea id="ann-text" name="text" class="form-control" rows="3" required placeholder="{{ __('app.announcement_text_placeholder') }}">{{ old('text') }}</textarea>
                        @error('text') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.short_text') }} <small class="text-muted">({{ __('app.for_mobile') }})</small></label>
                        <input type="text" name="short_text" class="form-control" value="{{ old('short_text') }}" placeholder="{{ __('app.short_text_placeholder') }}" maxlength="200">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.link') }} <small class="text-muted">({{ __('app.optional') }})</small></label>
                                <input type="text" id="ann-link" name="link" class="form-control" value="{{ old('link') }}" placeholder="/en/news/slug or https://...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.cta_text') }} <small class="text-muted">({{ __('app.optional') }})</small></label>
                                <input type="text" name="cta_text" class="form-control" value="{{ old('cta_text') }}" placeholder="{{ __('app.explore_now') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" style="border-left:3px solid var(--admin-primary, #13585D);">
                <div class="card-body" style="padding:16px 20px;">
                    <h6 class="mb-2" style="font-weight:600;"><i class="fas fa-eye me-1"></i> {{ __('app.live_preview') }}</h6>
                    <div id="live-preview" style="background:#13585D;color:#fff;padding:10px 24px;border-radius:8px;display:flex;align-items:center;justify-content:center;gap:12px;font-size:14px;min-height:44px;flex-wrap:wrap;">
                        <span id="preview-badge" class="badge" style="font-size:11px;padding:3px 8px;background:#ef4444;">LIVE</span>
                        <span id="preview-text" style="flex:1;text-align:center;">{{ __('app.preview_sample_text') }}</span>
                        <a href="#" id="preview-cta" style="color:#FECD0F;font-weight:600;font-size:13px;text-decoration:none;display:none;">{{ __('app.explore_now') }} →</a>
                    </div>
                    <div class="mt-2 d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary preview-device active" data-mode="desktop"><i class="fas fa-desktop me-1"></i> {{ __('app.desktop') }}</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary preview-device" data-mode="mobile"><i class="fas fa-mobile-alt me-1"></i> {{ __('app.mobile') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="header-title mb-3">{{ __('app.display_settings') }}</h5>

                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.badge_type') }}</label>
                        <select id="badge_type" name="badge_type" class="form-control">
                            <option value="LIVE" {{ old('badge_type') == 'LIVE' ? 'selected' : '' }}>🔴 LIVE</option>
                            <option value="INFO" {{ old('badge_type', 'INFO') == 'INFO' ? 'selected' : '' }}>ℹ️ INFO</option>
                            <option value="ALERT" {{ old('badge_type') == 'ALERT' ? 'selected' : '' }}>⚠️ ALERT</option>
                            <option value="NEW" {{ old('badge_type') == 'NEW' ? 'selected' : '' }}>✨ NEW</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.target_view') }}</label>
                        <select name="target_view" class="form-control">
                            <option value="both" {{ old('target_view', 'both') == 'both' ? 'selected' : '' }}>{{ __('app.all_devices') }}</option>
                            <option value="desktop" {{ old('target_view') == 'desktop' ? 'selected' : '' }}>{{ __('app.desktop_only') }}</option>
                            <option value="mobile" {{ old('target_view') == 'mobile' ? 'selected' : '' }}>{{ __('app.mobile_only') }}</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.start_date') }}</label>
                        <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date') }}">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.end_date') }}</label>
                        <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date') }}">
                    </div>

                    <div class="form-group mb-3">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active" style="font-weight:600;font-size:13px;">{{ __('app.active') }}</label>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block" style="width:100%;">
                <i class="fas fa-plus me-1"></i> {{ __('app.save') }}
            </button>
        </div>
    </div>
</form>
@endsection

@section('jsCode')
<script>
var badgeColors = { LIVE: '#ef4444', INFO: '#3b82f6', ALERT: '#f59e0b', NEW: '#10b981' };

var sourceSelect = document.getElementById('source_type');
var newsSelect = document.getElementById('news_id');
var newsSection = document.getElementById('news-link-section');
var titleField = document.getElementById('ann-title');
var textField = document.getElementById('ann-text');
var linkField = document.getElementById('ann-link');
var badgeSelect = document.getElementById('badge_type');

function toggleSourceType() {
    var isNews = sourceSelect.value === 'news';
    newsSection.style.display = isNews ? '' : 'none';
    if (!isNews) {
        newsSelect.value = '';
    }
}
sourceSelect.addEventListener('change', toggleSourceType);
toggleSourceType();

newsSelect.addEventListener('change', function() {
    var opt = this.options[this.selectedIndex];
    if (this.value) {
        titleField.value = opt.dataset.title || '';
        textField.value = opt.dataset.excerpt || '';
        var slugEn = opt.dataset.slugEn || '';
        var slugAr = opt.dataset.slugAr || '';
        linkField.value = slugEn ? '/en/news/' + slugEn : '/ar/news/' + slugAr;
        updatePreview();
    }
});

function updatePreview() {
    var badge = badgeSelect.value;
    var text = textField.value || '{{ __("app.preview_sample_text") }}';
    var cta = document.querySelector('[name="cta_text"]').value;

    document.getElementById('preview-badge').textContent = badge;
    document.getElementById('preview-badge').style.background = badgeColors[badge] || '#3b82f6';
    document.getElementById('preview-text').textContent = text.length > 100 ? text.substring(0, 100) + '...' : text;

    var ctaEl = document.getElementById('preview-cta');
    if (cta) {
        ctaEl.textContent = cta + ' →';
        ctaEl.style.display = '';
    } else {
        ctaEl.style.display = 'none';
    }
}

textField.addEventListener('input', updatePreview);
badgeSelect.addEventListener('change', updatePreview);
document.querySelector('[name="cta_text"]').addEventListener('input', updatePreview);

document.querySelectorAll('.preview-device').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.preview-device').forEach(function(b) { b.classList.remove('active'); });
        this.classList.add('active');
        var preview = document.getElementById('live-preview');
        if (this.dataset.mode === 'mobile') {
            preview.style.maxWidth = '375px';
            preview.style.margin = '0 auto';
            preview.style.fontSize = '12px';
        } else {
            preview.style.maxWidth = '';
            preview.style.margin = '';
            preview.style.fontSize = '14px';
        }
    });
});

updatePreview();
</script>
@endsection
