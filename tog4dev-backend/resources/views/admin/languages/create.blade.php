@extends('layouts.admin.add')
@section('title'){{ __('app.add new') }} - {{ __('app.languages') }}@endsection

@section('content')
@include('includes.admin.header', ['label_name' => __('app.add new') . ' - ' . __('app.languages')])

<div class="row">
    <div class="col-12">
        <div class="widget-rounded-circle card-box d-flex justify-content-between">
            <form class='w-100' action="{{ route('languages-admin.store') }}" method="POST">
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

                            <div class="form-group col-md-3">
                                <label for="code">{{ __('app.code') }} <span class="text-danger">*</span></label>
                                <input type="text" id="code" name="code" placeholder="en"
                                    value="{{ old('code') }}" class="form-control" required>
                                <small class="text-muted">{{ __('app.lowercase letters only, e.g. en, ar, fr, es') }}</small>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="name">{{ __('app.name') }} <span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name" placeholder="English"
                                    value="{{ old('name') }}" class="form-control" required>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="native_name">{{ __('app.native name') }} <span class="text-danger">*</span></label>
                                <input type="text" id="native_name" name="native_name" placeholder="English"
                                    value="{{ old('native_name') }}" class="form-control" required>
                            </div>

                            <div class="form-group col-md-2">
                                <label for="direction">{{ __('app.direction') }} <span class="text-danger">*</span></label>
                                <select id="direction" name="direction" class="form-control" required>
                                    <option value="ltr" {{ old('direction', 'ltr') === 'ltr' ? 'selected' : '' }}>LTR</option>
                                    <option value="rtl" {{ old('direction') === 'rtl' ? 'selected' : '' }}>RTL</option>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="position">{{ __('app.position') }}</label>
                                <input type="number" id="position" name="position"
                                    value="{{ old('position', 0) }}" class="form-control">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="is_active" class="d-block">{{ __('app.active') }}</label>
                                <input type="checkbox" data-plugin="switchery" data-color="#1bb99a" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }} />
                            </div>

                            <div class="form-group col-md-3">
                                <label for="is_default" class="d-block">{{ __('app.default') }}</label>
                                <input type="checkbox" data-plugin="switchery" data-color="#0acf97" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }} />
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
