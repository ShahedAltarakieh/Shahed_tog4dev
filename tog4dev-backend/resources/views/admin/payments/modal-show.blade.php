<div class="row mt-3">
    <div class="col-md-12">
        <div class="widget-rounded-circle card-box">
            <div class="row">
                <div class="col-12">
                    <h1 class="mb-4 text-center">Payment Details - ID {{ $payment->id }}</h1>

                    <!-- Styled User and Payment Information -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div
                            class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                            <h4 class="mb-0">Payment Summary</h4>
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-title">{{ __('app.user') }}</span>
                                        <span class="info-value">{{ $payment->userDetails->first_name }}
                                        {{ $payment->userDetails->last_name }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-title">Transaction ID</span>
                                        <span class="info-value">{{ $payment->cart_id }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-4 mt-3">
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-title">{{ __('app.phone') }}</span>
                                        <span class="info-value">{{ $payment->userDetails->phone }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-title">{{ __('app.created_at') }}</span>
                                        <span class="info-value"> {{ $payment->created_at->format('Y-m-d H:i:s') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-4 mt-3">
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-title">{{ __('app.status') }}</span>
                                        <span class="info-value">
                                            <span class="badge bg-{{ $payment->status == 'approved' ? 'success' : 'danger' }}">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-title">{{ __('app.payment_type') }}</span>
                                        <span class="info-value">{{ $payment->payment_type }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Items Table -->
                    <h3>Cart Items</h3>
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('app.item_id') }}</th>
                                <th>{{ __('app.product_name') }}</th>
                                <th>{{ __('app.type') }}</th>
                                <th>{{ __('app.price') }}</th>
                                <th>{{ __('app.payment_type') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payment->cartItems as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->title ?? 'N/A' }}</td> <!-- Dynamically fetch title -->
                                    <td>{{ class_basename($item->model_type) }}</td>
                                    <td>{{ (new \App\Helpers\Helper)->formatNumber($item->price / $item->quantity) }} {{ __('app.currency') }}</td>
                                    <td>{{ $item->type }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
