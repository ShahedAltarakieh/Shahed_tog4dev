@extends('layouts.admin.show')
@section('title'){{ __('app.contact_us') }}@endsection

@section('content')

@include('includes.admin.header', ['label_name' => __('app.contact_us'), "add_button" => null])
<div class="container mt-4">
    <h1 class="mb-4 text-center">{{ __('app.contact_us_details') }}</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th>{{ __('app.first_name') }}</th>
                                <td>{{ $contact->first_name }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('app.last_name') }}</th>
                                <td>{{ $contact->last_name }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('app.email') }}</th>
                                <td>{{ $contact->email }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('app.country') }}</th>
                                <td>{{ $contact->country }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('app.organization') }}</th>
                                <td>{{ $contact->organization_name ?? __('app.not_provided') }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('app.phone') }}</th>
                                <td>{{ $contact->phone ?? __('app.not_provided') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th>{{ __('app.type') }}</th>
                                <td>
                                    @switch($contact->type)
                                        @case(1)
                                            {{ __('app.organization') }}
                                            @break
                                        @case(2)
                                            {{ __('app.projects') }}
                                            @break
                                        @default
                                            {{ __('app.unknown') }}
                                    @endswitch
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('app.status') }}</th>
                                <td>
                                    @if ($contact->status == 1)
                                        <span class="badge badge-success">{{ __('app.active') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ __('app.inactive') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('app.is_read') }}</th>
                                <td>
                                    @if ($contact->is_read == 1)
                                        <span class="badge badge-success">{{ __('app.yes') }}</span>
                                    @else
                                        <span class="badge badge-warning">{{ __('app.no') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('app.created at') }}:</th>
                                <td>{{ $contact->created_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('app.updated at') }}:</th>
                                <td>{{ $contact->updated_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-4">
                <h5 class="text-primary">{{ __('app.message') }}</h5>
                <div class="p-3 bg-light border rounded">
                    {{ $contact->message }}
                </div>
            </div>
            <div class="mt-3 text-center">
                <a href="{{ route('contact_us.index', ['type' => $type]) }}" class="btn btn-primary">
                    {{ __('app.back') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
