@extends('layouts.admin.add')
@section('title'){{ __('app.map items') }}@endsection

@section('content')
@include('includes.admin.header', ['label_name' => __('app.map items')])
<div class="row">
    <div class="col-12">
        <div class="widget-rounded-circle card-box d-flex justify-content-between">
            <div class="w-100">
                <div class="form-group col-md-12">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                </div>
                <button type="button" class="btn btn-success" id="addRow">+ {{ __('app.add new') }}</button>

                <table class="table table-bordered mt-3" id="itemsTable">
                    <thead>
                        <tr>
                            <th>{{ __('app.type') }}</th>
                            <th>{{ __('app.name') }}</th>
                            <th>{{ __('app.name') }} (Excel)</th>
                            <th>{{ __('app.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr data-id="{{ $item->id }}">
                                <td>
                                    <select class="form-control model_type" name="model_type[]" disabled>
                                        <option value="" hidden>{{ __('app.select') }}</option>
                                        <option value="مشروع" {{ $item->model_type == 'مشروع' ? 'selected' : '' }}> {{ __('app.project') }} </option>
                                        <option value="كراود" {{ $item->model_type == 'كراود' ? 'selected' : '' }}> {{ __('app.crowdfunding') }} </option>
                                        <option value="كويك" {{ $item->model_type == 'كويك' ? 'selected' : '' }}> {{ __('app.contributions') }} </option>
                                        <option value="كويك شهري" {{ $item->model_type == 'كويك شهري' ? 'selected' : '' }}> {{ __('app.contributions') }} - {{ __('app.monthly') }}</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control item_id" name="item_id[]" disabled>
                                        <option value="" hidden>{{ __('app.select') }}</option>
                                        @if($item->model_type == 'مشروع' || $item->model_type == 'كراود')
                                            @foreach($allItems as $i)
                                                <option value="{{ $i->id }}" {{ $i->id == $item->item_id ? 'selected' : '' }}>
                                                    {{ $i->id }} - {{ __('app.'.(new \App\Helpers\Helper)->getFlipTypes($i->category->type)) }} - {{ $i->getLocalizationTitle() }} - {{ $i->getLocalizationLocation() }} {{ ($i->amount == 1) ? '' : '- '.$i->amount }}
                                                </option>
                                            @endforeach
                                        @elseif($item->model_type == 'كويك' || $item->model_type == 'كويك شهري')
                                            @foreach($quickItems as $q)
                                                <option value="{{ $q->id }}" {{ $q->id == $item->item_id ? 'selected' : '' }}>
                                                    {{ $q->id }} - {{ __('app.'.(new \App\Helpers\Helper)->getFlipTypes($q->category->type)) }} - {{ $q->getLocalizationTitle() }} - {{ $q->getLocalizationLocation() }} {{ ($q->target) ? '- ' . $q->target : ''}}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                                <td><input type="text" class="form-control zbooni_name" name="zbooni_name[]" value="{{ $item->zbooni_name }}" disabled></td>
                                <td>
                                    <button type="button" class="btn btn-warning btn-edit">{{ __('app.edit') }}</button>
                                    <button type="button" class="btn btn-danger removeRow">{{ __('app.delete') }}</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection


@section("jsCode")
<script>
$(document).ready(function() {
    // Add new row at the top
    $('#addRow').on('click', function() {
        let newRow = `
            <tr data-id="">
                <td>
                    <select class="form-control model_type" name="model_type[]">
                        <option value="" hidden>{{ __('app.select') }}</option>
                        <option value="مشروع">{{ __('app.project') }}</option>
                        <option value="كراود">{{ __('app.crowdfunding') }}</option>
                        <option value="كويك">{{ __('app.contributions') }}</option>
                        <option value="كويك شهري">{{ __('app.contributions') }} - {{ __('app.monthly') }}</option>
                    </select>
                </td>
                <td>
                    <select class="form-control item_id" name="item_id[]">
                        <option value="" hidden>{{ __('app.select') }}</option>
                    </select>
                </td>
                <td><input type="text" class="form-control zbooni_name" name="zbooni_name[]" value=""></td>
                <td>
                    <button type="button" class="btn btn-success btn-save">{{ __('app.save') }}</button>
                    <button type="button" class="btn btn-danger removeRow">{{ __('app.delete') }}</button>
                </td>
            </tr>`;
        $('#itemsTable tbody').prepend(newRow);
    });

    // Handle edit → save toggle
    $(document).on('click', '.btn-edit', function() {
        let row = $(this).closest('tr');
        let inputs = row.find('input, select');
        inputs.prop('disabled', false);
        $(this).text('{{ __("app.save") }}').removeClass('btn-warning').addClass('btn-success btn-save').removeClass('btn-edit');
    });

    // Handle save (update single row)
    $(document).on('click', '.btn-save', function() {
        let row = $(this).closest('tr');
        let id = row.data('id');
        let data = {
            _token: '{{ csrf_token() }}',
            id: id,
            model_type: row.find('.model_type').val(),
            item_id: row.find('.item_id').val(),
            zbooni_name: row.find('.zbooni_name').val(),
        };

        $.ajax({
            url: "{{ route('excel.update_map') }}",
            method: "POST",
            data: data,
            success: function(res) {
                Swal.fire({
                    title: "",
                    text: "{{ __('app.updated successfully') }}",
                    type: "success",
                    confirmButtonText: "{{ __('app.ok') }}"
                }).then(
                    (result) => {
                });
                row.find('input, select').prop('disabled', true);
                row.find('.btn-save').text('{{ __("app.edit") }}')
                    .removeClass('btn-success btn-save')
                    .addClass('btn-warning btn-edit');
                if (res.id) row.attr('data-id', res.id); // assign new ID if created
            },
            error: function(err) {
                alert('Error saving data');
                console.error(err);
            }
        });
    });

    // Remove row
    $(document).on('click', '.removeRow', function() {
        let row = $(this).closest('tr');
        let id = row.data('id');
        if (id) {
            Swal.fire({
                    title: "",
                    text: "{{ __('app.are you sure you want delete this record!') }}",
                    type: "warning",
                    showCancelButton: !0,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "{{ __('app.yes delete it') }}",
                    cancelButtonText: "{{ __('app.no close') }}",
                    confirmButtonClass: "btn btn-success mt-2",
                    cancelButtonClass: "btn btn-danger ml-2 mt-2",
                    buttonsStyling: !1
                })
                .then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: "{{ route('excel.delete_map') }}",
                            method: "POST",
                            data: { _token: '{{ csrf_token() }}', id: id },
                            success: function() {
                                row.remove();
                            }
                        });
                    }
                });

            
        } else {
            row.remove();
        }
    });

    // Handle model_type change
    $(document).on('change', '.model_type', function() {
        let type = $(this).val();
        let itemDropdown = $(this).closest('tr').find('.item_id');
        itemDropdown.empty().append(`<option value="" hidden>{{ __('app.select') }}</option>`);

        if (type === 'مشروع' || type === 'كراود') {
            @foreach($allItems as $i)
                itemDropdown.append(`<option value="{{ $i->id }}">{{ $i->id }} - {{ __('app.'.(new \App\Helpers\Helper)->getFlipTypes($i->category->type)) }} - {{ $i->getLocalizationTitle() }} - {{ $i->getLocalizationLocation() }}</option>`);
            @endforeach
        } else if (type === 'كويك' || type === 'كويك شهري') {
            @foreach($quickItems as $q)
                itemDropdown.append(`<option value="{{ $q->id }}">{{ $q->id }} - {{ __('app.'.(new \App\Helpers\Helper)->getFlipTypes($q->category->type)) }} - {{ $q->getLocalizationTitle() }} - {{ $q->getLocalizationLocation() }}</option>`);
            @endforeach
        }
    });
});
</script>
@endsection
