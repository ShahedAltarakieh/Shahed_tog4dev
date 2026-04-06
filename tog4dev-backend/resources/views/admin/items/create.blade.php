@extends('layouts.admin.add')
@section('title'){{ __('app.add new') }} @endsection

@section('content')
@include('includes.admin.header', ['label_name' => __('app.add new')])

<div class="row">
    <div class="col-12">
        <div class="widget-rounded-circle card-box d-flex justify-content-between">
            <form class='w-100' action="{{ route('items.store', ["type" => $type]) }}" method="POST"
                enctype="multipart/form-data">
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

                            <!-- Title AR -->
                            <div class="form-group col-md-6">
                                <label for="title">{{ __('app.title') }} (AR)</label>
                                <input type="text" id="title" name="title" placeholder="{{ __('app.title') }}"
                                    value="{{ old('title') }}" class="form-control">
                            </div>

                            <!-- Title EN -->
                            <div class="form-group col-md-6">
                                <label for="title_en">{{ __('app.title') }} (EN)</label>
                                <input type="text" id="title_en" name="title_en" placeholder="{{ __('app.title') }}"
                                    value="{{ old('title_en') }}" class="form-control">
                            </div>

                            <!-- Description AR -->
                            <div class="form-group col-md-6">
                                <label for="description">{{ __('app.description') }} (AR)</label>
                                <textarea id="description" name="description" placeholder="{{ __('app.description') }}"
                                    class="form-control" rows="4">{{ old('description') }}</textarea>
                            </div>

                            <!-- Description EN -->
                            <div class="form-group col-md-6">
                                <label for="description_en">{{ __('app.description') }} (EN)</label>
                                <textarea id="description_en" name="description_en"
                                    placeholder="{{ __('app.description') }}" class="form-control"
                                    rows="4">{{ old('description_en') }}</textarea>
                            </div>

                            @if ($type == 'projects')
                            <!-- beneficiaries message AR -->
                            <div class="form-group col-md-6">
                                <label for="beneficiaries_message">{{ __('app.subscription beneficiaries message') }} (AR)</label>
                                <textarea id="beneficiaries_message" name="beneficiaries_message" placeholder="{{ __('app.subscription beneficiaries message') }}"
                                    class="form-control" rows="4">{{ old('beneficiaries_message') }}</textarea>
                            </div>

                            <!-- beneficiaries message EN -->
                            <div class="form-group col-md-6">
                                <label for="beneficiaries_message_en">{{ __('app.subscription beneficiaries message') }} (EN)</label>
                                <textarea id="beneficiaries_message_en" name="beneficiaries_message_en"
                                    placeholder="{{ __('app.subscription beneficiaries message') }}" class="form-control"
                                    rows="4">{{ old('beneficiaries_message_en') }}</textarea>
                            </div>

                            <!-- Has beneficiary -->
                            <div class="form-group col-md-12">
                                <label for="has_beneficiary" class="d-block">{{ __('app.has beneficiary') }}</label>
                                <input type="checkbox" data-plugin="switchery" data-color="#1bb99a" name="has_beneficiary" value="1" {{ old('has_beneficiary') ? 'checked' : '' }} />
                            </div>
                            @endif

                            <!-- invoice description after payment AR -->
                            <div class="form-group col-md-6">
                                <label for="description_after_payment">{{ __('app.invoice description after payment') }} (AR)</label>
                                <textarea id="description_after_payment" name="description_after_payment" placeholder="{{ __('app.invoice description after payment') }}"
                                    class="form-control" rows="4">{{ old('description_after_payment') }}</textarea>
                            </div>

                            <!-- invoice description after payment EN -->
                            <div class="form-group col-md-6">
                                <label for="description_after_payment_en">{{ __('app.invoice description after payment') }} (EN)</label>
                                <textarea id="description_after_payment_en" name="description_after_payment_en"
                                    placeholder="{{ __('app.invoice description after payment') }}" class="form-control"
                                    rows="4">{{ old('description_after_payment_en') }}</textarea>
                            </div>

                            <!-- Location AR -->
                            <div class="form-group col-md-6">
                                <label for="location">{{ __('app.location') }} (AR)</label>
                                <input type="text" id="location" name="location" placeholder="{{ __('app.location') }}"
                                    value="{{ old('location') }}" class="form-control">
                            </div>

                            <!-- Location EN -->
                            <div class="form-group col-md-6">
                                <label for="location_en">{{ __('app.location') }} (EN)</label>
                                <input type="text" id="location_en" name="location_en"
                                    placeholder="{{ __('app.location') }}" value="{{ old('location_en') }}"
                                    class="form-control">
                            </div>

                            <!-- Image AR -->
                            <div class="form-group col-4">
                                <label for="image">{{ __('app.image') }} (Web) (404x200)</label>
                                <input type="file" id="image" name="image" data-plugins="dropify" data-height="200"
                                    data-allowed-file-extensions="png jpg jpeg webp" />
                            </div>
                            <div class="form-group col-4">
                                <label for="image_tablet">{{ __('app.image') }} (Tablet) (404x200)</label>
                                <input type="file" id="image_tablet" name="image_tablet" data-plugins="dropify" data-height="200"
                                    data-allowed-file-extensions="png jpg jpeg webp" />
                            </div>
                            <div class="form-group col-4">
                                <label for="image_mobile">{{ __('app.image') }} (Mobile) (404x200)</label>
                                <input type="file" id="image_mobile" name="image_mobile" data-plugins="dropify" data-height="200"
                                    data-allowed-file-extensions="png jpg jpeg webp" />
                            </div>

                            <!-- Image EN -->
                            <div class="form-group col-6 d-none">
                                <label for="image_en">{{ __('app.image') }} (EN)</label>
                                <input type="file" id="image_en" name="image_en" data-plugins="dropify"
                                    data-height="200" data-allowed-file-extensions="png jpg jpeg webp" />
                            </div>

                            @if ($type == 'crowdfunding' || $type == 'projects')
                                <!-- Accounting Label -->
                                <div class="form-group col-md-4">
                                    <label for="analyticـaccount">{{ __('app.account label') }}</label>
                                    <select class="form-control" name="analyticـaccount" id="analyticـaccount">
                                        <option value="">{{ __('app.select') }}</option>
                                        @foreach($analyticـaccounts as $item)
                                            <option value="{{ $item->odoo_id }}">{{ $item->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <!-- Category -->
                            <div class="form-group col-md-4">
                                <label for="category_id">{{ __('app.category') }}</label>
                                <select name="category_id" id="category_id" class="form-control">
                                    <option value="">{{ __('app.choose') }}</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Payment Type -->
                            @if($type == 'projects')
                                <div class="form-group col-md-4">
                                    <label for="payment_type">{{ __('app.payment_type') }}</label>
                                    <select name="payment_type" id="payment_type" class="form-control">
                                        <option value="Both" {{ old('payment_type', 'Both') == 'Both' ? 'selected' : '' }}>Both</option>
                                        <option value="One-Time" {{ old('payment_type') == 'One-Time' ? 'selected' : '' }}>One-Time</option>
                                        <option value="Subscription" {{ old('payment_type') == 'Subscription' ? 'selected' : '' }}>Subscription</option>
                                    </select>
                                </div>
                            @endif
                            @if($type == 'projects')
                            <!-- Amount -->
                            <div class="form-group col-md-2">
                                <label for="amount">{{ __('app.amount') }} (JOD)</label>
                                <input type="number" step="any" id="amount" name="amount" placeholder="{{ __('app.amount') }}"
                                    value="{{ old('amount') }}" class="form-control">
                            </div>

                            <!-- Amount -->
                            <div class="form-group col-md-2">
                                <label for="amount_usd">{{ __('app.amount') }} (USD)</label>
                                <input type="number" step="any" id="amount" name="amount_usd" placeholder="{{ __('app.amount') }}"
                                       value="{{ old('amount_usd') }}" class="form-control">
                            </div>
                            @endif
                            @if($type == 'crowdfunding')
                                <!-- Amount -->
                                <div class="form-group col-md-2">
                                    <label for="target">{{ __('app.target') }} (JOD)</label>
                                    <input type="number" step="any" id="target" name="amount" placeholder="{{ __('app.target') }}"
                                           value="{{ old('amount') }}" class="form-control">
                                </div>

                                <!-- Amount -->
                                <div class="form-group col-md-2">
                                    <label for="target_usd">{{ __('app.target') }} (USD)</label>
                                    <input type="number" step="any" id="target_usd" name="amount_usd" placeholder="{{ __('app.target') }}"
                                           value="{{ old('amount_usd') }}" class="form-control">
                                </div>
                            @endif

                            @if ($type == 'projects')
                            <div class="col-12">
                                <table class="table table-light">
                                    <thead>
                                        <tr>
                                            <th>Dropdown 1</th>
                                            <th>Dropdown 2</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="form-row">
                                                    <div class="form-group col-6">
                                                        <label for="dropdown_1_option_1">Option 1 (AR)</label>
                                                        <input type="text" id="dropdown_1_option_1" name="dropdown_1_option_1" placeholder="Option 1 (AR)" class="form-control">
                                                    </div>
                                                    <div class="form-group col-6">
                                                        <label for="dropdown_1_option_1_en">Option 1 (EN)</label>
                                                        <input type="text" id="dropdown_1_option_1_en" name="dropdown_1_option_1_en" placeholder="Option 1 (EN)" class="form-control">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-row">
                                                    <div class="form-group col-6">
                                                        <label for="dropdown_2_option_1">Option 1 (AR)</label>
                                                        <input type="text" id="dropdown_2_option_1" name="dropdown_2_option_1" placeholder="Option 1 (AR)" class="form-control">
                                                    </div>
                                                    <div class="form-group col-6">
                                                        <label for="dropdown_2_option_1_en">Option 1 (EN)</label>
                                                        <input type="text" id="dropdown_2_option_1_en" name="dropdown_2_option_1_en" placeholder="Option 1 (EN)" class="form-control">
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-row">
                                                    <div class="form-group col-6">
                                                        <label for="dropdown_1_option_2">Option 2 (AR)</label>
                                                        <input type="text" id="dropdown_1_option_2" name="dropdown_1_option_2" placeholder="Option 2 (AR)" class="form-control">
                                                    </div>
                                                    <div class="form-group col-6">
                                                        <label for="dropdown_1_option_2_en">Option 2 (EN)</label>
                                                        <input type="text" id="dropdown_1_option_2_en" name="dropdown_1_option_2_en" placeholder="Option 2 (EN)" class="form-control">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-row">
                                                    <div class="form-group col-6">
                                                        <label for="dropdown_2_option_2">Option 2 (AR)</label>
                                                        <input type="text" id="dropdown_2_option_2" name="dropdown_2_option_2" placeholder="Option 2 (AR)" class="form-control">
                                                    </div>
                                                    <div class="form-group col-6">
                                                        <label for="dropdown_2_option_2_en">Option 2 (EN)</label>
                                                        <input type="text" id="dropdown_2_option_2_en" name="dropdown_2_option_2_en" placeholder="Option 2 (EN)" class="form-control">
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <button class="btn btn-secondary btn-generate" type="button">Generate</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-12 d-none mt-2" id="generated_prices">
                                <table class="table table-light">
                                    <thead>
                                        <tr>
                                            <th colspan="4">Price Options</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="tr-1">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <input type="radio" name="is_default" value="1" class="form-check-input mr-2">
                                                    <input type="text" class="form-control" name="prices_option_1" id="prices_option_1" placeholder="Option" style="width: 100%;" readonly>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" step="any" class="form-control" name="price_1_jod" id="price_1_jod" placeholder="Price (JOD)">
                                            </td>
                                            <td>
                                                <input type="number" step="any" class="form-control" name="price_1_usd" id="price_1_usd" placeholder="Price (USD)">
                                            </td>
                                        </tr>

                                        <tr class="tr-2">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <input type="radio" name="is_default" value="2" class="form-check-input mr-2">
                                                    <input type="text" class="form-control" name="prices_option_2" id="prices_option_2" placeholder="Option" style="width: 100%;" readonly>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" step="any" class="form-control" name="price_2_jod" id="price_2_jod" placeholder="Price (JOD)">
                                            </td>
                                            <td>
                                                <input type="number" step="any" class="form-control" name="price_2_usd" id="price_2_usd" placeholder="Price (USD)">
                                            </td>
                                        </tr>

                                        <tr class="tr-3">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <input type="radio" name="is_default" value="3" class="form-check-input mr-2">
                                                    <input type="text" class="form-control" name="prices_option_3" id="prices_option_3" placeholder="Option" style="width: 100%;" readonly>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" step="any" class="form-control" name="price_3_jod" id="price_3_jod" placeholder="Price (JOD)">
                                            </td>
                                            <td>
                                                <input type="number" step="any" class="form-control" name="price_3_usd" id="price_3_usd" placeholder="Price (USD)">
                                            </td>
                                        </tr>

                                        <tr class="tr-4">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <input type="radio" name="is_default" value="4" class="form-check-input mr-2">
                                                    <input type="text" class="form-control" name="prices_option_4" id="prices_option_4" placeholder="Option" style="width: 100%;" readonly>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" step="any" class="form-control" name="price_4_jod" id="price_4_jod" placeholder="Price (JOD)">
                                            </td>
                                            <td>
                                                <input type="number" step="any" class="form-control" name="price_4_usd" id="price_4_usd" placeholder="Price (USD)">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @endif

                            @if ($type == 'crowdfunding')
                            <div class="col-md-12">
                                <label for="price">{{ __('app.prices') }}</label>
                            </div>

                            <div class="col-md-6">
                                <div class="form-row border p-3 mb-2">
                                    <div class="form-group col-md-12 d-flex align-items-center">
                                        <input type="text" id="price" name="price[0]"
                                            placeholder="{{ __('app.price') }} (JOD)" value="{{ old('price.0') }}"
                                            class="form-control mr-2">
                                        <input type="text" id="price" name="price[1]"
                                            placeholder="{{ __('app.price') }} (JOD)" value="{{ old('price.1') }}"
                                            class="form-control mr-2">
                                        <input type="text" id="price" name="price[2]"
                                            placeholder="{{ __('app.price') }} (JOD)" value="{{ old('price.2') }}"
                                            class="form-control mr-2">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-row border p-3 mb-2">
                                    <div class="form-group col-md-12 d-flex align-items-center">
                                        <input type="text" id="price_en" name="price_en[0]"
                                            placeholder="{{ __('app.price') }} (USD)" value="{{ old('price_en.0') }}"
                                            class="form-control mr-2">
                                        <input type="text" id="price_en" name="price_en[1]"
                                            placeholder="{{ __('app.price') }} (USD)" value="{{ old('price_en.1') }}"
                                            class="form-control mr-2">
                                        <input type="text" id="price_en" name="price_en[2]"
                                            placeholder="{{ __('app.price') }} (USD)" value="{{ old('price_en.2') }}"
                                            class="form-control mr-2">
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($type == 'crowdfunding')
                                <!-- Amount -->
                                <div class="form-group col-md-2">
                                    <label for="single_price">{{ __('app.single_price') }} (JOD)</label>
                                    <input type="number" step="any" id="single_price" name="single_price" placeholder="{{ __('app.single_price') }}"
                                           value="{{ old('single_price') }}" class="form-control">
                                </div>

                                <!-- Amount -->
                                <div class="form-group col-md-2">
                                    <label for="single_price_usd">{{ __('app.single_price') }} (USD)</label>
                                    <input type="number" step="any" id="single_price_usd" name="single_price_usd" placeholder="{{ __('app.single_price') }} (USD)"
                                           value="{{ old('single_price_usd') }}" class="form-control">
                                </div>
                            @endif
                            <div class="form-group col-md-12">
                                <label for="status" class="d-block">{{ __('app.status') }}</label>
                                <input type="checkbox" data-plugin="switchery" data-color="#1bb99a" name="status" value="1" checked />
                            </div>

                            <!-- Save Button -->
                            <div class="form-group col-md-12">
                                <button class='btn btn-primary px-4' type="submit" name="save_and_return" value="save_and_return">{{ __('app.save') }}</button>
                                <button class='btn btn-secondary px-4' type="submit" name="save" value="save">{{ __('app.save & create another') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
const translations = @json(__('app'));
</script>
@endsection
