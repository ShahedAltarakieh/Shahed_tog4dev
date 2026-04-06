@extends('layouts.admin.add')
@section('title'){{ __('app.add new') }} @endsection

@section('content')
@include('includes.admin.header', ['label_name' => __('app.add new')])

<div class="row">
    <div class="col-12">
        <div class="widget-rounded-circle card-box d-flex justify-content-between">
            <form class='w-100' action="{{ route('stories.store', ["type" => $type]) }}" method="POST" enctype="multipart/form-data">
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

                            <!-- Title AR -->
                            <div class="form-group col-md-6">
                                <label for="title">{{ __('app.title') }} (AR)</label>
                                <input type="text" id="title" name="title" placeholder="{{ __('app.title') }}" value="{{ old('title') }}" class="form-control">
                            </div>

                            <!-- Title EN -->
                            <div class="form-group col-md-6">
                                <label for="title_en">{{ __('app.title') }} (EN)</label>
                                <input type="text" id="title_en" name="title_en" placeholder="{{ __('app.title') }}" value="{{ old('title_en') }}" class="form-control">
                            </div>

                            <!-- Image AR -->
                            <div class="form-group col-4">
                                <label for="image">{{ __('app.image') }} (Web) (328x581)</label>
                                <input type="file" id="image" name="image" data-plugins="dropify" data-height="200" data-allowed-file-extensions="png jpg jpeg webp" />
                            </div>

                            <div class="form-group col-4">
                                <label for="image_tablet">{{ __('app.image') }} (Tablet) (367x474)</label>
                                <input type="file" id="image_tablet" name="image_tablet" data-plugins="dropify" data-height="200" data-allowed-file-extensions="png jpg jpeg webp"  />
                            </div>

                            <div class="form-group col-4">
                                <label for="image_mobile">{{ __('app.image') }} (Mobile) (367x474)</label>
                                <input type="file" id="image_mobile" name="image_mobile" data-plugins="dropify" data-height="200" data-allowed-file-extensions="png jpg jpeg webp"  />
                            </div>

                            <!-- Image EN -->
                            <div class="form-group col-6 d-none">
                                <label for="image_en">{{ __('app.image') }} (EN)</label>
                                <input type="file" id="image_en" name="image_en" data-plugins="dropify" data-height="200" data-allowed-file-extensions="png jpg jpeg webp" />
                            </div>

                            <!-- Category -->
                            <div class="form-group col-md-6">
                                <label for="category_id">{{ __('app.category') }}</label>
                                <select name="category_id" id="category_id" class="form-control">
                                    <option value="">{{ __('app.choose') }}</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Status -->
                            <div class="form-group col-md-6">
                                <label for="status" class="d-block">{{ __('app.status') }}</label>
                                <input type="checkbox" checked data-plugin="switchery" data-color="#1bb99a" name="status" value="1"/>
                            </div>

                            <!-- Save Button -->
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
