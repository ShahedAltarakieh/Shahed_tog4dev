@extends('layouts.admin.show')
@section('title'){{ __('app.items') }}@endsection

@section('cssCode')
<style>
    .drag-handle { cursor: grab; }
    tr.dragging { opacity: .6; }
</style>
@endsection

@section('content')

@include('includes.admin.header', ['label_name' => __('app.items')])

<div class='row mt-3'>
    <div class="col-md-12">
        <div class="widget-rounded-circle card-box">
            <div class="row">
                <div class='col-12'>

                    <!-- SAVE BUTTON -->
                    <div class="mb-3 text-right">
                        <button id="saveOrderBtn" class="btn btn-primary">
                            {{ __('app.save') }}
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Sort</th>
                                    <th>#</th>
                                    <th>{{ __('app.image') }}</th>
                                    <th>{{ __('app.title') }} (AR)</th>
                                    <th>{{ __('app.title') }} (EN)</th>
                                    <th>{{ __('app.category') }}</th>
                                    <th>
                                        @if($type == 'crowdfunding')
                                            {{ __('app.target') }}
                                        @elseif($type == 'home')
                                            {{ __('app.amount') }} / {{ __('app.target') }}
                                        @else
                                            {{ __('app.amount') }}
                                        @endif
                                    </th>
                                </tr>
                            </thead>

                            <tbody id="sortable">
                                @foreach ($data as $item)
                                    <tr data-id="{{ $item->id }}">
                                        <td class="drag-handle">⋮⋮</td>
                                        <td>{{ $item->id }}</td>
                                        <td><img src="{{ $item->image }}" style="max-height: 40px"></td>
                                        <td>{{ $item->title }}</td>
                                        <td>{{ $item->title_en }}</td>
                                        <td>{{ $item->category->getLocalizationTitle() }}</td>
                                        <td>
                                            {{ (new \App\Helpers\Helper)->formatNumber($item->amount) }}
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
</div>

@endsection

@section("jsCode")
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

<script>
    const tbody = document.getElementById('sortable');
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let latestOrder = [];

    // INIT SORTABLE
    const sortable = Sortable.create(tbody, {
        handle: '.drag-handle',
        animation: 150,
        onStart: (evt) => {
            evt.item.classList.add('dragging');
        },
        onEnd: (_) => {
            document.querySelectorAll('#sortable tr').forEach(tr => tr.classList.remove('dragging'));

            // Update the in-memory order array
            latestOrder = Array.from(document.querySelectorAll('#sortable tr')).map((tr, index) => ({
                id: tr.dataset.id,
                sort_order: index + 1
            }));
        }
    });

    // SAVE BUTTON
    document.getElementById('saveOrderBtn').addEventListener('click', function () {

        // If user didn't drag anything yet, generate order manually
        if (latestOrder.length === 0) {
            latestOrder = Array.from(document.querySelectorAll('#sortable tr')).map((tr, index) => ({
                id: tr.dataset.id,
                sort_order: index + 1
            }));
        }

        $.ajax({
            type: "POST",
            url: "{{ route('items.storeSorting', ['type' => $type]) }}",
            data: JSON.stringify({ order: latestOrder }),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            success: function (data) {
                if (data.status == "success") {
                    Swal.fire({
                        title: "",
                        text: "{{ __('app.save successfully') }}",
                        type: "success",
                        confirmButtonText: "{{ __('app.ok') }}"
                    }).then(() => {
                        window.location.href = data.url;
                    });

                } else {
                    Swal.fire({
                        title: "",
                        text: "{{ __('app.ops there are problem, try again') }}",
                        type: "error",
                        confirmButtonText: "{{ __('app.ok') }}"
                    });
                }
            },
            error: function () {
                Swal.fire({
                    title: "",
                    text: "{{ __('app.ops there are problem, try again') }}",
                    type: "error",
                    confirmButtonText: "{{ __('app.ok') }}"
                });
            }
        });

    });
</script>
@endsection