@extends('layouts.admin.add')
@section('title'){{ __('app.view_details') }}@endsection

@section('content')

@include('includes.admin.header', ['label_name' => __('app.view_details')])

<div class="row mt-4">
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">{{ __('Collection Team Details') }}</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <table class="table table-sm table-borderless">
                    <tbody>
                        <tr>
                            <th>{{ __('app.first_name') }}:</th>
                            <td>{{ $collectionTeam->first_name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('app.last_name') }}:</th>
                            <td>{{ $collectionTeam->last_name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('app.email') }}:</th>
                            <td>{{ $collectionTeam->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('app.phone') }}:</th>
                            <td>{{ $collectionTeam->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('app.country') }}:</th>
                            <td>{{ $collectionTeam->country ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('app.city') }}:</th>
                            <td>{{ $collectionTeam->city ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('app.address') }}:</th>
                            <td>{{ $collectionTeam->address ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('app.created at') }}:</th>
                            <td>{{ $collectionTeam->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('app.updated at') }}:</th>
                            <td>{{ $collectionTeam->updated_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">{{ __('Cart Items') }}</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Item ID</th>
                            <th>Product Name</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Payment Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($collectionTeam->cartItems as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->title ?? 'N/A' }}</td>
                                <td>{{ class_basename($item->model_type) }}</td>
                                <td>{{ number_format($item->price, 2) }} {{ __('app.currency') }}</td>
                                <td>{{ $item->type }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Confirm and Back Buttons -->
<div class="row mt-4">
    <div class="col text-center">
        <form method="POST" action="{{ route('cart.confirm', ['collectionTeam' => $collectionTeam->id]) }}" class="d-inline-block">
            @csrf
            <button type="submit" class="btn px-5 {{ $allItemsPaid ? 'btn-info cursor-not-allowed' : 'btn-success' }}"
                    {{ $allItemsPaid ? 'disabled' : '' }}>
                {{ $allItemsPaid ? __('app.confirmed') : __('app.confirm') }}
            </button>
        </form>
        <a href="{{ route('collection_team.index') }}" class="btn btn-secondary px-5 ml-2">{{ __('app.back') }}</a>
    </div>
</div>

@endsection
