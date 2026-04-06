@extends('layouts.admin.show')
@section('title'){{ __('app.items') }}@endsection

@section('content')

@include('includes.admin.header', ['label_name' => __('app.items'), "add_button" => route('items.create', ["type" => $type]), "sorting_btn" => route('items.sorting', ["type" => $type])])
<div class='row mt-3'>
    <div class="col-md-12">
        <div class="widget-rounded-circle card-box">
            <div class="row">
                <div class='col-12 table-responsive'>
                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('app.image') }}</th>
                                <th>{{ __('app.title') }} (AR)</th>
                                <th>{{ __('app.title') }} (EN)</th>
                                <th>{{ __('app.category') }}</th>
                                <th>
                                    @if($type == 'crowdfunding')
                                        {{ __('app.target') }}
                                    @else
                                        {{ __('app.amount') }}
                                    @endif
                                </th>
                                @if($type == 'crowdfunding')
                                    <th>{{ __('app.remaining') }}</th>
                                    <th>{{ __('app.completed') }}</th>
                                @endif
                                <th>{{ __('app.status') }}</th>
                                @if($type != 'organization')
                                <th>{{ __('app.add to home') }}</th>
                                @endif
                                <th>{{ __('app.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td><img src="{{ $item->image }}" style="max-height: 40px"></td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->title_en }}</td>
                                    <td>{{ $item->category->title }}</td>
                                    <td>
                                        {{ (new \App\Helpers\Helper)->formatNumber($item->amount) }}
                                    </td>
                                    @if($type == 'crowdfunding')
                                        <td>
                                            @if($item->cartItemsPaid->sum("price") >= $item->amount)
                                                
                                            @else
                                                {{ (new \App\Helpers\Helper)->formatNumber(number_format($item->amount - $item->cartItemsPaid->sum("price"), 2, '.', '')) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->cartItemsPaid->sum("price") >= $item->amount)
                                                {{ __('app.yes') }}
                                            @else
                                                {{ __('app.no') }}
                                            @endif
                                        </td>
                                    @endif
                                    <td>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input change_status" {{ ($item->status == 1) ? "checked" : "" }}
                                                   data-table="{{$type}}/items"
                                                   data-status="{{ $item->status }}"
                                                   data-id="{{ $item->id }}"
                                                   id="customSwitchStatus{{ $item->id }}">
                                            <label class="custom-control-label" for="customSwitchStatus{{ $item->id }}"></label>
                                        </div>
                                    </td>
                                    @if($type != 'organization')
                                    <td>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input add_to_home" {{ ($item->show_in_home == 1) ? "checked" : "" }} data-table="items"
                                                data-add-to-home="{{ $item->show_in_home }}" data-id="{{ $item->id }}"
                                                id="customSwitch{{ $item->id }}">
                                            <label class="custom-control-label" for="customSwitch{{ $item->id }}"></label>
                                        </div>
                                    </td>
                                    @endif
                                    <td>
                                        <a class="btn btn-info"
                                            href="{{ route('items.show', ['type' => $type, 'item' => $item->id]) }}">
                                            <i class="mdi mdi-eye-outline"></i>
                                        </a>
                                        <a href="{{ route('items.edit', ["type" => $type, "item" => $item->id]) }}"
                                            class='btn btn-secondary' data-toggle="tooltip" data-placement="top"
                                            title="{{ __('app.edit') }}" data-id="{{ $item->id }}">
                                            <i class='fas fa-edit'></i>
                                        </a>
                                        <button class='btn btn-danger btn-delete' data-toggle="tooltip" data-placement="top"
                                            title="{{ __('app.delete') }}" data-original-title="Tooltip on top"
                                            data-table="{{$type}}/items" data-id="{{ $item->id }}">
                                            <i class='fas fa-trash-alt'></i>
                                        </button>
                                        <!-- Dropdown for actions -->
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-secondary dropdown-toggle"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i> <!-- Ellipsis icon for dropdown -->
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                    href="{{ route('items.slider', ['type' => $type, 'item' => $item->id]) }}">
                                                    <i class="fas fa-sliders-h"></i> {{ __('app.sliders') }}
                                                </a>
                                                <a class="dropdown-item"
                                                    href="{{ route('items.additional_info', ['type' => $type, 'item' => $item->id]) }}">
                                                    <i class="fas fa-list-alt"></i> {{ __('app.additional_info') }}
                                                </a>
                                                <a class="dropdown-item"
                                                    href="{{ route('items.paid', ['type' => $type, 'item' => $item->id]) }}">
                                                    <i class="fas fa-credit-card"></i> {{ __('app.payments') }}
                                                </a>
                                                <a class="dropdown-item"
                                                    href="{{ route('items.seo', ['type' => $type, 'item' => $item->id]) }}">
                                                    <i class="far fa-folder-open"></i> {{ __('app.seo') }}
                                                </a>
                                            </div>
                                        </div>
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
