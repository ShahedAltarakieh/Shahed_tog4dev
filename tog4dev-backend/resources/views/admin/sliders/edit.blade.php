@extends('layouts.admin.add')
@section('title'){{ __('app.edit details') }}@endsection

@section('content')

    @include('includes.admin.header' , ['label_name' => __('app.edit details') ])

    <div class="row">
        <div class="col-12">
            <div class="widget-rounded-circle card-box d-flex justify-content-between">
                <form class='w-100' action="{{ route('sliders.update', ["slider" => $data]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
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
                                    <input type="text" id="title" name="title" placeholder='{{ __('app.name') }}' value="{{ $data->title }}" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="title_en">{{ __('app.title') }} (EN)</label>
                                    <input type="text" id="title_en" name="title_en" placeholder='{{ __('app.name') }}' value="{{ $data->title_en }}" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="description">{{ __('app.description') }} (AR)</label>
                                    <textarea id="description" name="description" rows="4" placeholder='{{ __('app.description') }}' class="form-control">{{ $data->description }}</textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="description_en">{{ __('app.description') }} (EN)</label>
                                    <textarea id="description_en" name="description_en" rows="4" placeholder='{{ __('app.description') }}' class="form-control">{{ $data->description_en }}</textarea>
                                </div>
                                <div class="form-group col-4">
                                    <label for="image">{{ __('app.image') }} (Web) (1300x664)</label>
                                    <input type="file" id="image" name="image" data-plugins="dropify" data-max-file-size="3M" data-default-file="{{ $data->image }}" data-height="200" data-allowed-file-extensions="png jpg jpeg webp"  />
                                    @include('includes.admin.image-upload-notes', [
                                        'recommendedSize' => '1300 x 664 px',
                                        'maxSize' => '3 MB',
                                        'extensions' => 'png,jpg,jpeg,webp'
                                    ])
                                </div>
                                <div class="form-group col-4">
                                    <label for="image_tablet">{{ __('app.image') }} (Tablet) (900x460)</label>
                                    <input type="file" id="image_tablet" name="image_tablet" data-plugins="dropify" data-max-file-size="3M" data-default-file="{{ $data->image_tablet }}" data-height="200" data-allowed-file-extensions="png jpg jpeg webp"  />
                                    @include('includes.admin.image-upload-notes', [
                                        'recommendedSize' => '900 x 460 px',
                                        'maxSize' => '3 MB',
                                        'extensions' => 'png,jpg,jpeg,webp'
                                    ])
                                </div>
                                <div class="form-group col-4">
                                    <label for="image_mobile">{{ __('app.image') }} (Mobile) (432x690)</label>
                                    <input type="file" id="image_mobile" name="image_mobile" data-plugins="dropify" data-max-file-size="3M" data-default-file="{{ $data->image_mobile }}" data-height="200" data-allowed-file-extensions="png jpg jpeg webp"  />
                                    @include('includes.admin.image-upload-notes', [
                                        'recommendedSize' => '432 x 690 px',
                                        'maxSize' => '3 MB',
                                        'extensions' => 'png,jpg,jpeg,webp'
                                    ])
                                </div>
                                <div class="form-group col-6 d-none">
                                    <label for="image_en">{{ __('app.image') }} (EN)</label>
                                    <input type="file" id="image_en" name="image_en" data-plugins="dropify" data-max-file-size="3M" data-default-file="{{ $data->image_en }}" data-height="200" data-allowed-file-extensions="png jpg jpeg webp"  />
                                </div>
                                <div class="form-group col-6">
                                    <label for="logo">{{ __('app.logo') }} (AR)</label>
                                    <input type="file" id="logo" name="logo" data-plugins="dropify" data-max-file-size="3M" data-default-file="{{ $data->logo }}" data-height="200" data-allowed-file-extensions="png jpg jpeg webp"  />
                                    @include('includes.admin.image-upload-notes', [
                                        'recommendedSize' => '300 x 200 px',
                                        'maxSize' => '3 MB',
                                        'extensions' => 'png,jpg,jpeg,webp'
                                    ])
                                </div>
                                <div class="form-group col-6">
                                    <label for="logo_en">{{ __('app.logo') }} (EN)</label>
                                    <input type="file" id="logo_en" name="logo_en" data-plugins="dropify" data-max-file-size="3M" data-default-file="{{ $data->logo_en }}" data-height="200" data-allowed-file-extensions="png jpg jpeg webp"  />
                                    @include('includes.admin.image-upload-notes', [
                                        'recommendedSize' => '300 x 200 px',
                                        'maxSize' => '3 MB',
                                        'extensions' => 'png,jpg,jpeg,webp'
                                    ])
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="status" class="d-block">{{ __('app.status') }}</label>
                                    <input type="checkbox" data-plugin="switchery" data-color="#1bb99a" name="status" value="1" @if($data->status == 1) checked @endif/>
                                </div>
                                <div class="form-group col-md-12">
                                    <button class='btn btn-primary px-4'>{{ __('app.save') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
