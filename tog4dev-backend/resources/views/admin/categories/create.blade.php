@extends('layouts.admin.add')
@section('title'){{ __('app.add new') }}@endsection
@section('content')
@include('includes.admin.header', ['label_name' => __('app.add new')])
<div class="row">
    <div class="col-12">
        <div class="widget-rounded-circle card-box d-flex justify-content-between">
            <form class='w-100' action="{{ route('categories.store', ["type" => $type]) }}" method="POST" enctype="multipart/form-data">
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
                                @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="title">Title (AR)</label>
                                <input type="text" id="title" name="title" placeholder='{{ __('app.title') }}' value="{{ old('title') }}" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="title_en">Title (EN)</label>
                                <input type="text" id="title_en" name="title_en" placeholder='{{ __('app.title') }}' value="{{ old('title_en') }}" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="description">Category Description After Hero Section (AR)</label>
                                <textarea id="description" name="description" rows="4" placeholder='{{ __('app.description') }}' class="form-control">{{ old('description') }}</textarea>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="description_en">Category Description After Hero Section (EN)</label>
                                <textarea id="description_en" name="description_en" rows="4" placeholder='{{ __('app.description') }}' class="form-control">{{ old('description_en') }}</textarea>
                            </div>
                            <div class="form-group col-6">
                                <label for="image">Category Hero Logo (AR)</label>
                                <input type="file" id="image" name="image" data-plugins="dropify" data-height="200" data-allowed-file-extensions="png jpg jpeg webp" />
                                @include('includes.admin.image-upload-notes', [
                                    'recommendedSize' => '400 x 400 px',
                                    'maxSize' => '5 MB',
                                    'extensions' => 'png,jpg,jpeg,webp'
                                ])
                            </div>
                            <div class="form-group col-6">
                                <label for="image">Category Hero Logo (EN)</label>
                                <input type="file" id="image_en" name="image_en" data-plugins="dropify" data-height="200" data-allowed-file-extensions="png jpg jpeg webp" />
                                @include('includes.admin.image-upload-notes', [
                                    'recommendedSize' => '400 x 400 px',
                                    'maxSize' => '5 MB',
                                    'extensions' => 'png,jpg,jpeg,webp'
                                ])
                            </div>
                            <div class="col-12">
                                <hr class="my-5">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="hero_title">Category Hero Title (AR)</label>
                                <input type="text" id="hero_title" name="hero_title" placeholder='{{ __('app.hero_title') }}' value="{{ old('title') }}" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="hero_title_en">Category Hero Title (EN)</label>
                                <input type="text" id="hero_title_en" name="hero_title_en" placeholder='{{ __('app.hero_title') }}' value="{{ old('title_en') }}" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="hero_description">Category Hero Description (AR)</label>
                                <textarea id="hero_description" name="hero_description" rows="4" placeholder='{{ __('app.hero_description') }}' class="form-control">{{ old('description') }}</textarea>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="hero_description_en">Category Hero Description (EN)</label>
                                <textarea id="hero_description_en" name="hero_description_en" rows="4" placeholder='{{ __('app.hero_description') }}' class="form-control">{{ old('description_en') }}</textarea>
                            </div>
                            <div class="form-group col-4">
                                <label for="hero_image">Category Hero Image (Web) (1300x376)</label>
                                <input type="file" id="hero_image" name="hero_image" data-plugins="dropify" data-height="200" data-allowed-file-extensions="png jpg jpeg webp" />
                                @include('includes.admin.image-upload-notes', [
                                    'recommendedSize' => '1300 x 376 px',
                                    'maxSize' => '5 MB',
                                    'extensions' => 'png,jpg,jpeg,webp'
                                ])
                            </div>
                            <div class="form-group col-4">
                                <label for="hero_image_tablet">Category Hero Image (Tablet) (900x376)</label>
                                <input type="file" id="hero_image_tablet" name="hero_image_tablet" data-plugins="dropify" data-height="200" data-allowed-file-extensions="png jpg jpeg webp" />
                                @include('includes.admin.image-upload-notes', [
                                    'recommendedSize' => '900 x 376 px',
                                    'maxSize' => '5 MB',
                                    'extensions' => 'png,jpg,jpeg,webp'
                                ])
                            </div>
                            <div class="form-group col-4">
                                <label for="hero_image_mobile">Category Hero Image (Mobile) (432x690)</label>
                                <input type="file" id="hero_image_mobile" name="hero_image_mobile" data-plugins="dropify" data-height="200" data-allowed-file-extensions="png jpg jpeg webp" />
                                @include('includes.admin.image-upload-notes', [
                                    'recommendedSize' => '432 x 690 px',
                                    'maxSize' => '5 MB',
                                    'extensions' => 'png,jpg,jpeg,webp'
                                ])
                            </div>
                            <div class="form-group col-6 d-none">
                                <label for="hero_image_en">Category Hero Image (EN)</label>
                                <input type="file" id="hero_image_en" name="hero_image_en" data-plugins="dropify" data-height="200" data-allowed-file-extensions="png jpg jpeg webp" />
                            </div>
                            <div class="form-group col-md-6">
                                <label for="status" class="d-block">{{ __('app.status') }}</label>
                                <input type="checkbox" checked data-plugin="switchery" data-color="#1bb99a" name="status" value="1"/>
                            </div>
                            <div class="form-group col-md-12">
                                <button class='btn btn-primary px-4' type="submit" name="save_and_return" value="save_and_return">{{ __('app.save') }}</button>
                                <button class='btn btn-secondary px-4' type="submit" name="save" value="save">{{ __('app.save & create another') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
