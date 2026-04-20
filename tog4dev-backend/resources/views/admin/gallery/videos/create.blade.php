@extends('layouts.admin.add')
@section('title'){{ __('app.add new') }} - {{ __('app.videos') }}@endsection

@section('content')
@include('includes.admin.header', ['label_name' => __('app.add new') . ' - ' . __('app.videos')])

<div class="row">
    <div class="col-12">
        <div class="widget-rounded-circle card-box d-flex justify-content-between">
            <form class='w-100' action="{{ route('gallery-admin.videos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class='ml-3 mb-0'>
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>

                            <div class="form-group col-md-6">
                                <label for="title">{{ __('app.title') }} (AR) <span class="text-danger">*</span></label>
                                <input type="text" id="title" name="title" placeholder="{{ __('app.title') }}"
                                    value="{{ old('title') }}" class="form-control" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="title_en">{{ __('app.title') }} (EN)</label>
                                <input type="text" id="title_en" name="title_en" placeholder="{{ __('app.title') }}"
                                    value="{{ old('title_en') }}" class="form-control">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="description">{{ __('app.description') }} (AR)</label>
                                <textarea id="description" name="description" placeholder="{{ __('app.description') }}"
                                    class="form-control" rows="3">{{ old('description') }}</textarea>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="description_en">{{ __('app.description') }} (EN)</label>
                                <textarea id="description_en" name="description_en" placeholder="{{ __('app.description') }}"
                                    class="form-control" rows="3">{{ old('description_en') }}</textarea>
                            </div>

                            <div class="form-group col-md-8">
                                <label for="video_url">{{ __('app.video url') }} (YouTube) <span class="text-danger">*</span></label>
                                <input type="url" id="video_url" name="video_url" placeholder="https://www.youtube.com/watch?v=..."
                                    value="{{ old('video_url') }}" class="form-control" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="display_target">{{ __('app.display for') ?? 'Display For' }} <span class="text-danger">*</span></label>
                                <select name="display_target" id="display_target" class="form-control" required>
                                    <option value="both" {{ old('display_target', 'both') === 'both' ? 'selected' : '' }}>
                                        {{ __('app.both') ?? 'Both (Mobile & Desktop)' }}
                                    </option>
                                    <option value="mobile" {{ old('display_target') === 'mobile' ? 'selected' : '' }}>
                                        {{ __('app.mobile only') ?? 'Mobile Only' }}
                                    </option>
                                    <option value="desktop" {{ old('display_target') === 'desktop' ? 'selected' : '' }}>
                                        {{ __('app.desktop only') ?? 'Desktop Only' }}
                                    </option>
                                </select>
                                <small class="form-text text-muted">
                                    <i class="fas fa-eye mr-1"></i>{{ __('app.display target help') ?? 'Choose which devices this video will be visible on' }}
                                </small>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="thumbnail">{{ __('app.cover image') ?? 'Cover Image' }}</label>
                                <input type="file" id="thumbnail" name="thumbnail" data-plugins="dropify" data-height="200"
                                    data-allowed-file-extensions="png jpg jpeg webp"
                                    accept=".png,.jpg,.jpeg,.webp" />
                                @include('includes.admin.image-upload-notes', [
                                    'recommendedSize' => '1280 x 720 px (16:9)',
                                    'maxSize' => '5 MB',
                                    'extensions' => 'png,jpg,jpeg,webp'
                                ])
                            </div>

                            <div class="form-group col-md-6 d-flex align-items-center">
                                <div id="thumbnail-preview-wrapper" style="display:none; width: 100%; text-align: center;">
                                    <p class="text-muted mb-2" style="font-size: 13px;"><i class="fas fa-image mr-1"></i>{{ __('app.cover preview') ?? 'Cover Preview' }}</p>
                                    <img id="thumbnail-preview" src="" alt="Cover preview" style="max-width: 100%; max-height: 200px; border-radius: 8px; border: 2px solid #e2e8f0; object-fit: cover;" />
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="news_category_id">{{ __('app.category') }} <span class="text-danger">*</span></label>
                                <select name="news_category_id" id="news_category_id" class="form-control" required>
                                    <option value="">{{ __('app.choose') }}</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('news_category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }} - {{ $category->name_en }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-2">
                                <label for="position">{{ __('app.position') }}</label>
                                <input type="number" id="position" name="position"
                                    value="{{ old('position', 0) }}" class="form-control">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="status" class="d-block">{{ __('app.published') }}</label>
                                <input type="checkbox" data-plugin="switchery" data-color="#1bb99a" name="status" value="1" {{ old('status', 1) ? 'checked' : '' }} />
                            </div>

                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-primary px-4">{{ __('app.save') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('jsCode')
<script>
document.getElementById('thumbnail').addEventListener('change', function(e) {
    var file = e.target.files[0];
    var wrapper = document.getElementById('thumbnail-preview-wrapper');
    var preview = document.getElementById('thumbnail-preview');
    if (file && file.type.startsWith('image/')) {
        var reader = new FileReader();
        reader.onload = function(ev) {
            preview.src = ev.target.result;
            wrapper.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        wrapper.style.display = 'none';
    }
});
</script>
@endsection
