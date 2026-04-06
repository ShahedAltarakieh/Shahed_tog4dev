@extends('layouts.admin.add')
@section('title'){{ __('app.view_details') }} @endsection

@section('content')
@include('includes.admin.header', ['label_name' => __('app.view_details')])

<div class="row">
    <div class="col-12">
        <div class="widget-rounded-circle card-box">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>{{ __('app.title') }} (AR):</th>
                        <td>{{ $data->title ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>{{ __('app.title') }} (EN):</th>
                        <td>{{ $data->title_en ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>{{ __('app.description') }} (AR):</th>
                        <td>{{ $data->description ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>{{ __('app.description') }} (EN):</th>
                        <td>{{ $data->description_en ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>{{ __('app.subscription beneficiaries message') }} (AR):</th>
                        <td>{{ $data->beneficiaries_message ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>{{ __('app.subscription beneficiaries message') }} (EN):</th>
                        <td>{{ $data->beneficiaries_message_en ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>{{ __('app.location') }} (AR):</th>
                        <td>{{ $data->location ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>{{ __('app.location') }} (EN):</th>
                        <td>{{ $data->location_en ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>{{ __('app.image') }} (AR):</th>
                        <td>
                            @if($data->image)
                                <img src="{{ $data->getImageAttribute() }}" alt="{{ __('app.image') }}" class="img-fluid" style="max-width: 200px;">
                            @else
                                {{ '-' }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>{{ __('app.image') }} (EN):</th>
                        <td>
                            @if($data->image_en)
                                <img src="{{ $data->getImageENAttribute() }}" alt="{{ __('app.image_en') }}" class="img-fluid" style="max-width: 200px;">
                            @else
                                {{ '-' }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>{{ __('app.category') }}:</th>
                        <td>{{ $data->category->title ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>
                            @if($type == 'crowdfunding')
                                {{ __('app.target') }}:
                            @else
                                {{ __('app.amount') }}:
                            @endif
                        </th>
                        <td>{{ $data->amount ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>
                            @if($type == 'crowdfunding')
                                {{ __('app.target') }}:
                            @else
                                {{ __('app.amount') }}:
                            @endif
                            (USD):
                        </th>
                        <td>{{ $data->amount_usd ?? '-' }}</td>
                    </tr>

                    @if($data->priceOptions->count())
                    <tr>
                        <th>{{ __('app.number_options') }}:</th>
                        <td>
                            @foreach($data->priceOptions as $option)
                                <div>
                                    <strong>{{ $option->name_ar }} (AR):</strong> {{ $option->price_ar }} (JOD) <br>
                                    <strong>{{ $option->name_en }} (EN):</strong> {{ $option->price_en }} (USD)
                                </div>
                            @endforeach
                        </td>
                    </tr>
                    @endif

                    <tr>
                        <th>{{ __('app.status') }}:</th>
                        <td>{{ $data->status ? __('app.active') : __('app.inactive') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.created at') }}:</th>
                        <td>{{ $data->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.updated at') }}:</th>
                        <td>{{ $data->updated_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="text-center">
                <a href="{{ route('items.index', ['type' => $type]) }}" class="btn btn-secondary px-4">{{ __('app.back') }}</a>
            </div>
        </div>
    </div>
</div>

@endsection
