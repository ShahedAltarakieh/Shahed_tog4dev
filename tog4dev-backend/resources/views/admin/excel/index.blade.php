@extends('layouts.admin.show')
@section('title') {{ __('app.upload sheets') }} @endsection

@section('content')

@include('includes.admin.header', ['label_name' => __('app.upload sheets'), "add_button" => route('excel.create'), "download_template" => asset('/payment-template.xlsx') ])
<div class='row mt-3'>
    <div class="col-md-12">
        <div class="widget-rounded-circle card-box">
            <div class="row">
                <div class='col-12 table-responsive'>
                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>webID</th>
                                <th>{{ __('app.file name') }}</th>
                                <th>{{ __('app.status') }}</th>
                                <th>{{ __('app.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->file_name }}</td>
                                    <td>
                                        @switch($item->status)
                                            @case(-1)
                                                Error in uploading file
                                            @break
                                            @case(0)
                                                New
                                                @break
                                            @case(1)
                                                In progress
                                                @break
                                            @case(2)
                                                Uploaded
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <a class="btn btn-light" href="{{ route('excel.run-job', ['id' => $item->id]) }}" data-toggle="tooltip" data-placement="top" title="Run again">
                                            <i class="fas fa-share"></i>
                                        </a>
                                        <a class="btn btn-info" href="{{ route('excel.show', ['id' => $item->id]) }}" data-toggle="tooltip" data-placement="top" title="Show payments">
                                            <i class="mdi mdi-eye-outline"></i>
                                        </a>
                                        <a class="btn btn-success btn-download-success" href="{{ route('excel.download', ['id' => $item->id, 'type' => 'approved']) }}" data-toggle="tooltip" data-placement="top" title="Download approved payments">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <a class="btn btn-danger btn-download-failure" href="{{ route('excel.download', ['id' => $item->id, 'type' => 'declined']) }}" data-toggle="tooltip" data-placement="top" title="Download declined payments">
                                            <i class="fas fa-download"></i>
                                        </a>
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
