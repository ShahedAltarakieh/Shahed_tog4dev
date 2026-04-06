@extends('layouts.admin.add')
@section('title') {{ __('app.edit details') }} @endsection

@section('content')

@include('includes.admin.header', ['label_name' => __('app.edit details')])

<div class="row">
    <div class="col-12">
        <div class="widget-rounded-circle card-box d-flex justify-content-between">
            <form class='w-100' action="{{ route('shortlinks.update', $shortlink->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-12">
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
                        <input type="url" id="original_url" name="original_url" placeholder="{{ __('app.original url') }}" value="{{ $shortlink->original_url }}" class="form-control" required>
                    </div>

                    <!-- Custom Short Code -->
                    <div class="form-group col-md-6">
                        <label for="short_code">{{ __('app.short code') }}</label>
                        <input type="text" id="short_code" name="short_code" placeholder="{{ __('app.short code') }}" value="{{ $shortlink->short_code }}" class="form-control" maxlength="20">
                    </div>

                    <!-- Save Buttons -->
                    <div class="form-group col-md-12 mt-3">
                        <button class='btn btn-primary px-4' type="submit" name="save_and_return" value="save_and_return">{{ __('app.save') }}</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

@endsection
