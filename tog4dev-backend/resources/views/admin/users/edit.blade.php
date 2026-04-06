@extends('layouts.admin.add')
@section('title') {{ __('app.edit details') }} @endsection

@section('content')

@include('includes.admin.header' , ['label_name' => __('app.edit details') ])

<div class="row">
    <div class="col-12">
        <div class="widget-rounded-circle card-box d-flex justify-content-between">
            <form class='w-100' action="{{ route('user.update', $data->id) }}" method="POST" enctype="multipart/form-data">
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
                                <label for="first_name">{{ __('app.first_name') }}</label>
                                <input type="text" id="first_name" name="first_name" placeholder='{{ __('app.first_name') }}' value="{{ old('first_name', $data->first_name) }}" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="last_name">{{ __('app.last_name') }}</label>
                                <input type="text" id="last_name" name="last_name" placeholder='{{ __('app.last_name') }}' value="{{ old('last_name', $data->last_name) }}" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email">{{ __('app.email') }}</label>
                                <input type="email" id="email" name="email" placeholder='{{ __('app.email') }}' value="{{ old('email', $data->email) }}" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="organization_name">{{ __('app.organization_name') }}</label>
                                <input type="text" id="organization_name" name="organization_name" placeholder="{{ __('app.organization_name') }}" value="{{ old('organization_name', $data->organization_name) }}" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="city">{{ __('app.city') }}</label>
                                <input type="text" id="city" name="city" placeholder="{{ __('app.city') }}" value="{{ old('city', $data->city) }}" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="birthday">{{ __('app.birthday') }}</label>
                                <input type="date" id="birthday" name="birthday" value="{{ old('birthday', $data->birthday ? $data->birthday->format('Y-m-d') : '') }}" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="country">{{ __('app.country') }}</label>
                                <input type="text" id="country" name="country" placeholder="{{ __('app.country') }}" value="{{ old('country', $data->country) }}" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="password">{{ __('app.password') }}</label>
                                <input type="password" id="password" name="password" placeholder='{{ __('app.password') }}' value="{{ old('password') }}" class="form-control">
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
