@extends('layouts.admin.add')
@section('title'){{ __('app.edit details') }} @endsection

@section('content')
@include('includes.admin.header', ['label_name' => __('app.edit details')])

<div class="row">
    <div class="col-12">
        <div class="widget-rounded-circle card-box d-flex justify-content-between">
            <form class="w-100" action="{{ route('items.update', ['type' => $type, 'item' => $data->id]) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-row">
                            <!-- Validation messages -->
                            <div class="form-group col-md-12">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="ml-3 mb-0">
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
                                    value="{{ old('title', $data->title) }}" class="form-control">
                            </div>

                            <!-- Title EN -->
                            <div class="form-group col-md-6">
                                <label for="title_en">{{ __('app.title') }} (EN)</label>
                                <input type="text" id="title_en" name="title_en" placeholder="{{ __('app.title') }}"
                                    value="{{ old('title_en', $data->title_en) }}" class="form-control">
                            </div>

                            <!-- Description AR -->
                            <div class="form-group col-md-6">
                                <label for="description">{{ __('app.description') }} (AR)</label>
                                <textarea id="description" name="description" placeholder="{{ __('app.description') }}"
                                    class="form-control"
                                    rows="4">{{ old('description', $data->description) }}</textarea>
                            </div>

                            <!-- Description EN -->
                            <div class="form-group col-md-6">
                                <label for="description_en">{{ __('app.description') }} (EN)</label>
                                <textarea id="description_en" name="description_en"
                                    placeholder="{{ __('app.description') }}" class="form-control"
                                    rows="4">{{ old('description_en', $data->description_en) }}</textarea>
                            </div>

                            @if ($type == 'projects')
                            <!-- beneficiaries message AR -->
                            <div class="form-group col-md-6">
                                <label for="beneficiaries_message">{{ __('app.subscription beneficiaries message') }} (AR)</label>
                                <textarea id="beneficiaries_message" name="beneficiaries_message" placeholder="{{ __('app.subscription beneficiaries message') }}"
                                    class="form-control" rows="4">{{ old('beneficiaries_message', $data->beneficiaries_message) }}</textarea>
                            </div>

                            <!-- beneficiaries message EN -->
                            <div class="form-group col-md-6">
                                <label for="beneficiaries_message_en">{{ __('app.subscription beneficiaries message') }} (EN)</label>
                                <textarea id="beneficiaries_message_en" name="beneficiaries_message_en"
                                    placeholder="{{ __('app.subscription beneficiaries message') }}" class="form-control"
                                    rows="4">{{ old('beneficiaries_message_en', $data->beneficiaries_message_en) }}</textarea>
                            </div>

                            <!-- Has beneficiary -->
                            <div class="form-group col-md-12">
                                <label for="has_beneficiary" class="d-block">{{ __('app.has beneficiary') }}</label>
                                <input type="checkbox" data-plugin="switchery" data-color="#1bb99a" name="has_beneficiary" value="1" {{ old('has_beneficiary', $data->has_beneficiary) ? 'checked' : '' }} />
                            </div>
                            @endif

                            <!-- invoice description after payment AR -->
                            <div class="form-group col-md-6">
                                <label for="description_after_payment">{{ __('app.invoice description after payment') }} (AR)</label>
                                <textarea id="description_after_payment" name="description_after_payment" placeholder="{{ __('app.invoice description after payment') }}"
                                    class="form-control" rows="4">{{ old('description_after_payment', $data->description_after_payment) }}</textarea>
                            </div>

                            <!-- invoice description after payment EN -->
                            <div class="form-group col-md-6">
                                <label for="description_after_payment_en">{{ __('app.invoice description after payment') }} (EN)</label>
                                <textarea id="description_after_payment_en" name="description_after_payment_en"
                                    placeholder="{{ __('app.invoice description after payment') }}" class="form-control"
                                    rows="4">{{ old('description_after_payment_en', $data->description_after_payment_en) }}</textarea>
                            </div>

                            <!-- Location AR -->
                            <div class="form-group col-md-6">
                                <label for="location">{{ __('app.location') }} (AR)</label>
                                <input type="text" id="location" name="location" placeholder="{{ __('app.location') }}"
                                    value="{{ old('location', $data->location) }}" class="form-control">
                            </div>

                            <!-- Location EN -->
                            <div class="form-group col-md-6">
                                <label for="location_en">{{ __('app.location') }} (EN)</label>
                                <input type="text" id="location_en" name="location_en"
                                    placeholder="{{ __('app.location') }}"
                                    value="{{ old('location_en', $data->location_en) }}" class="form-control">
                            </div>

                            <!-- Image AR -->
                            <div class="form-group col-4">
                                <label for="image">{{ __('app.image') }} (Web) (404x200)</label>
                                <input type="file" id="image" name="image" data-plugins="dropify"
                                    data-default-file="{{ old('image', $data->image) }}" data-height="200"
                                    data-allowed-file-extensions="png jpg jpeg webp" />
                                @include('includes.admin.image-upload-notes', [
                                    'recommendedSize' => '404 x 200 px',
                                    'maxSize' => '5 MB',
                                    'extensions' => 'png,jpg,jpeg,webp'
                                ])
                            </div>
                            <div class="form-group col-4">
                                <label for="image_tablet">{{ __('app.image') }} (Tablet) (404x200)</label>
                                <input type="file" id="image_tablet" name="image_tablet" data-plugins="dropify" data-height="200"
                                    data-default-file="{{ old('image_tablet', $data->image_tablet) }}" data-allowed-file-extensions="png jpg jpeg webp" />
                                @include('includes.admin.image-upload-notes', [
                                    'recommendedSize' => '404 x 200 px',
                                    'maxSize' => '5 MB',
                                    'extensions' => 'png,jpg,jpeg,webp'
                                ])
                            </div>
                            <div class="form-group col-4">
                                <label for="image_mobile">{{ __('app.image') }} (Mobile) (404x200)</label>
                                <input type="file" id="image_mobile" name="image_mobile" data-plugins="dropify" data-height="200"
                                    data-default-file="{{ old('image_mobile', $data->image_mobile) }}" data-allowed-file-extensions="png jpg jpeg webp" />
                                @include('includes.admin.image-upload-notes', [
                                    'recommendedSize' => '404 x 200 px',
                                    'maxSize' => '5 MB',
                                    'extensions' => 'png,jpg,jpeg,webp'
                                ])
                            </div>

                            <!-- Image EN -->
                            <div class="form-group col-6 d-none">
                                <label for="image_en">{{ __('app.image') }} (EN)</label>
                                <input type="file" id="image_en" name="image_en" data-plugins="dropify"
                                    data-default-file="{{ old('image_en', $data->getImageENAttribute()) }}"
                                    data-height="200" data-allowed-file-extensions="png jpg jpeg webp" />
                            </div>

                            @if ($type == 'crowdfunding' || $type == 'projects')
                                <div class="form-group col-md-4">
                                    <label for="analyticـaccount">{{ __('app.account label') }}</label>
                                    <select class="form-control" name="analyticـaccount" id="analyticـaccount">
                                        <option value="">{{ __('app.select') }}</option>
                                        @foreach($analyticـaccounts as $item)
                                            <option value="{{ $item->odoo_id }}" {{ $item->odoo_id == $data->analyticـaccount ? 'selected' : '' }}>{{ $item->value }}</option>
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
                                        <option value="{{ $category->id }}" {{ $data->category_id == $category->id ? 'selected' : '' }}>{{ $category->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Payment Type -->
                            @if($type == 'projects')
                                <div class="form-group col-md-4">
                                    <label for="payment_type">{{ __('app.payment_type') }}</label>
                                    <select name="payment_type" id="payment_type" class="form-control">
                                        <option value="Both" {{ old('payment_type', $data->payment_type) == 'Both' ? 'selected' : '' }}>{{ __('app.both') }}</option>
                                        <option value="One-Time" {{ old('payment_type', $data->payment_type) == 'One-Time' ? 'selected' : '' }}>{{ __('app.one_time') }}</option>
                                        <option value="Subscription" {{ old('payment_type', $data->payment_type) == 'Subscription' ? 'selected' : '' }}>{{ __('app.subscription') }}</option>
                                    </select>
                                </div>
                            @endif
                            @if($type == 'projects')
                            <!-- Amount -->
                            <div class="form-group col-md-2">
                                <label for="amount">{{ __('app.amount') }}</label>
                                <input type="number" step="any" id="amount" name="amount" placeholder="{{ __('app.amount') }}"
                                    value="{{ old('amount', $data->amount) }}" class="form-control">
                            </div>

                            <!-- Amount USD -->
                            <div class="form-group col-md-2">
                                <label for="amount_usd">{{ __('app.amount') }} (USD)</label>
                                <input type="number" step="any" id="amount_usd" name="amount_usd" placeholder="{{ __('app.amount') }}"
                                       value="{{ old('amount_usd', $data->amount_usd) }}" class="form-control">
                            </div>
                            @endif

                            @if($type == 'crowdfunding')
                                <!-- Amount -->
                                <div class="form-group col-md-2">
                                    <label for="target">{{ __('app.target') }}</label>
                                    <input type="number" step="any" id="target" name="amount" placeholder="{{ __('app.target') }}"
                                           value="{{ old('amount', $data->amount) }}" class="form-control">
                                </div>

                                <!-- Amount USD -->
                                <div class="form-group col-md-2">
                                    <label for="target_usd">{{ __('app.target') }} (USD)</label>
                                    <input type="number" step="any" id="target_usd" name="amount_usd" placeholder="{{ __('app.target') }}"
                                           value="{{ old('amount_usd', $data->amount_usd) }}" class="form-control">
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
                                                        <input type="text" id="dropdown_1_option_1" name="dropdown_1_option_1" value="{{ $options["d1_options"][0]["ar"] ?? '' }}" placeholder="Option 1 (AR)" class="form-control">
                                                    </div>
                                                    <div class="form-group col-6">
                                                        <label for="dropdown_1_option_1_en">Option 1 (EN)</label>
                                                        <input type="text" id="dropdown_1_option_1_en" name="dropdown_1_option_1_en" value="{{ $options["d1_options"][0]["en"] ?? '' }}" placeholder="Option 1 (EN)" class="form-control">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-row">
                                                    <div class="form-group col-6">
                                                        <label for="dropdown_2_option_1">Option 1 (AR)</label>
                                                        <input type="text" id="dropdown_2_option_1" name="dropdown_2_option_1" value="{{ $options["d2_options"][0]["ar"] ?? '' }}" placeholder="Option 1 (AR)" class="form-control">
                                                    </div>
                                                    <div class="form-group col-6">
                                                        <label for="dropdown_2_option_1_en">Option 1 (EN)</label>
                                                        <input type="text" id="dropdown_2_option_1_en" name="dropdown_2_option_1_en" value="{{ $options["d2_options"][0]["en"] ?? '' }}" placeholder="Option 1 (EN)" class="form-control">
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-row">
                                                    <div class="form-group col-6">
                                                        <label for="dropdown_1_option_2">Option 2 (AR)</label>
                                                        <input type="text" id="dropdown_1_option_2" name="dropdown_1_option_2" value="{{ $options["d1_options"][1]["ar"] ?? '' }}" placeholder="Option 2 (AR)" class="form-control">
                                                    </div>
                                                    <div class="form-group col-6">
                                                        <label for="dropdown_1_option_2_en">Option 2 (EN)</label>
                                                        <input type="text" id="dropdown_1_option_2_en" name="dropdown_1_option_2_en" value="{{ $options["d1_options"][1]["en"] ?? '' }}" placeholder="Option 2 (EN)" class="form-control">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-row">
                                                    <div class="form-group col-6">
                                                        <label for="dropdown_2_option_2">Option 2 (AR)</label>
                                                        <input type="text" id="dropdown_2_option_2" name="dropdown_2_option_2" value="{{ $options["d2_options"][1]["ar"] ?? '' }}" placeholder="Option 2 (AR)" class="form-control">
                                                    </div>
                                                    <div class="form-group col-6">
                                                        <label for="dropdown_2_option_2_en">Option 2 (EN)</label>
                                                        <input type="text" id="dropdown_2_option_2_en" name="dropdown_2_option_2_en" value="{{ $options["d2_options"][1]["en"] ?? '' }}" placeholder="Option 2 (EN)" class="form-control">
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


                                <div class="col-12 mt-2
                                @if(empty($options_placeholders[1]) && empty($options_placeholders[2]) && empty($options_placeholders[3]) && empty($options_placeholders[4]))
                                d-none
                                @endif
                                " id="generated_prices">
                                    <table class="table table-light">
                                        <thead>
                                        <tr>
                                            <th colspan="3">Price Options</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="tr-1">
                                            <td>
                                                <label for="prices_option_1">Option</label>
                                                <input type="text" class="form-control" name="prices_option_1" value="{{ $options_placeholders[1] ?? '' }}" id="prices_option_1" readonly>
                                            </td>
                                            <td>
                                                <label for="price_1_jod">Price (JOD)</label>
                                                <input type="number" step="any" class="form-control" name="price_1_jod" value="{{ $options_price[1][0] ?? '' }}" id="price_1_jod">
                                            </td>
                                            <td>
                                                <label for="price_1_usd">Price (USD)</label>
                                                <input type="number" step="any" class="form-control" name="price_1_usd" value="{{ $options_price[1][1] ?? '' }}" id="price_1_usd">
                                            </td>
                                        </tr>
                                        <tr class="tr-2 @if(empty($options_placeholders[2])) d-none @endif">
                                            <td>
                                                <label for="prices_option_2">Option</label>
                                                <input type="text" class="form-control" name="prices_option_2" value="{{ $options_placeholders[2] ?? '' }}" id="prices_option_2" readonly>
                                            </td>
                                            <td>
                                                <label for="price_2_jod">Price (JOD)</label>
                                                <input type="number" step="any" class="form-control" name="price_2_jod" value="{{ $options_price[2][0] ?? '' }}" id="price_2_jod">
                                            </td>
                                            <td>
                                                <label for="price_2_usd">Price (USD)</label>
                                                <input type="number" step="any" class="form-control" name="price_2_usd" value="{{ $options_price[2][1] ?? '' }}" id="price_2_usd">
                                            </td>
                                        </tr>
                                        <tr class="tr-3">
                                            <td>
                                                <label for="prices_option_3">Option</label>
                                                <input type="text" class="form-control" name="prices_option_3" value="{{ $options_placeholders[3] ?? '' }}" id="prices_option_3" readonly>
                                            </td>
                                            <td>
                                                <label for="price_3_jod">Price (JOD)</label>
                                                <input type="number" step="any" class="form-control" name="price_3_jod" value="{{ $options_price[3][0] ?? '' }}" id="price_3_jod">
                                            </td>
                                            <td>
                                                <label for="price_3_usd">Price (USD)</label>
                                                <input type="number" step="any" class="form-control" name="price_3_usd" value="{{ $options_price[3][1] ?? '' }}" id="price_3_usd">
                                            </td>
                                        </tr>
                                        <tr class="tr-4 @if(empty($options_placeholders[4])) d-none @endif">
                                            <td>
                                                <label for="prices_option_4">Option</label>
                                                <input type="text" class="form-control" name="prices_option_4" value="{{ $options_placeholders[4] ?? '' }}" id="prices_option_4" readonly>
                                            </td>
                                            <td>
                                                <label for="price_4_jod">Price (JOD)</label>
                                                <input type="number" step="any" class="form-control" name="price_4_jod" value="{{ $options_price[4][0] ?? '' }}" id="price_4_jod">
                                            </td>
                                            <td>
                                                <label for="price_4_usd">Price (USD)</label>
                                                <input type="number" step="any" class="form-control" name="price_4_usd" value="{{ $options_price[4][1] ?? '' }}" id="price_4_usd">
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                            @if ($type == 'crowdfunding')
                            <div class="col-md-6">
                                <label>{{ __('app.prices') }} (JOD)</label>
                                <div class="form-row border p-3 mb-2">
                                    @foreach ($prices as $key => $price)
                                        <div class="form-group col-md-4 d-flex align-items-center">
                                            <input type="text" name="price[{{ $key }}][value]"
                                                placeholder="{{ __('app.price') }} (JOD)"
                                                value="{{ old('price.' . $key . '.value', $price->price) }}"
                                                class="form-control mr-2">
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label>{{ __('app.prices') }} (USD)</label>
                                <div class="form-row border p-3 mb-2">
                                    @foreach ($prices as $key => $price)
                                        <div class="form-group col-md-4 d-flex align-items-center">
                                            <input type="text" name="price_en[{{ $key }}][value]"
                                                placeholder="{{ __('app.price') }} (USD)"
                                                value="{{ old('price_en.' . $key . '.value', $price->price_en) }}"
                                                class="form-control mr-2">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            @if($type == 'crowdfunding')
                                <!-- Amount -->
                                <div class="form-group col-md-2">
                                    <label for="single_price">{{ __('app.single_price') }} (JOD)</label>
                                    <input type="number" step="any" id="single_price" name="single_price" placeholder="{{ __('app.single_price') }}"
                                           value="{{ old('single_price', $data->single_price) }}" class="form-control">
                                </div>

                                <!-- Amount -->
                                <div class="form-group col-md-2">
                                    <label for="single_price_usd">{{ __('app.single_price') }} (USD)</label>
                                    <input type="number" step="any" id="single_price_usd" name="single_price_usd" placeholder="{{ __('app.single_price') }} (USD)"
                                           value="{{ old('single_price_usd', $data->single_price_usd) }}" class="form-control">
                                </div>
                            @endif
                            
                            <div class="form-group col-md-12">
                                <label for="status" class="d-block">{{ __('app.status') }}</label>
                                <input type="checkbox" data-plugin="switchery" data-color="#1bb99a" name="status" value="1" @if($data->status == 1) checked @endif />
                            </div>

                            <!-- Save Button -->
                            <div class="form-group col-md-12">
                                <button class="btn btn-primary px-4">{{ __('app.save') }}</button>
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
    const oldOptions = @json(old('options', $data->priceOptions->toArray())); // Pass old options to JS
</script>
@endsection
