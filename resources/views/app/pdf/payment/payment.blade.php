<!DOCTYPE html>
<html>

<head>
    <title>@lang('pdf_payment_label') - {{$payment->payment_number}}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style type="text/css">
        /* -- Base -- */
        body {
            font-family: "DejaVu Sans";
        }

        html {
            margin: 0px;
            padding: 0px;
            margin-top: 50px;
        }

        table {
            border-collapse: collapse;
        }

        hr {
            color: rgba(0, 0, 0, 0.2);
            border: 0.5px solid #EAF1FB;
        }

        /* -- Heeader -- */

        .header-container {
            /* position: absolute; */
            width: 100%;
            padding: 0 30px;
            margin-bottom: 0;
            /* height: 150px;
            left: 0px;
            top: -60px; */
        }

         .header-section-left {
            padding-right: 30px;
        } 

        .header-logo {
            /* position: absolute; */
            height: 90px;
            text-transform: capitalize;
            color: #00008B;
            padding-top: 0px;
        }

        .company-address-container {
            text-transform: capitalize;
            padding-left: 80px;
            margin-bottom: 2px;
        }

        /* .header-section-right {
            display:inline-block;
            position: absolute;
            right:0;
            padding: 15px 30px 15px 0px;
            float: right;
        } */

        .header-section-right {
            padding-right: 60px;
            text-align: left;
        }

        .header {
            font-size: 20px;
            color: rgba(0, 0, 0, 0.7);
        }

        /* -- Company Address -- */

        .company-details h1 {
            margin: 0;
            font-weight: bold;
            font-size: 15px;
            line-height: 22px;
            letter-spacing: 0.05em;
            text-align: left;
        }

        .company-address {
            font-size: 12px;
            line-height: 15px;
            color: #595959;
            word-wrap: break-word;
        }

        .content-wrapper {
            display: block;
            height: 90px;
        }

        .main-content {
            display: inline-block;
            padding-top: 20px
        }

        /* -- Customer Address -- */
        .customer-address-container {
            display: block;
            float: left;
            padding: 0 0 0 30px;
        }

        

        /* -- Billing -- */

        .billing-address-container {
            display: block;
            float: left;
        }

        .billing-address-container--right {
            float: right;
        }

        .billing-address-label {
            padding-top: 5px;
            font-size: 12px;
            line-height: 18px;
            margin-bottom: 0px;
            color: #55547A;
        }

        .billing-address-name {
            padding: 0px;
            font-size: 15px;
            line-height: 22px;
            margin: 0px;
        }

        .billing-address {
            font-size: 11px;
            line-height: 15px;
            color: #595959;
            margin: 0px;
            width: 180px;
            word-wrap: break-word;
        }

        /* -- Payment Details -- */

        .payment-details-container {
            display: inline;
            position: absolute;
            float: right;
            width: 40%;
            height: 120px;
            padding: 5px 30px 0 0;
        }

        .attribute-label {
            font-size: 12px;
            line-height: 18px;
            text-align: left;
            color: #55547A
        }

        .attribute-value {
            font-size: 12px;
            line-height: 18px;
            text-align: left;
        }
        .total_payment_value {
            text-align: right !important;
            padding-left:30px;
            font-size: 12px;
            line-height: 18px;
        }
        .prepared_by{
            margin-top: 100px;
            font-size: 12px;
            line-height: 18px;
        }

        /* -- Notes -- */

        .notes {
            font-size: 12px;
            color: #595959;
            margin-top: 15px;
            margin-left: 30px;
            width: 442px;
            text-align: left;
            page-break-inside: avoid;
        }

        .notes-label {
            font-size: 15px;
            line-height: 22px;
            letter-spacing: 0.05em;
            color: #040405;
            height: 19.87px;
            padding: 10px 0px;
        }

        .content-heading {
            width: 100%;
            text-align: center;
        }

        p {
            padding: 0 0 0 0;
            margin: 0 0 0 0;
        }

        .content-heading span {
            font-weight:bold;
            font-size:18px;
            color:#00008B;
            padding-bottom: 5px;
        }

        /* -- Total Display Box -- */

        .total-display-box {
            /*width: 310px;*/
            display: block;
            /*background: #F9FBFF;*/
            box-sizing: border-box;
            padding: 12px 30px 15px 30px;
        }

        .total-display-label {
            display: inline;
            font-weight: bold;
            font-size: 14px;
            line-height: 21px;
            color: #595959;
        }

        .total-display-box .amount {
            float: right;
            font-weight: bold;
            font-size: 14px;
            line-height: 21px;
            text-align: right;
            color: #00008B;
        }
        
    </style>
</head>

