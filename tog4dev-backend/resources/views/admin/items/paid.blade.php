@extends('layouts.admin.show')
@section('title'){{ __('app.items') }}@endsection

@section('content')

@include('includes.admin.header', ['label_name' => __('app.items')])
<div class='row mt-3'>
    <div class="col-md-12">
        <div class="widget-rounded-circle card-box">
            <div class="row">
                <div class="col-md-6">
                    <h1 class="header-title mb-3">
                        {{$item->title}}</h1>
                </div>
                <div class="col-md-6">
                    <h1 class="header-title mb-3 text-end">
                        {{ __('app.total_amount') }}: {{ (int)$total }}{{ __('app.currency') }}</h1>
                </div>
            </div>
            <div class="row">
                <div class='col-12 table-responsive'>
                    @if($uniquePaidItems->isEmpty())
                        <p>No paid items found.</p>
                    @else
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('app.user') }}</th>
                                    <th>{{ __('app.type') }}</th>
                                    <th>{{ __('app.price') }}</th>
                                    <th>{{ __('app.paid On') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($uniquePaidItems as $cartItem)
                                    <tr>
                                        <td>{{ $cartItem->user ? $cartItem->user->first_name . ' ' . $cartItem->user->last_name : 'N/A' }}</td> <!-- Displaying user name -->
                                        <td>{{ $cartItem->type }}</td>
                                        <td>{{ $cartItem->price ?? 'N/A' }} {{ __('app.currency') }}</td>
                                        <td>{{ $cartItem->updated_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-center">
                            <a href="{{ route('items.index', ['type' => $type]) }}"
                                class="btn btn-secondary px-4">{{ __('app.back') }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
