@extends('layouts.admin.add')
@section('title') {{ __('app.add new') }} @endsection

@section('content')

@include('includes.admin.header', ['label_name' => __('app.add new')])

<div class="row">
    <div class="col-12">
        <div class="widget-rounded-circle card-box d-flex justify-content-between">
            <form class='w-100' action="{{ route('shortlinks.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-12">

                        {{-- Validation Errors --}}
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

                    <!-- Original URL -->
                    <div class="form-group col-md-6">
                        <label for="original_url">{{ __('app.original url') }}</label>
                        <input type="url" id="original_url" name="original_url" placeholder="{{ __('app.original url') }}" value="{{ old('original_url') }}" class="form-control" required>
                    </div>

                    <!-- Custom Short Code (optional) -->
                    <div class="form-group col-md-6">
                        <label for="short_code">{{ __('app.short code') }}</label>
                        <input type="text" id="short_code" name="short_code" placeholder="{{ __('app.short code') }}" value="{{ old('short_code') }}" class="form-control" maxlength="20">
                    </div>

                    <!-- Save Buttons -->
                    <div class="form-group col-md-12 mt-3">
                        <button class='btn btn-primary px-4' type="submit" name="save_and_return" value="save_and_return">{{ __('app.save') }}</button>
                        <button class='btn btn-secondary px-4' type="submit" name="save" value="save">{{ __('app.save & create another') }}</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

@endsection