<body>
    <div class="header-container">
        <table width="100%">
            <tr >
                <td width="45%" class=" header-section-right company-details company-address">
                    @if($logo)
                        <img class="header-logo" src="{{ $logo }}" alt="Company Logo">
                    @endif
                    @if($company_adresss)
                        <br><span style="font-size:14px;"><strong>{{$company_adresss['{COMPANY_NAME}'] ?? ''}}</strong></span><br>
                        <span>{{$company_adresss['{COMPANY_ADDRESS_STREET_1}'] ?? ''}}</span><br>
                        <span>{{$company_adresss['{COMPANY_ADDRESS_STREET_2}'] ?? ''}}</span><br>
                        <span>{{$company_adresss['{COMPANY_CITY}'] ?? ''}} {{$company_adresss['{COMPANY_STATE}'] ?? ''}}</span><br>
                        <span>{{$company_adresss['{COMPANY_COUNTRY}'] ?? ''}} {{$company_adresss['{COMPANY_ZIP_CODE}'] ?? ''}}</span><br>
                        <span>{{$company_adresss['{COMPANY_PHONE}'] ?? ''}}</span><br>
                    @endif
                </td>
                <td width="35%" class="header-section-left">
                     <table width="100%" style="text-align: right;">
                    <tr>
                        <td class="attribute-label">@lang('pdf_payment_date')</td>
                        <td class="attribute-value"> &nbsp;{{$payment->formattedPaymentDate}}</td>
                    </tr>
                    <tr>
                        <td class="attribute-label">@lang('pdf_payment_number')</td>
                        <td class="attribute-value"> &nbsp;{{$payment->payment_number}}</td>
                    </tr>
                    <tr>
                        <td class="attribute-label">@lang('pdf_payment_mode')</td>
                        <td class="attribute-value"> &nbsp;{{$payment->paymentMethod ? $payment->paymentMethod->name : '-'}}</td>
                    </tr>
                    @if ($payment->invoice && $payment->invoice->invoice_number)
                    <tr>
                        <td class="attribute-label">@lang('pdf_invoice_label')</td>
                        <td class="attribute-value"> &nbsp;{{$payment->invoice->invoice_number}}</td>
                    </tr>
                    @endif
                </table>
                </td>
                
            </tr>
        </table>
    </div>

    <hr style="border: 0.620315px solid black;">

    <p class="content-heading">
        <span>@lang('pdf_payment_receipt_label')</span>
    </p>

    <hr style="border: 0.620315px solid black;">

    <div class="content-wrapper">
        <div class="main-content">
            <div class="customer-address-container">
                <div class="billing-address-container billing-address">
                    @if($billing_addresss)
                    {{\Lang::get('to',[],'en')}}<br>
                    <strong>{{ $billing_addresss['{BILLING_ADDRESS_NAME}'] ?? ''}}</strong>
                    @endif
                </div>
                <div class="billing-address-container--right">
                </div>
            </div>
        </div>
    </div>
    <div class="total-display-box">
        <table width="100%" style="text-align: left;">
            <tr>
                <th class="attribute-label" width="20%">Invoice No</th>
                <th class="attribute-label">Invoice Date</th>
                <th class="attribute-label">Invoice Amount</th>
                <th class="attribute-label">Discount/Deduction</th>
                <th class="attribute-label">Recieved Amount</th>
            </tr>
            <tr >
                <td class="attribute-value" style="padding-bottom:5px;border-top:1px solid black;border-bottom:1px solid black;">{{$payment->invoice->invoice_number}}</td>
                <td class="attribute-value" style="padding-bottom:5px;padding-bottom:5px;border-top:1px solid black;border-bottom:1px solid black;">{{$payment->invoice->formattedInvoiceDate}}</td>
                <td class="attribute-value" style="padding-bottom:5px;border-top:1px solid black;border-bottom:1px solid black;">{!! format_money_pdf($payment->invoice->total, $payment->customer->currency) !!}</td>
                <td class="attribute-value" style="padding-bottom:5px;border-top:1px solid black;border-bottom:1px solid black;">{!! format_money_pdf($payment->discount, $payment->customer->currency) !!}</td>
                <td class="attribute-value" style="padding-bottom:5px;border-top:1px solid black;border-bottom:1px solid black;">{!! format_money_pdf($payment->amount, $payment->customer->currency) !!}</td>
                
                
            </tr>
            <tr>
                <td class="attribute-label" colspan="3">
                    @if($notes)
                        <div class="notes-label">
                            <div class="notes-label">
                                {!! $note_heading !!}
                            </div>
                            {!! $notes !!}
                        </div>
                    @endif
                </td>
                <td class="" colspan="2">
                    <table clas="" style="">
                        <tr>
                            <th class="attribute-label">Total Payable Amount</th>
                            <td class="attribute-value total_payment_value">{!! format_money_pdf($payment->invoice->total - $payment->discount, $payment->customer->currency) !!}</td>
                        </tr>
                        <tr>
                            <th class="attribute-label">Recieved Amount</th>
                            <td class="total_payment_value">{!! format_money_pdf($payment->amount, $payment->customer->currency) !!}</td>
                        </tr>
                        <tr>
                            <th class="attribute-label">Balance Due Amount(SAR)</th>
                            <td class="total_payment_value">{!! format_money_pdf($payment->invoice->total - $payment->discount - $payment->amount, $payment->customer->currency) !!}</td>
                        </tr>
                    </table>

                </td>
            </tr>
        </table>
        <div>
            <div class="prepared_by">
                {{\Lang::get('Prepared By',[],'en')}}
            </div>
        </div>
    </div>
</body>

</html>
