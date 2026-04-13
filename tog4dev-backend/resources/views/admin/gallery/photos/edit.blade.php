@extends('layouts.admin.add')
@section('title'){{ __('app.edit details') }} - {{ __('app.photos') }}@endsection

@section('content')
@include('includes.admin.header', ['label_name' => __('app.edit details') . ' - ' . __('app.photos')])

<div class="row">
    <div class="col-12">
        <div class="widget-rounded-circle card-box d-flex justify-content-between">
            <form class="w-100" action="{{ route('gallery-admin.photos.update', ['id' => $data->id]) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-row">
                            <div class="form-group col-md-12">
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
                                <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                            </div>

                            <div class="form-group col-md-6">
                                <label for="title">{{ __('app.title') }} (AR) <span class="text-danger">*</span></label>
                                <input type="text" id="title" name="title" placeholder="{{ __('app.title') }}"
                                    value="{{ old('title', $data->title) }}" class="form-control" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="title_en">{{ __('app.title') }} (EN)</label>
                                <input type="text" id="title_en" name="title_en" placeholder="{{ __('app.title') }}"
                                    value="{{ old('title_en', $data->title_en) }}" class="form-control">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="description">{{ __('app.description') }} (AR)</label>
                                <textarea id="description" name="description" placeholder="{{ __('app.description') }}"
                                    class="form-control" rows="3">{{ old('description', $data->description) }}</textarea>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="description_en">{{ __('app.description') }} (EN)</label>
                                <textarea id="description_en" name="description_en" placeholder="{{ __('app.description') }}"
                                    class="form-control" rows="3">{{ old('description_en', $data->description_en) }}</textarea>
                            </div>

                            <div class="form-group col-4">
                                <label for="image">{{ __('app.image') }} (Web)</label>
                                <input type="file" id="image" name="image" data-plugins="dropify"
                                    data-default-file="{{ $data->image }}" data-height="200"
                                    data-allowed-file-extensions="png jpg jpeg webp"
                                    accept=".png,.jpg,.jpeg,.webp" />
                                @include('includes.admin.image-upload-notes', [
                                    'recommendedSize' => '1200 x 800 px',
                                    'maxSize' => '5 MB',
                                    'extensions' => 'png,jpg,jpeg,webp'
                                ])
                            </div>
                            <div class="form-group col-4">
                                <label for="image_tablet">{{ __('app.image') }} (Tablet)</label>
                                <input type="file" id="image_tablet" name="image_tablet" data-plugins="dropify"
                                    data-default-file="{{ $data->image_tablet }}" data-height="200"
                                    data-allowed-file-extensions="png jpg jpeg webp"
                                    accept=".png,.jpg,.jpeg,.webp" />
                                @include('includes.admin.image-upload-notes', [
                                    'recommendedSize' => '800 x 600 px',
                                    'maxSize' => '5 MB',
                                    'extensions' => 'png,jpg,jpeg,webp'
                                ])
                            </div>
                            <div class="form-group col-4">
                                <label for="image_mobile">{{ __('app.image') }} (Mobile)</label>
                                <input type="file" id="image_mobile" name="image_mobile" data-plugins="dropify"
                                    data-default-file="{{ $data->image_mobile }}" data-height="200"
                                    data-allowed-file-extensions="png jpg jpeg webp"
                                    accept=".png,.jpg,.jpeg,.webp" />
                                @include('includes.admin.image-upload-notes', [
                                    'recommendedSize' => '600 x 400 px',
                                    'maxSize' => '5 MB',
                                    'extensions' => 'png,jpg,jpeg,webp'
                                ])
                            </div>

                            <div class="form-group col-md-4">
                                <label for="news_category_id">{{ __('app.category') }} <span class="text-danger">*</span></label>
                                <select name="news_category_id" id="news_category_id" class="form-control" required>
                                    <option value="">{{ __('app.choose') }}</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('news_category_id', $data->news_category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }} - {{ $category->name_en }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-2">
                                <label for="position">{{ __('app.position') }}</label>
                                <input type="number" id="position" name="position"
                                    value="{{ old('position', $data->position) }}" class="form-control">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="status" class="d-block">{{ __('app.published') }}</label>
                                <input type="checkbox" data-plugin="switchery" data-color="#1bb99a" name="status" value="1" {{ old('status', $data->status) ? 'checked' : '' }} />
                            </div>

                            <div class="form-group col-md-12 text-center mt-3">
                                <button type="submit" class="btn btn-primary px-5">{{ __('app.save') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
