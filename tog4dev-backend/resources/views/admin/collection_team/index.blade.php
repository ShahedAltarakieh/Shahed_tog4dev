@extends('layouts.admin.show')
@section('title') {{ __('app.collection_team') }} @endsection

@section('content')

@include('includes.admin.header', [
    'label_name' => __('app.collection_team'), 
    'download_button' => route('collection_team.download')
])

<div class='row mt-3'>
    <div class="col-md-12">
        <div class="widget-rounded-circle card-box">
            <div class="row">
                <div class='col-12 table-responsive'>
                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('app.first_name') }}</th>
                                <th>{{ __('app.last_name') }}</th>
                                <th>{{ __('app.email') }}</th>
                                <th>{{ __('app.phone') }}</th>
                                <th>{{ __('app.city') }}</th>
                                <th>{{ __('app.address') }}</th>
                                <th>{{ __('app.created at') }}</th>
                                <th>{{ __('app.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($collectionTeams as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->first_name }}</td>
                                    <td>{{ $item->last_name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->phone }}</td>
                                    <td>{{ $item->city }}</td>
                                    <td>{{ $item->address }}</td>
                                    <td>{{ $item->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <a class="btn btn-info"
                                            href="{{ route('collection_team.show', ['collection_team' => $item->id]) }}">
                                            <i class="mdi mdi-eye-outline"></i>
                                        </a>
                                        <button class='btn btn-danger btn-delete' data-toggle="tooltip" data-placement="top"
                                            title="{{ __('app.delete') }}" data-original-title="Tooltip on top"
                                            data-table="collection_team" data-id="{{ $item->id }}">
                                            <i class='fas fa-trash-alt'></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection