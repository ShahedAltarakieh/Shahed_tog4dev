@extends('layouts.admin.add')
@section('title'){{ __('app.upload sheets') }}@endsection
@section('content')
@include('includes.admin.header', ['label_name' => __('app.upload sheets')])
<div class="row">
    <div class="col-12">
        <div class="widget-rounded-circle card-box d-flex justify-content-between">
            <form class='w-100' action="{{ route('excel.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
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
                            <div class="form-group col-12">
                                <label for="excel_file">Upload excel file</label>
                                <input type="file" id="excel_file" name="excel_file" data-plugins="dropify" data-height="200" data-allowed-file-extensions="xlsx csv xls" />
                            </div>
                            <div class="form-group col-md-12">
                                <button class='btn btn-secondary px-4' type="submit" name="save" value="save">{{ __('app.save') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
