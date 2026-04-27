@extends('layouts.admin.add')
@section('title'){{ __('app.edit details') }} - {{ __('app.news categories') }}@endsection

@section('content')
@include('includes.admin.header', ['label_name' => __('app.edit details') . ' - ' . __('app.news categories')])

<div class="row">
    <div class="col-12">
        <div class="widget-rounded-circle card-box d-flex justify-content-between">
            <form class="w-100" action="{{ route('news-categories-admin.update', ['id' => $data->id]) }}"
                method="POST">
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
                                <label for="name">{{ __('app.name') }} (AR) <span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name" placeholder="{{ __('app.name') }}"
                                    value="{{ old('name', $data->name) }}" class="form-control" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="name_en">{{ __('app.name') }} (EN)</label>
                                <input type="text" id="name_en" name="name_en" placeholder="{{ __('app.name') }}"
                                    value="{{ old('name_en', $data->name_en) }}" class="form-control">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="position">{{ __('app.position') }}</label>
                                <input type="number" id="position" name="position"
                                    value="{{ old('position', $data->position) }}" class="form-control">
                            </div>

                            <div class="form-group col-md-8">
                                <label for="status" class="d-block">{{ __('app.active') }}</label>
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
