@extends('layouts.admin.add')
@section('title'){{ __('app.edit details') }} @endsection

@section('content')
@include('includes.admin.header', ['label_name' => __('app.edit details')])

<div class="row">
    <div class="col-12">
        <div class="widget-rounded-circle card-box d-flex justify-content-between">
            <form class='w-100' action="{{ route('stories.update', ['type' => $type, 'story' => $data->id]) }}" method="POST" enctype="multipart/form-data">
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

                            <!-- Title AR -->
                            <div class="form-group col-md-6">
                                <label for="title">{{ __('app.title') }} (AR)</label>
                                <input type="text" id="title" name="title" placeholder="{{ __('app.title') }}" value="{{ old('title', $data->title) }}" class="form-control">
                            </div>

                            <!-- Title EN -->
                            <div class="form-group col-md-6">
                                <label for="title_en">{{ __('app.title') }} (EN)</label>
                                <input type="text" id="title_en" name="title_en" placeholder="{{ __('app.title') }}" value="{{ old('title_en', $data->title_en) }}" class="form-control">
                            </div>

                            <!-- Image AR -->
                            <div class="form-group col-4">
                                <label for="image">{{ __('app.image') }} (Web) (328x581)</label>
                                <input type="file" id="image" name="image" data-plugins="dropify" data-default-file="{{ old('image', $data->getImageAttribute()) }}" data-height="200" data-allowed-file-extensions="png jpg jpeg webp" />
                            </div>
                            
                            <div class="form-group col-4">
                                <label for="image_tablet">{{ __('app.image') }} (Tablet) (367x474)</label>
                                <input type="file" id="image_tablet" name="image_tablet" data-plugins="dropify" data-default-file="{{ $data->image_tablet }}" data-height="200" data-allowed-file-extensions="png jpg jpeg webp"  />
                            </div>

                            <div class="form-group col-4">
                                <label for="image_mobile">{{ __('app.image') }} (Mobile) (367x474)</label>
                                <input type="file" id="image_mobile" name="image_mobile" data-plugins="dropify" data-default-file="{{ $data->image_mobile }}" data-height="200" data-allowed-file-extensions="png jpg jpeg webp"  />
                            </div>

                            <!-- Image EN -->
                            <div class="form-group col-6 d-none">
                                <label for="image_en">{{ __('app.image') }} (EN)</label>
                                <input type="file" id="image_en" name="image_en" data-plugins="dropify" data-default-file="{{ old('image_en', $data->getImageENAttribute()) }}" data-height="200" data-allowed-file-extensions="png jpg jpeg webp" />
                            </div>

                            <!-- Category -->
                            <div class="form-group col-md-6">
                                <label for="category_id">{{ __('app.category') }}</label>
                                <select name="category_id" id="category_id" class="form-control">
                                    <option value="">{{ __('app.choose') }}</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $data->category_id == $category->id ? 'selected' : '' }}>{{ $category->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Status -->
                            <div class="form-group col-md-6">
                                <label for="status" class="d-block">{{ __('app.status') }}</label>
                                <input type="checkbox" data-plugin="switchery" data-color="#1bb99a" name="status" value="1" {{ $data->status == 1 ? 'checked' : '' }} />
                            </div>

                            <!-- Save Button -->
                            <div class="form-group col-md-12">
                                <button class="btn btn-primary px-4">{{ __('app.save') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
