@php
    $a = $announcement; // null on create
    $val = function($field, $default = '') use ($a) {
        return old($field, $a ? $a->{$field} : $default);
    };
@endphp

<div class="row">
    <div class="col-xl-8">
        {{-- SHARED: Source / Linked News --}}
        <div class="card">
            <div class="card-body">
                <h5 class="header-title mb-3">{{ __('app.announcement_source') }}</h5>

                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.source') }}</label>
                    <select id="source_type" name="source_type" class="form-control">
                        <option value="manual" {{ $val('source_type', 'manual') == 'manual' ? 'selected' : '' }}>{{ __('app.manual_announcement') }}</option>
                        <option value="news" {{ $val('source_type') == 'news' ? 'selected' : '' }}>{{ __('app.linked_news') }}</option>
                    </select>
                </div>

                <div id="news-link-section" style="display:none;">
                    <div class="form-group mb-0">
                        <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.select_news_article') }}</label>
                        <select id="news_id" name="news_id" class="form-control">
                            <option value="">— {{ __('app.choose') }} —</option>
                            @foreach($newsItems as $news)
                                <option value="{{ $news->id }}"
                                    data-title-en="{{ $news->title_en ?? $news->getLocalizationTitle() }}"
                                    data-title-ar="{{ $news->title ?? $news->getLocalizationTitle() }}"
                                    data-excerpt-en="{{ Str::limit(strip_tags($news->body_en ?? $news->getLocalizationBody()), 200) }}"
                                    data-excerpt-ar="{{ Str::limit(strip_tags($news->body ?? $news->getLocalizationBody()), 200) }}"
                                    data-slug-en="{{ $news->slug_en ?? $news->slug }}"
                                    data-slug-ar="{{ $news->slug }}"
                                    {{ $val('news_id') == $news->id ? 'selected' : '' }}>
                                    {{ $news->getLocalizationTitle() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- ENGLISH SECTION --}}
        <div class="card" style="border-top:3px solid var(--admin-primary, #13585D);">
            <div class="card-body">
                <h5 class="header-title mb-3" style="display:flex;align-items:center;gap:8px;">
                    <span style="background:var(--admin-primary,#13585D);color:#fff;padding:2px 10px;border-radius:6px;font-size:12px;font-weight:700;">EN</span>
                    {{ __('app.english_content') }}
                </h5>

                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.title') }} (EN) <small class="text-muted">({{ __('app.optional') }})</small></label>
                    <input type="text" id="ann-title-en" name="title" class="form-control" value="{{ $val('title') }}" placeholder="{{ __('app.announcement_title_placeholder') }}">
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.text') }} (EN) <span class="text-danger">*</span></label>
                    <textarea id="ann-text-en" name="text" class="form-control" rows="3" required placeholder="{{ __('app.announcement_text_placeholder') }}">{{ $val('text') }}</textarea>
                    @error('text') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.short_text') }} (EN) <small class="text-muted">({{ __('app.for_mobile') }} — {{ __('app.optional') }})</small></label>
                    <input type="text" name="short_text" class="form-control" value="{{ $val('short_text') }}" placeholder="{{ __('app.short_text_placeholder') }}" maxlength="200">
                </div>

                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.cta_text') }} (EN) <small class="text-muted">({{ __('app.optional') }})</small></label>
                    <input type="text" name="cta_text" class="form-control" value="{{ $val('cta_text') }}" placeholder="{{ __('app.explore_now') }}">
                </div>
            </div>
        </div>

        {{-- ARABIC SECTION (RTL) --}}
        <div class="card" style="border-top:3px solid var(--admin-accent, #FECD0F);" dir="rtl">
            <div class="card-body">
                <h5 class="header-title mb-3" style="display:flex;align-items:center;gap:8px;justify-content:flex-end;">
                    <span style="background:var(--admin-accent,#FECD0F);color:#13585D;padding:2px 10px;border-radius:6px;font-size:12px;font-weight:700;">AR</span>
                    {{ __('app.arabic_content') }}
                </h5>

                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.title') }} (AR) <small class="text-muted">({{ __('app.optional') }})</small></label>
                    <input type="text" id="ann-title-ar" name="title_ar" class="form-control" value="{{ $val('title_ar') }}" placeholder="عنوان اختياري" dir="rtl" lang="ar">
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.text') }} (AR) <span class="text-danger">*</span></label>
                    <textarea id="ann-text-ar" name="text_ar" class="form-control" rows="3" required placeholder="أدخل نص الإعلان..." dir="rtl" lang="ar">{{ $val('text_ar') }}</textarea>
                    @error('text_ar') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.short_text') }} (AR) <small class="text-muted">({{ __('app.for_mobile') }} — {{ __('app.optional') }})</small></label>
                    <input type="text" name="short_text_ar" class="form-control" value="{{ $val('short_text_ar') }}" placeholder="نص قصير" maxlength="200" dir="rtl" lang="ar">
                </div>

                <div class="form-group mb-0">
                    <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.cta_text') }} (AR) <small class="text-muted">({{ __('app.optional') }})</small></label>
                    <input type="text" name="cta_text_ar" class="form-control" value="{{ $val('cta_text_ar') }}" placeholder="استكشف الآن" dir="rtl" lang="ar">
                </div>
            </div>
        </div>

        {{-- LIVE PREVIEW --}}
        <div class="card" style="border-left:3px solid var(--admin-primary, #13585D);">
            <div class="card-body" style="padding:16px 20px;">
                <h6 class="mb-2" style="font-weight:600;display:flex;align-items:center;justify-content:space-between;">
                    <span><i class="fas fa-eye me-1"></i> {{ __('app.live_preview') }}</span>
                    <span>
                        <button type="button" class="btn btn-sm btn-outline-secondary preview-lang active" data-lang="en">EN</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary preview-lang" data-lang="ar">AR</button>
                    </span>
                </h6>
                <div id="live-preview" style="background:#13585D;color:#fff;padding:10px 24px;border-radius:8px;display:flex;align-items:center;justify-content:center;gap:12px;font-size:14px;min-height:44px;flex-wrap:wrap;">
                    <span id="preview-badge" class="badge" style="font-size:11px;padding:3px 8px;background:#3b82f6;">{{ $a->badge_type ?? 'INFO' }}</span>
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
        {{-- SHARED SETTINGS --}}
        <div class="card">
            <div class="card-body">
                <h5 class="header-title mb-3">{{ __('app.display_settings') }}</h5>

                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.link') }} <small class="text-muted">({{ __('app.optional') }})</small></label>
                    <input type="text" id="ann-link" name="link" class="form-control" value="{{ $val('link') }}" placeholder="/en/news/slug or https://...">
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.badge_type') }}</label>
                    <select id="badge_type" name="badge_type" class="form-control">
                        <option value="LIVE" {{ $val('badge_type') == 'LIVE' ? 'selected' : '' }}>🔴 LIVE</option>
                        <option value="INFO" {{ $val('badge_type', 'INFO') == 'INFO' ? 'selected' : '' }}>ℹ️ INFO</option>
                        <option value="ALERT" {{ $val('badge_type') == 'ALERT' ? 'selected' : '' }}>⚠️ ALERT</option>
                        <option value="NEW" {{ $val('badge_type') == 'NEW' ? 'selected' : '' }}>✨ NEW</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.target_view') }}</label>
                    <select name="target_view" class="form-control">
                        <option value="both" {{ $val('target_view', 'both') == 'both' ? 'selected' : '' }}>{{ __('app.all_devices') }}</option>
                        <option value="desktop" {{ $val('target_view') == 'desktop' ? 'selected' : '' }}>{{ __('app.desktop_only') }}</option>
                        <option value="mobile" {{ $val('target_view') == 'mobile' ? 'selected' : '' }}>{{ __('app.mobile_only') }}</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.start_date') }}</label>
                    <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date', $a && $a->start_date ? $a->start_date->format('Y-m-d\TH:i') : '') }}">
                </div>

                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.end_date') }}</label>
                    <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date', $a && $a->end_date ? $a->end_date->format('Y-m-d\TH:i') : '') }}">
                </div>

                <div class="form-group mb-0">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" {{ old('is_active', $a ? $a->is_active : true) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_active" style="font-weight:600;font-size:13px;">{{ __('app.active') }}</label>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-block" style="width:100%;">
            <i class="fas fa-save me-1"></i> {{ __('app.save') }}
        </button>
    </div>
</div>
