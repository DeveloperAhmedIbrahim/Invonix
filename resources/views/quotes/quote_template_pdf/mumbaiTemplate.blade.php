<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="icon" href="{{ asset('web/media/logos/favicon.ico') }}" type="image/png">
    <title>{{ __('messages.quote.quote_pdf') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/invoice-pdf.css') }}" rel="stylesheet" type="text/css" />
    <style>
        * {
            font-family: DejaVu Sans, Arial, "Helvetica", Arial, "Liberation Sans", sans-serif;
        }

        @page {
            margin-top: 30px !important;
        }

        @if (getCurrencySymbol($quote->tenant_id) == '€')
            .euroCurrency {
                font-family: Arial, "Helvetica", Arial, "Liberation Sans", sans-serif;
            }
        @endif
    </style>
</head>

<body style="padding: 0rem 2rem;">
    @php $styleCss = 'style'; @endphp
    <div class="preview-main client-preview mumbai-template">
        <div class="d" id="boxes">
            <div class="d-inner">
                <div class="top-border" style="background-color: {{ $quote_template_color }};"></div>
                <div style="background-color: {{ $quote_template_color }};">
                    <table class="pb-10 bg-white w-100 m-0 h-125px" style="overflow:hidden;">
                        <tr>
                            <td class=" p-0 m-0 " style="width:66%; overflow:hidden !important;  ">
                                <div class="bg-white  h-125px" style=" border-top-right-radius:30px; padding:24.5px;">
                                    <img width="100px" height="66px" src="{{ getLogoUrl($quote->tenant_id) }}"
                                        class="img-logo">
                                </div>
                            </td>
                            <td class="bg-white p-0 m-0 h-125px"
                                style="width:33%;  border-bottom-left-radius:30px; overflow:hidden;">
                                <div class="text-end p-4 pt-10 h-125px"
                                    style=" background-color: {{ $quote_template_color }};">
                                    <h1 class="m-0 pt-4 text-white pe-2"
                                        style=" font-size: 40px; font-weight:700; letter-spacing: 4px;">
                                        {{ __('messages.quote.quote_name') }}</h1>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div class="px-4 py-4 bg-white" style="margin-right:10px;">
                        <div class="pb-3">
                            <table class="mb-10 w-100">
                                <tbody>
                                    <tr style="vertical-align:top;">
                                        <td width="33.33%">
                                            <p class="fs-6 mb-2 font-gray-900">
                                                <strong>{{ __('messages.common.to') . ':' }}</strong>
                                            </p>
                                            <p class=" mb-1 font-color-gray fs-6">
                                                {{ __('messages.common.name') . ':' }}
                                                <span class="font-gray-900">{{ $client->user->full_name }}</span>
                                            </p>
                                            <p class=" mb-1 font-color-gray fs-6">
                                                {{ __('messages.common.email') . ':' }}
                                                <span class="font-gray-900">{{ $client->user->email }}</span>
                                            </p>
                                            <p class="mb-1 font-color-gray fs-6">
                                                {{ __('messages.common.address') . ':' }}
                                                <span class="font-gray-900">{{ $client->address }} </span>
                                            </p>
                                            @if (!empty($client->vat_no))
                                                <p class="mb-1 font-color-gray fs-6">
                                                    {{ getVatNoLabel() . ':' }}
                                                    <span class="font-gray-900">{{ $client->vat_no }} </span>
                                                </p>
                                            @endif
                                        </td>
                                        <td width="33.33%">
                                            <p class="fs-6 mb-2 font-gray-900">
                                                <strong>{{ __('messages.common.from') . ':' }}</strong>
                                            </p>
                                            <p class=" mb-1 font-color-gray fs-6">
                                                {{ __('messages.common.name') . ':' }}
                                                <span class="font-gray-900">{!! $setting['company_name'] !!}</span>
                                            </p>
                                            <p class=" mb-1 font-color-gray fw-bold fs-6">
                                                {{ __('messages.common.address') . ':' }}&nbsp; <span
                                                    class="font-gray-900">{!! $setting['company_address'] !!}</span></p>

                                            <p class=" mb-1 font-color-gray  fw-bold fs-6">
                                                {{ __('messages.user.phone') . ':' }}&nbsp; <span
                                                    class="font-gray-900">{{ $setting['company_phone'] }}</span></p>
                                            @if (!empty($setting['gst_no']))
                                                <p class=" mb-1 font-color-gray  fw-bold fs-6">
                                                    {{ getVatNoLabel() . ':' }}
                                                    <span class="text-break font-gray-900">{{ $setting['gst_no'] }}
                                                    </span>
                                                </p>
                                            @endif

                                        </td>
                                        <td width="28%" class="text-end pt-7">
                                            <p class="mb-1 text-gray-600 fs-6"><strong
                                                    class="font-gray-900">{{ __('messages.quote.quote_id') . ':' }}
                                                </strong>#{{ $quote->quote_id }}
                                            </p>
                                            <p class="mb-1 text-gray-600 fs-6"><strong
                                                    class="font-gray-900">{{ __('messages.quote.quote_date') . ':' }}
                                                </strong>{{ \Carbon\Carbon::parse($quote->quote_date)->translatedFormat(currentDateFormat()) }}
                                            </p>
                                            <p class=" mb-1 text-gray-600 fs-6"><strong
                                                    class="font-gray-900">{{ __('messages.quote.due_date') . ':' }}&nbsp;
                                                </strong>{{ \Carbon\Carbon::parse($quote->due_date)->translatedFormat(currentDateFormat()) }}
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="overflow-auto">
                            <table class="invoice-table w-100">
                                <thead style="background-color: {{ $quote_template_color }};">
                                    <tr>
                                        <th class="p-2 text-uppercase" style="width:5% !important;">#</th>
                                        <th class="p-2 in-w-2 text-uppercase">{{ __('messages.product.product') }}</th>
                                        <th class="p-2 text-center text-uppercase" style="width:10% !important;">
                                            {{ __('messages.invoice.qty') }}
                                        </th>
                                        <th class="p-2 text-center  text-nowrap text-uppercase"
                                            style="width:18% !important;">
                                            {{ __('messages.product.unit_price') }}</th>
                                        <th class="p-2 text-center  text-nowrap text-uppercase"
                                            style="width:13% !important;">
                                            {{ __('messages.invoice.tax') . '(in %)' }}
                                        </th>
                                        <th class="p-2 text-end  text-nowrap text-uppercase"
                                            style="width:17% !important;">
                                            {{ __('messages.invoice.amount') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($quote) && !empty($quote))
                                        @foreach ($quote->quoteItems as $key => $quoteItems)
                                            <tr>
                                                <td class="p-2" style="width:5%;"><span>{{ $key + 1 }}</span>
                                                </td>
                                                <td class="p-2 in-w-2">
                                                    <p class="fw-bold mb-0">
                                                        {{ isset($quoteItems->product->name) ? $quoteItems->product->name : $quoteItems->product_name ?? __('messages.common.n/a') }}
                                                    </p>
                                                    @if (
                                                        !empty($quoteItems->product->description) &&
                                                            (isset($setting['show_product_description']) && $setting['show_product_description'] == 1))
                                                        <span
                                                            style="font-size: 12px; word-break: break-all">{{ $quoteItems->product->description }}</span>
                                                    @endif
                                                </td>
                                                <td class="p-2 text-center">{{ $quoteItems->quantity }}
                                                </td>
                                                <td class="p-2 text-center text-nowrap euroCurrency">
                                                    {{ isset($quoteItems->price) ? getCurrencyAmount($quoteItems->price, true) : __('messages.common.n/a') }}
                                                </td>
                                                <td class="p-2 text-center">
                                                    @foreach ($quoteItems->quoteItemTax as $keys => $tax)
                                                        {{ $tax->tax ?? '--' }}
                                                        @if (!$loop->last)
                                                            ,
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td class="p-2 text-end text-nowrap euroCurrency">
                                                    {{ isset($quoteItems->total) ? getCurrencyAmount($quoteItems->total, true) : __('messages.common.n/a') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="my-5">
                            <table class="w-100">
                                <tr>
                                    <td style="vertical-align:bottom; width:60%;">
                                    </td>
                                    <td style="vertical-align:top; width:40%;">
                                        <table class="w-100">
                                            <tbody>
                                                <tr>
                                                    <td class="py-1 px-2">
                                                        <strong>{{ __('messages.quote.amount') . ':' }}</strong>
                                                    </td>
                                                    <td class="text-nowrap text-end font-gray-600 py-1 px-2 fw-bold">
                                                        {{ getCurrencyAmount($quote->amount, true) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="py-1 px-2">
                                                        <strong>{{ __('messages.quote.discount') . ':' }}</strong>
                                                    </td>
                                                    <td class="text-end font-gray-600 py-1 px-2 fw-bold">
                                                        @if ($quote->discount == 0)
                                                            <span>{{ __('messages.common.n/a') }}</span>
                                                        @else
                                                            @if (isset($quote) && $quote->discount_type == \App\Models\Quote::FIXED)
                                                                <b
                                                                    class="euroCurrency">{{ isset($quote->discount) ? getCurrencyAmount($quote->discount, true) : __('messages.common.n/a') }}</b>
                                                            @else
                                                                {{ $quote->discount }}<span
                                                                    {{ $styleCss }}="font-family: DejaVu Sans">
                                                                    &#37;</span>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    @php
                                                        $itemTaxesAmount = $quote->amount + array_sum($totalTax);
                                                        $quoteTaxesAmount =
                                                            ($itemTaxesAmount * $quote->qouteTaxes->sum('value')) / 100;
                                                        $totalTaxes = array_sum($totalTax) + $quoteTaxesAmount;
                                                    @endphp
                                                    <td class="py-1 px-2">
                                                        <strong>{{ __('messages.invoice.tax') . ':' }}</strong>
                                                    </td>
                                                    <td class="text-end font-gray-600 py-1 px-2 fw-bold">
                                                        {!! numberFormat($totalTaxes) != 0
                                                            ? '<b class="euroCurrency">' . getCurrencyAmount($totalTaxes, true) . '</b>'
                                                            : __('messages.common.n/a') !!}
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot class="total-amount"
                                                {{ $styleCss }}="background-color: {{ $quote_template_color }};">
                                                <tr>
                                                    <td class="p-2">
                                                        <strong>{{ __('messages.quote.total') . ':' }}</strong>
                                                    </td>
                                                    <td class="text-nowrap text-end p-2">
                                                        <strong>{{ getCurrencyAmount($quote->final_amount, true) }}</strong>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        @if (!empty($quote->note))
                            <div class="mb-5">
                                <h6 class="font-gray-900 mb5"><b>{{ __('messages.client.notes') . ':' }}</b></h6>
                                <p class="font-gray-600">{!! nl2br($quote->note ?? __('messages.common.n/a')) !!}
                                </p>
                            </div>
                        @endif
                        @if (!empty($quote->term))
                            <div class="w-75">
                                <h6 class="font-gray-900 mb5">
                                    <b>{{ __('messages.invoice.terms') . ':' }}</b>
                                </h6>
                                <p class="font-gray-600 mb-0">{!! nl2br($quote->term ?? __('messages.common.n/a')) !!}</p>
                            </div>
                        @endif
                        <div class="w-25 text-end" style="position:relative; margin-left:auto; top:-20px;">
                            <h5 class="text-indigo mt-2" {{ $styleCss }}="color:{{ $quote_template_color }}">
                                <b>{{ __('messages.setting.regards') . ':' }}</b>
                            </h5>
                            <p class="fs-6 mb-0"><b>
                                    {{ html_entity_decode(!empty($setting['app_name']) ? $setting['app_name'] : $userSetting['app_name']) }}</b>
                            </p>
                        </div>
                    </div>
                    <div>
                        <table class="bg-white w-100">
                            <tr>
                                <td class=" p-0 h-25px" style="width:80% !important; overflow:hidden;">
                                    <div class="bg-white   h-25px"
                                        style=" border-bottom-right-radius:30px; padding:26px;">
                                    </div>
                                </td>
                                <td class="bg-white p-0 h-25px"
                                    style="width:20%; border-top-left-radius:35px; overflow:hidden;">
                                    <div class="text-end   h-25px"
                                        style="background-color: {{ $quote_template_color }};  padding:26px;">
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="bottom-border" style="background-color: {{ $quote_template_color }};"></div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
