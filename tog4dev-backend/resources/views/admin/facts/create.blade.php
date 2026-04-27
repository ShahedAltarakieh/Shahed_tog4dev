@extends('layouts.admin.add')
@section('title'){{ __('app.add new') }}@endsection

@section('content')

    @include('includes.admin.header', ['label_name' => __('app.add new')])

    <div class="row">
        <div class="col-12">
            <div class="widget-rounded-circle card-box d-flex justify-content-between">
                <form class='w-100' action="{{ route('facts.store', ['type' => $type]) }}" method="POST" enctype="multipart/form-data">
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
                                    <label for="title">{{ __('app.title') }} (AR)</label>
                                    <input type="text" id="title" name="title" placeholder='{{ __('app.title') }}' value="{{ old('title') }}" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="title_en">{{ __('app.title') }} (EN)</label>
                                    <input type="text" id="title_en" name="title_en" placeholder='{{ __('app.title') }}' value="{{ old('title_en') }}" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="description">{{ __('app.description') }} (AR)</label>
                                    <textarea id="description" name="description" rows="4" placeholder='{{ __('app.description') }}' class="form-control">{{ old('description') }}</textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="description_en">{{ __('app.description') }} (EN)</label>
                                    <textarea id="description_en" name="description_en" rows="4" placeholder='{{ __('app.description') }}' class="form-control">{{ old('description_en') }}</textarea>
                                </div>
                                <div class="form-group col-6">
                                    <label for="logo">{{ __('app.logo') }} (AR) (53x46)</label>
                                    <input type="file" id="logo" name="logo" data-plugins="dropify" data-height="200" data-allowed-file-extensions="png jpg jpeg webp" />
                                    @include('includes.admin.image-upload-notes', [
                                        'recommendedSize' => '53 x 46 px',
                                        'maxSize' => '5 MB',
                                        'extensions' => 'png,jpg,jpeg,webp'
                                    ])
                                </div>
                                <div class="form-group col-6">
                                    <label for="logo_en">{{ __('app.logo') }} (EN) (53x46)</label>
                                    <input type="file" id="logo_en" name="logo_en" data-plugins="dropify" data-height="200" data-allowed-file-extensions="png jpg jpeg webp" />
                                    @include('includes.admin.image-upload-notes', [
                                        'recommendedSize' => '53 x 46 px',
                                        'maxSize' => '5 MB',
                                        'extensions' => 'png,jpg,jpeg,webp'
                                    ])
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="category_id">{{ __('app.category') }}</label>
                                    <select class="form-control" id="category_id" name="category_id">
                                        <option value="">{{ __('app.choose') }}</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @if(old('category_id') == $category->id) selected @endif>{{ $category->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="status" class="d-block">{{ __('app.status') }}</label>
                                    <input type="checkbox" data-plugin="switchery" data-color="#1bb99a" name="status" value="1" checked />
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
