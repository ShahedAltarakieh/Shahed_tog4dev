@extends('layouts.admin.add')
@section('title') {{ __('app.edit details') }} @endsection

@section('content')

@include('includes.admin.header', ['label_name' => __('app.edit details')])

<div class="row">
    <div class="col-12">
        <div class="widget-rounded-circle card-box d-flex justify-content-between">
            <form class='w-100' action="{{ route('influencers.update', $influencer->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-row">
                            <!-- Display Validation Errors and Success Messages -->
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

                            <!-- Influencer Name -->
                            <div class="form-group col-md-6">
                                <label for="name">{{ __('app.name') }}</label>
                                <input type="text" id="name" name="name" placeholder="{{ __('app.name') }}"
                                    value="{{ old('name', $influencer->name) }}" class="form-control" required>
                            </div>

                            <!-- Influencer Code -->
                            <div class="form-group col-md-6">
                                <label for="code">{{ __('app.code') }}</label>
                                <input type="text" id="code" name="code" placeholder="{{ __('app.code') }}"
                                    value="{{ old('code', $influencer->code) }}" class="form-control" required>
                            </div>

                            <!-- Influencer Page Link -->
                            <div class="form-group col-md-6">
                                <label for="page_link">{{ __('app.page_link') }}</label>
                                <input type="url" id="page_link" name="page_link" placeholder="{{ __('app.page_link') }}"
                                    value="{{ old('page_link', $cleanPageLink) }}" class="form-control" required>
                            </div>

                            <!-- Expiry Date -->
                            <div class="form-group col-md-6">
                                <label for="expiry_date">{{ __('app.expiry_date') }}</label>
                                <input type="date" id="expiry_date" name="expiry_date" 
                                    value="{{ old('expiry_date', $influencer->expiry_date ? $influencer->expiry_date->format('Y-m-d') : '') }}" 
                                    class="form-control" min="{{ date('Y-m-d') }}">
                            </div>

                            <!-- Save Button -->
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
