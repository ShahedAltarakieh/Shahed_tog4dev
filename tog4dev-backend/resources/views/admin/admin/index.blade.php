@extends('layouts.admin.show')
@section('title') {{ __('app.admins') }} @endsection

@section('content')

@include('includes.admin.header' , ['label_name' => __('app.admins') ])
<div class='row mt-3'>
    <div class="col-md-12">
        <div class="widget-rounded-circle card-box">
            <div class="row">
                <div class='col-12 table-responsive'>
                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>{{ __('app.name') }}</th>
                                <th>{{ __('app.email') }}</th>
                                <th>{{ __('app.role') }}</th>
                                <th>{{ __('app.created at') }}</th>
                                <th>{{ __('app.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td>{{$user->username}}</td>
                                <td>{{$user->email}}</td>
                                <td>
                                    @switch($user->role)
                                        @case(0)
                                            {{ __('app.admin') }}
                                            @break
                                        @case(1)
                                            {{ __('app.data entry') }}
                                            @break
                                    @endswitch
                                </td>
                                <td>{{$user->created_at}}</td>
                                <td>
                                    <a href="{{ route('admin.edit', $user->id) }}" class='btn btn-secondary' data-toggle="tooltip" data-placement="top" title="{{ __('app.edit') }}" data-original-title="Tooltip on top" data-id="{{ $user->id }}"><i class='fas fa-edit'></i></a>
                                    <button class='btn btn-danger btn-delete' data-toggle="tooltip" data-placement="top" title="{{ __('app.delete') }}" data-original-title="Tooltip on top" data-table="admins" data-id="{{ $user->id }}"><i class='fas fa-trash-alt'></i></button>
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
