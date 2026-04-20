@extends('layouts.admin.add')
@section('title'){{ __('app.seo') }}@endsection

@section('content')
    @include('includes.admin.header', ['label_name' => __('app.seo')])

    <div class="row">
        <div class="col-12">
            <div class="widget-rounded-circle card-box d-flex justify-content-between">
                <form class="w-100" action="{{ route('categories.seo', ['type' => $type, 'category' => $category->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="row">
                        <!-- Handle Errors and Success Messages -->
                        <div class="col-md-12">
                            <div class="form-group">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="ml-3 mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Title (AR) -->
                        <div class="form-group col-md-6">
                            <label for="meta_title">{{ __('app.title') }} (AR) (50–60 characters)</label>
                            <input type="text" id="meta_title" name="meta_title" placeholder='{{ __('app.title') }} (AR)' value="{{ old('meta_title', $seo->meta_title) }}" class="form-control">
                        </div>

                        <!-- Title (EN) -->
                        <div class="form-group col-md-6">
                            <label for="meta_title_en">{{ __('app.title') }} (EN) (50–60 characters)</label>
                            <input type="text" id="meta_title_en" name="meta_title_en" placeholder='{{ __('app.title') }} (EN)' value="{{ old('meta_title_en', $seo->meta_title_en) }}" class="form-control">
                        </div>

                        <!-- Description (AR) -->
                        <div class="form-group col-md-6">
                            <label for="meta_description">{{ __('app.description') }} (AR) (150–160 characters)</label>
                            <textarea id="meta_description" name="meta_description" rows="4" placeholder='{{ __('app.description') }} (AR)' class="form-control">{{ old('meta_description', $seo->meta_description) }}</textarea>
                        </div>

                        <!-- Description (EN) -->
                        <div class="form-group col-md-6">
                            <label for="meta_description_en">{{ __('app.description') }} (EN) (150–160 characters)</label>
                            <textarea id="meta_description_en" name="meta_description_en" rows="4" placeholder='{{ __('app.description') }} (EN)' class="form-control">{{ old('meta_description_en', $seo->meta_description_en) }}</textarea>
                        </div>

                        <!-- Keywords (AR) -->
                        <div class="form-group col-md-6">
                            <label for="meta_keywords">{{ __('app.keywords') }} (AR) (5–10 Words ≤ 255 characters)</label>
                            <textarea id="meta_keywords" name="meta_keywords" rows="4" placeholder='{{ __('app.keywords') }} (AR)' class="form-control">{{ old('meta_keywords', $seo->meta_keywords) }}</textarea>
                        </div>

                        <!-- Keywords (EN) -->
                        <div class="form-group col-md-6">
                            <label for="meta_keywords_en">{{ __('app.keywords') }} (EN) (5–10 Words ≤ 255 characters)</label>
                            <textarea id="meta_keywords_en" name="meta_keywords_en" rows="4" placeholder='{{ __('app.keywords') }} (EN)' class="form-control">{{ old('meta_keywords_en', $seo->meta_keywords_en) }}</textarea>
                        </div>

                        <div class="form-group col-6">
                            <label for="image">{{ __('app.image') }} (AR)</label>
                            <input type="file" id="image" name="image" data-plugins="dropify" data-height="200" data-default-file="{{ $seo->image }}" data-allowed-file-extensions="png jpg jpeg webp"  />
                            @include('includes.admin.image-upload-notes', [
                                'recommendedSize' => '1200 x 630 px',
                                'maxSize' => '5 MB',
                                'extensions' => 'png,jpg,jpeg,webp'
                            ])
                        </div>
                        <div class="form-group col-6">
                            <label for="image_en">{{ __('app.image') }} (EN)</label>
                            <input type="file" id="image_en" name="image_en" data-plugins="dropify" data-height="200" data-default-file="{{ $seo->image_en }}" data-allowed-file-extensions="png jpg jpeg webp"  />
                            @include('includes.admin.image-upload-notes', [
                                'recommendedSize' => '1200 x 630 px',
                                'maxSize' => '5 MB',
                                'extensions' => 'png,jpg,jpeg,webp'
                            ])
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-primary px-4">{{ __('app.save') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
