@extends('layouts.admin.add')

@section('title') {{ __('app.edit_announcement') }} @endsection

@section('content')
<div class="row mt-3 mb-3">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1" style="background:none;padding:0;">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('announcements.index') }}">{{ __('app.announcements') }}</a></li>
                <li class="breadcrumb-item active">{{ __('app.edit') }}</li>
            </ol>
        </nav>
        <h4 class="page-title mb-0">{{ __('app.edit_announcement') }}</h4>
    </div>
</div>

<form action="{{ route('announcements.update', $announcement->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="header-title mb-3">{{ __('app.announcement_content') }}</h5>

                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.title') }} <small class="text-muted">({{ __('app.optional') }})</small></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $announcement->title) }}">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.text') }} <span class="text-danger">*</span></label>
                        <textarea name="text" class="form-control" rows="3" required>{{ old('text', $announcement->text) }}</textarea>
                        @error('text') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.short_text') }} <small class="text-muted">({{ __('app.for_mobile') }})</small></label>
                        <input type="text" name="short_text" class="form-control" value="{{ old('short_text', $announcement->short_text) }}" maxlength="200">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.link') }} <small class="text-muted">({{ __('app.optional') }})</small></label>
                                <input type="text" name="link" class="form-control" value="{{ old('link', $announcement->link) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.cta_text') }} <small class="text-muted">({{ __('app.optional') }})</small></label>
                                <input type="text" name="cta_text" class="form-control" value="{{ old('cta_text', $announcement->cta_text) }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.linked_news') }} <small class="text-muted">({{ __('app.optional') }})</small></label>
                        <select name="news_id" class="form-control">
                            <option value="">— {{ __('app.none') }} —</option>
                            @foreach($newsItems as $news)
                                <option value="{{ $news->id }}" {{ old('news_id', $announcement->news_id) == $news->id ? 'selected' : '' }}>
                                    {{ $news->getLocalizationTitle() }}
                                </option>
                            @endforeach
                        </select>
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
                        <select name="badge_type" class="form-control">
                            <option value="LIVE" {{ old('badge_type', $announcement->badge_type) == 'LIVE' ? 'selected' : '' }}>🔴 LIVE</option>
                            <option value="INFO" {{ old('badge_type', $announcement->badge_type) == 'INFO' ? 'selected' : '' }}>ℹ️ INFO</option>
                            <option value="ALERT" {{ old('badge_type', $announcement->badge_type) == 'ALERT' ? 'selected' : '' }}>⚠️ ALERT</option>
                            <option value="NEW" {{ old('badge_type', $announcement->badge_type) == 'NEW' ? 'selected' : '' }}>✨ NEW</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.target_view') }}</label>
                        <select name="target_view" class="form-control">
                            <option value="both" {{ old('target_view', $announcement->target_view) == 'both' ? 'selected' : '' }}>{{ __('app.all_devices') }}</option>
                            <option value="desktop" {{ old('target_view', $announcement->target_view) == 'desktop' ? 'selected' : '' }}>{{ __('app.desktop_only') }}</option>
                            <option value="mobile" {{ old('target_view', $announcement->target_view) == 'mobile' ? 'selected' : '' }}>{{ __('app.mobile_only') }}</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.start_date') }}</label>
                        <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date', $announcement->start_date ? $announcement->start_date->format('Y-m-d\TH:i') : '') }}">
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight:600;font-size:13px;">{{ __('app.end_date') }}</label>
                        <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date', $announcement->end_date ? $announcement->end_date->format('Y-m-d\TH:i') : '') }}">
                    </div>

                    <div class="form-group mb-3">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" {{ old('is_active', $announcement->is_active) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active" style="font-weight:600;font-size:13px;">{{ __('app.active') }}</label>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block" style="width:100%;">
                <i class="fas fa-save me-1"></i> {{ __('app.update') }}
            </button>
        </div>
    </div>
</form>
@endsection
