@extends('layouts.admin.add')
@section('title'){{ __('app.add new') }}@endsection

@section('content')

@include('includes.admin.header' , ['label_name' => __('app.add new') ])

<div class="row">
    <div class="col-12">
        <div class="widget-rounded-circle card-box d-flex justify-content-between">
            <form class='w-100' action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data">
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
                                <label for="name">{{ __('app.name') }}</label>
                                <input type="text" id="name" name="username" placeholder='{{ __('app.name') }}' value="{{ old('username') }}" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email">{{ __('app.email') }}</label>
                                <input type="email" id="email" name="email" placeholder='{{ __('app.email') }}' value="{{ old('email') }}" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="password">{{ __('app.password') }}</label>
                                <input type="password" id="password" name="password" placeholder='{{ __('app.password') }}' value="{{ old('password') }}" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="image">{{ __('app.role') }}</label>
                                <select class="form-control" name="role">
                                    <option value="">{{ __('app.choose') }}</option>
                                    <option value="0" @if(old('role') == 0) SELECTED @endif>{{__("app.admin")}}</option>
                                    <option value="1" @if(old('role') == 1) SELECTED @endif>{{__("app.data entry")}}</option>
                                </select>
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
