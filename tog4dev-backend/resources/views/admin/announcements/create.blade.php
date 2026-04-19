@extends('layouts.admin.add')

@section('title') {{ __('app.add_announcement') }} @endsection

@section('content')
<div class="row mt-3 mb-3">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1" style="background:none;padding:0;">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('announcements.index') }}">{{ __('app.announcements') }}</a></li>
                <li class="breadcrumb-item active">{{ __('app.add_announcement') }}</li>
            </ol>
        </nav>
        <h4 class="page-title mb-0">{{ __('app.add_announcement') }}</h4>
    </div>
</div>

<form action="{{ route('announcements.store') }}" method="POST">
    @csrf

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @include('admin.announcements._form', ['announcement' => null, 'newsItems' => $newsItems, 'mode' => 'create'])
</form>
@endsection

@section('jsCode')
@include('admin.announcements._form_js', ['announcement' => null])
@endsection
