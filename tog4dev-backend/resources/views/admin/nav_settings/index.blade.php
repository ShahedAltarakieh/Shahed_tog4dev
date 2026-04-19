@extends('layouts.admin.add')
@section('title'){{ __('app.navigation_visibility') }} @endsection

@section('content')

@include('includes.admin.header', ['label_name' => __('app.navigation_visibility')])

<div class="card card-box">
    <div class="card-body">
        <form method="POST" action="{{ route('nav-settings.update') }}">
            @csrf
            @method('PUT')
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>{{ __('app.page_key') }}</th>
                            <th>EN</th>
                            <th>AR</th>
                            <th class="text-center">{{ __('app.visible') }}</th>
                            <th style="width:120px;">{{ __('app.order') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $i => $item)
                            <tr>
                                <td><code>{{ $item->page_key }}</code></td>
                                <td>{{ $item->label_en }}</td>
                                <td dir="rtl">{{ $item->label_ar }}</td>
                                <td class="text-center">
                                    <input type="hidden" name="items[{{ $i }}][id]" value="{{ $item->id }}">
                                    <input type="hidden" name="items[{{ $i }}][visible]" value="0">
                                    <div class="form-check form-switch d-inline-flex">
                                        <input class="form-check-input" type="checkbox"
                                               name="items[{{ $i }}][visible]" value="1"
                                               {{ $item->visible ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm"
                                           name="items[{{ $i }}][order]" value="{{ $item->order }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> {{ __('app.save') }}
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
