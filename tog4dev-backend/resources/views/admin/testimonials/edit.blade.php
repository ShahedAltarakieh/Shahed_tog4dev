@extends('layouts.admin.add')
@section('title'){{ __('app.edit details') }}@endsection

@section('content')

    @include('includes.admin.header' , ['label_name' => __('app.edit details') ])

    <div class="row">
        <div class="col-12">
            <div class="widget-rounded-circle card-box d-flex justify-content-between">
                <form class='w-100' action="{{ route('testimonials.update', ["type" => $type, "testimonial" => $data]) }}" method="POST" enctype="multipart/form-data">
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
                                    <label for="name">{{ __('app.name') }} (AR)</label>
                                    <input type="text" id="name" name="name" placeholder='{{ __('app.name') }}' value="{{ $data->name }}" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="name_en">{{ __('app.name') }} (EN)</label>
                                    <input type="text" id="name_en" name="name_en" placeholder='{{ __('app.name') }}' value="{{ $data->name_en }}" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="description">{{ __('app.description') }} (AR)</label>
                                    <textarea id="description" name="description" rows="4" placeholder='{{ __('app.description') }}' class="form-control">{{ $data->description }}</textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="description_en">{{ __('app.description') }} (EN)</label>
                                    <textarea id="description_en" name="description_en" rows="4" placeholder='{{ __('app.description') }}' class="form-control">{{ $data->description_en }}</textarea>
                                </div>
                                <!-- Location AR -->
                                <div class="form-group col-md-6">
                                    <label for="location">{{ __('app.location') }} (AR)</label>
                                    <input type="text" id="location" name="location" placeholder="{{ __('app.location') }}"
                                           value="{{ old('location', $data->location) }}" class="form-control">
                                </div>

                                <!-- Location EN -->
                                <div class="form-group col-md-6">
                                    <label for="location_en">{{ __('app.location') }} (EN)</label>
                                    <input type="text" id="location_en" name="location_en"
                                           placeholder="{{ __('app.location') }}"
                                           value="{{ old('location_en', $data->location_en) }}" class="form-control">
                                </div>
                                <div class="form-group col-4">
                                    <label for="image">{{ __('app.image') }} (Web) (538x450)</label>
                                    <input type="file" id="image" name="image" data-plugins="dropify" data-default-file="{{ $data->image }}" data-height="200" data-allowed-file-extensions="png jpg jpeg webp"  />
                                    @include('includes.admin.image-upload-notes', [
                                        'recommendedSize' => '538 x 450 px',
                                        'maxSize' => '5 MB',
                                        'extensions' => 'png,jpg,jpeg,webp'
                                    ])
                                </div>
                                <div class="form-group col-4">
                                    <label for="image_tablet">{{ __('app.image') }} (Tablet) (454x380)</label>
                                    <input type="file" id="image_tablet" name="image_tablet" data-plugins="dropify" data-default-file="{{ $data->image_tablet }}" data-height="200" data-allowed-file-extensions="png jpg jpeg webp"  />
                                    @include('includes.admin.image-upload-notes', [
                                        'recommendedSize' => '454 x 380 px',
                                        'maxSize' => '5 MB',
                                        'extensions' => 'png,jpg,jpeg,webp'
                                    ])
                                </div>
                                <div class="form-group col-4">
                                    <label for="image_mobile">{{ __('app.image') }} (Mobile) (348x318)</label>
                                    <input type="file" id="image_mobile" name="image_mobile" data-plugins="dropify" data-default-file="{{ $data->image_mobile }}" data-height="200" data-allowed-file-extensions="png jpg jpeg webp"  />
                                    @include('includes.admin.image-upload-notes', [
                                        'recommendedSize' => '348 x 318 px',
                                        'maxSize' => '5 MB',
                                        'extensions' => 'png,jpg,jpeg,webp'
                                    ])
                                </div>
                                <div class="form-group col-6 d-none">
                                    <label for="image_en">{{ __('app.image') }} (EN)</label>
                                    <input type="file" id="image_en" name="image_en" data-plugins="dropify" data-default-file="{{ $data->image_en }}" data-height="200" data-allowed-file-extensions="png jpg jpeg webp"  />
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="category_id">{{ __('app.category') }}</label>
                                    <select name="category_id" id="category_id" class="form-control">
                                        <option value="">{{ __('app.choose') }}</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $data->category_id == $category->id ? 'selected' : '' }}>{{ $category->title }}</option>
                                        @endforeach
                                    </select>
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
