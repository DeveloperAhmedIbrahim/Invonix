<div class="row">
    <div class="col-lg-6 col-sm-12 mb-5">
        {{ Form::label('client_id', __('messages.quote.client') . ':', ['class' => 'form-label mb-3']) }}
        {{ Form::text('client', getLogInUser()->full_name, ['class' => 'form-control', 'readonly']) }}
        {{ Form::hidden('client_id', getLogInUserId(), ['class' => 'form-control', 'id' => 'client_id']) }}
    </div>
    <div class="col-lg-6 col-sm-12 mb-5">
        {{ Form::label('quote_id', __('messages.quote.quote') . ' #', ['class' => 'form-label mb-3']) }}
        {{ Form::text('quote_id', \App\Models\Quote::generateUniqueQuoteId(), ['class' => 'form-control', 'required', 'id' => 'quoteId', 'maxlength' => 6, 'onkeypress' => 'return blockSpecialChar(event)']) }}
    </div>
    <div class="col-lg-6 col-sm-12 mb-5">
        {{ Form::label('quote_date', __('messages.quote.quote_date') . ':', ['class' => 'form-label required mb-3']) }}
        {{ Form::text('quote_date', null, ['class' => 'form-select', 'id' => 'client_quote_date', 'autocomplete' => 'off', 'required']) }}
    </div>
    <div class="col-lg-6 col-sm-12 mb-5">
        {{ Form::label('due_date', __('messages.quote.due_date') . ':', ['class' => 'form-label required mb-3']) }}
        {{ Form::text('due_date', null, ['class' => 'form-select', 'id' => 'clientQuoteDueDate', 'autocomplete' => 'off', 'required']) }}
    </div>
    <div class="col-lg-6 col-sm-12">
        <div class="mb-5">
            {{ Form::label('status', __('messages.common.status') . ':', ['class' => 'form-label required mb-3']) }}
            {{ Form::select('status', getTranslatedData($statusArr), null, ['class' => 'form-select io-select2', 'id' => 'status', 'required', 'data-control' => 'select2']) }}
        </div>
    </div>
    <div class="col-lg-6 col-sm-12 mb-5">
        {{ Form::label('templateId', __('messages.setting.invoice_template') . ':', ['class' => 'form-label mb-3']) }}
        {{ Form::select('template_id', $template, \App\Models\Setting::DEFAULT_TEMPLATE ?? null, ['class' => 'form-select io-select2', 'id' => 'templateId', 'required', 'data-control' => 'select2']) }}
    </div>
    <div class="mb-0">
        <div class="col-12 text-end my-5">
            <button type="button" class="btn btn-primary text-start" id="addClientQuoteItem">
                {{ __('messages.quote.add') }}</button>
        </div>
        <div class="table-responsive">
            <table class="table table-striped box-shadow-none mt-4" id="billTbl">
                <thead>
                    <tr class="border-bottom fs-7 fw-bolder text-gray-700 text-uppercase">
                        <th scope="col">#</th>
                        <th scope="col" class="required">{{ __('messages.product.product') }}</th>
                        <th scope="col" class="required">{{ __('messages.quote.qty') }}</th>
                        <th scope="col" class="required">{{ __('messages.product.unit_price') }}</th>
                        <th scope="col">{{ __('messages.invoice.tax') }}</th>
                        <th scope="col" class="required">{{ __('messages.quote.amount') }}</th>
                        <th scope="col" class="text-end">{{ __('messages.common.action') }}</th>
                    </tr>
                </thead>
                <tbody class="quote-item-container">
                    <tr class="tax-tr">
                        <td class="text-center item-number align-center">1</td>
                        <td class="table__item-desc w-25">
                            {{ Form::select('product_id[]', $products, null, ['class' => 'form-select client-product-quote io-select2', 'required', 'placeholder' => 'Select Product or Enter free text', 'data-control' => 'select2']) }}
                        </td>
                        <td class="table__qty">
                            {{ Form::number('quantity[]', null, ['class' => 'form-control qty-quote', 'required', 'type' => 'number', 'min' => 1, 'oninput' => "this.value = this.value.replace(/\./g, '').replace(/\D/g, '')"]) }}
                        </td>
                        <td>
                            {{ Form::number('price[]', null, ['class' => 'form-control price-input price-quote', 'oninput' => "validity.valid||(value=value.replace(/[e\+\-]/gi,''))", 'min' => '0', 'value' => '0', 'step' => '.01', 'pattern' => "^\d*(\.\d{0,2})?$", 'required', 'onKeyPress' => 'if(this.value.length==8) return false;']) }}
                        </td>
                        <td>
                            <select name="tax[]" class='form-select io-select2 fw-bold taxQuote'
                                data-control='select2' multiple="multiple">
                                @foreach ($taxes as $tax)
                                    <option value="{{ $tax->value }}" data-id="{{ $tax->id }}">
                                        {{ $tax->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="quote-item-total pt-8 text-nowrap">
                            @if (!getSettingValue('currency_after_amount'))
                                <span>{{ getCurrencySymbol() }}</span>
                            @endif
                            0.00
                            @if (getSettingValue('currency_after_amount'))
                                <span>{{ getCurrencySymbol() }}</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <button type="button" title="{{ __('messages.common.delete') }}"
                                class="btn btn-icon fs-3 text-danger btn-active-color-danger delete-quote-item">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-lg-7 col-sm-12 mt-2 mt-lg-0 align-right-for-full-screen">
                <div class="mb-2 col-xl-5 col-lg-8 col-sm-12 float-right">
                    <label class="form-check form-switch form-check-custom mt-3">
                        <span class="form-check-label text-gray-600"
                            for="clientDiscounBeforeTax">{{ 'Discount %(applied before tax):' }}</span>
                        <input class="form-check-input" type="checkbox" name="discount_before_tax"
                            id="clientDiscounBeforeTax" disabled>
                        &nbsp;&nbsp;
                    </label>
                </div>
                <div class="mb-2 col-xl-6 col-lg-8 col-sm-12 float-right">
                    {{ Form::label('discount', __('messages.invoice.discount') . ':', ['class' => 'form-label mb-1']) }}
                    <div class="input-group">
                        {{ Form::number('discount', 0, ['id' => 'discountQuoteClient', 'class' => 'form-control ', 'oninput' => "validity.valid||(value=value.replace(/[e\+\-]/gi,''))", 'min' => '0', 'value' => '0', 'step' => '.01', 'pattern' => "^\d*(\.\d{0,2})?$"]) }}
                        <div class="input-group-append" style="width: 210px !important;">
                            {{ Form::select('discount_type', getTranslatedData($discount_type), 0, ['class' => 'form-select io-select2', 'id' => 'discountTypeQuoteClient', 'data-control' => 'select2']) }}
                        </div>
                    </div>
                </div>
                <div class="mb-2 col-xl-5 col-lg-8 col-sm-12 float-right">
                    {{ Form::label('tax2', __('messages.invoice.tax') . ':', ['class' => 'form-label mb-1']) }}
                    <select name="taxes[]" class='form-select io-select2 fw-bold  quote-taxes-client'
                        data-control='select2' multiple="multiple">
                        @foreach ($taxes as $tax)
                            <option value="{{ $tax->id }}" data-tax="{{ $tax->value }}">{{ $tax->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" id="taxes" value="{{ $taxes }}" />
            </div>
            <div class="col-xxl-3 col-lg-5 col-md-6 ms-md-auto mt-4 mb-lg-10 mb-6">
                <div class="border-top">
                    <table class="table table-borderless box-shadow-none mb-0 mt-5">
                        <tbody>
                            <tr>
                                <td class="ps-0">{{ __('messages.quote.sub_total') . ':' }}</td>
                                <td class="text-gray-900 text-end pe-0">
                                    @if (!getSettingValue('currency_after_amount'))
                                        <span>{{ getCurrencySymbol() }}</span>
                                    @endif
                                    <span id="quoteTotal" class="price">
                                        0
                                    </span>
                                    @if (getSettingValue('currency_after_amount'))
                                        <span>{{ getCurrencySymbol() }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-0">{{ __('messages.quote.discount') . ':' }}</td>
                                <td class="text-gray-900 text-end pe-0">
                                    @if (!getSettingValue('currency_after_amount'))
                                        <span>{{ getCurrencySymbol() }}</span>
                                    @endif
                                    <span id="quoteClientDiscountAmount">
                                        0
                                    </span>
                                    @if (getSettingValue('currency_after_amount'))
                                        <span>{{ getCurrencySymbol() }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-0">{{ __('messages.invoice.total_tax') . ':' }}</td>
                                <td class="text-gray-900 text-end pe-0">
                                    @if (!getSettingValue('currency_after_amount'))
                                        <span>{{ getCurrencySymbol() }}</span>
                                    @endif
                                    <span id="quoteClientTotalTax">
                                        0
                                    </span>
                                    @if (getSettingValue('currency_after_amount'))
                                        <span>{{ getCurrencySymbol() }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-0">{{ __('messages.quote.total') . ':' }}</td>
                                <td class="text-gray-900 text-end pe-0">
                                    @if (!getSettingValue('currency_after_amount'))
                                        <span>{{ getCurrencySymbol() }}</span>
                                    @endif
                                    <span id="quoteClientFinalAmount">
                                        0
                                    </span>
                                    @if (getSettingValue('currency_after_amount'))
                                        <span>{{ getCurrencySymbol() }}</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row justify-content-left">
            <div class="col-lg-12 col-md-12 col-sm-12 end justify-content-left mb-5">
                <button type="button" class="btn btn-primary note" id="quoteAddNote"><i class="fas fa-plus"></i>
                    {{ __('messages.quote.add_note_term') }}</button>
                <button type="button" class="btn btn-danger note" id="quoteRemoveNote"><i class="fas fa-minus"></i>
                    {{ __('messages.quote.remove_note_term') }}</button>
            </div>
            <div class="col-lg-6 mb-5 mt-5" id="quoteNoteAdd">
                {{ Form::label('note', __('messages.quote.note') . ':', ['class' => 'form-label fs-6 fw-bolder text-gray-700 mb-3']) }}
                {{ Form::textarea('note', null, ['class' => 'form-control', 'id' => 'quoteNote', 'rows' => '5']) }}
            </div>
            <div class="col-lg-6 mb-5 mt-5" id="quoteTermRemove">
                {{ Form::label('term', __('messages.quote.terms') . ':', ['class' => 'form-label fs-6 fw-bolder text-gray-700 mb-3']) }}
                {{ Form::textarea('term', null, ['class' => 'form-control', 'id' => 'quoteTerm', 'rows' => '5']) }}
            </div>
        </div>
    </div>
</div>

<!-- Total Amount Field -->
{{ Form::hidden('amount', 0, ['class' => 'form-control', 'id' => 'quoteTotalAmount']) }}

<!-- Final Amount Field -->
{{ Form::hidden('final_amount', 0, ['class' => 'form-control', 'id' => 'quoteClientFinalTotalAmt']) }}

<!-- Submit Field -->
<div class="float-end">
    <div class="form-group col-sm-12">
        <button type="button" name="draft" class="btn btn-primary mx-3" id="saveAsDraftClientQuote"
            data-status="0" value="0">{{ __('messages.common.save') }}
        </button>
        <a href="{{ route('client.quotes.index') }}"
            class="btn btn-secondary btn-active-light-primary">{{ __('messages.common.cancel') }}</a>
    </div>
</div>
