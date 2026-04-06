@extends('layouts.admin.add')
@section('title'){{ __('app.edit details') }}@endsection

@section('content')

@include('includes.admin.header', ['label_name' => __('app.edit details')])

<div class="row">
    <div class="col-12">
        <div class="widget-rounded-circle card-box d-flex justify-content-between">
            <form class='w-100' action="{{ route('collection_team.update', ['collection_team' => $collectionTeam]) }}" method="POST">
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

                            <!-- Full Name Input -->
                            <div class="form-group col-md-6">
                                <label for="full_name">{{ __('app.name') }}</label>
                                <input type="text" id="full_name" name="full_name" placeholder="{{ __('app.name') }}" value="{{ old('full_name', $collectionTeam->full_name) }}" class="form-control">
                            </div>

                            <!-- Email Input -->
                            <div class="form-group col-md-6">
                                <label for="email">{{ __('app.email') }}</label>
                                <input type="email" id="email" name="email" placeholder="{{ __('app.email') }}" value="{{ old('email', $collectionTeam->email) }}" class="form-control">
                            </div>

                            <!-- Phone Input -->
                            <div class="form-group col-md-6">
                                <label for="phone">{{ __('app.phone') }}</label>
                                <input type="text" id="phone" name="phone" placeholder="{{ __('app.phone') }}" value="{{ old('phone', $collectionTeam->phone) }}" class="form-control">
                            </div>

                            <!-- Country Input -->
                            <div class="form-group col-md-6">
                                <label for="country">{{ __('app.country') }}</label>
                                <input type="text" id="country" name="country" placeholder="{{ __('app.country') }}" value="{{ old('country', $collectionTeam->country) }}" class="form-control">
                            </div>

                            <!-- City Input -->
                            <div class="form-group col-md-6">
                                <label for="city">{{ __('app.city') }}</label>
                                <input type="text" id="city" name="city" placeholder="{{ __('app.city') }}" value="{{ old('city', $collectionTeam->city) }}" class="form-control">
                            </div>

                            <!-- Address Input -->
                            <div class="form-group col-md-6">
                                <label for="address">{{ __('app.address') }}</label>
                                <input type="text" id="address" name="address" placeholder="{{ __('app.address') }}" value="{{ old('address', $collectionTeam->address) }}" class="form-control">
                            </div>

                            <!-- Submit Button -->
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
