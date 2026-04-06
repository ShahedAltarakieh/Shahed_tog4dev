@extends('layouts.admin.show')
@section('title'){{ __('app.influencer_details') }}@endsection

@section('content')

@include('includes.admin.header', [
    'label_name' => __('app.influencer_details')
])

<div class="row">
    <div class="col-12">
        <div class="widget-rounded-circle card-box">
            <h4>{{ $influencer->name }} - {{ $influencer->email }}</h4>
            <p><strong>{{ __('app.code') }}:</strong> {{ $influencer->code }}</p>
            <p><strong>{{ __('app.page_link') }}:</strong> <a href="{{ $influencer->page_link }}" target="_blank">{{ $influencer->page_link }}</a></p>
            <p><strong>{{ __('app.expiry_date') }}:</strong> 
                @if($influencer->expiry_date)
                    {{ $influencer->expiry_date->format('d/m/Y') }}
                @else
                    {{ __('app.no_expiry') }}
                @endif
            </p>
            <p><strong>{{ __('app.referred_users_count') }}:</strong> {{ $referredUsers->count() }}</p>

            <hr>

            <h5>{{ __('app.referred_users') }}</h5>
            @if($referredUsers->count() > 0)
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('app.name') }}</th>
                            <th>{{ __('app.email') }}</th>
                            <th>{{ __('app.registered_date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($referredUsers as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>{{ __('app.no_referred_users') }}</p>
            @endif

            <a href="{{ route('admin.influencers.index') }}" class="btn btn-secondary mt-3">{{ __('app.back_to_list') }}</a>
            <a href="{{ route('admin.influencers.edit', $influencer->id) }}" class="btn btn-primary">{{ __('app.edit') }}</a>
        </div>
    </div>
</div>

@endsection
