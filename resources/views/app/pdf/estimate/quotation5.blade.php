<!DOCTYPE html>
<html>

<head>
    <title>@lang('pdf_estimate_label') - {{$estimate->estimate_number}}</title>
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

        /* -- Header -- */

        .header-container {
            margin-top: -30px;
            width: 100%;
            padding: 0px 30px;
        }

        .header-logo {
            text-transform: capitalize;
            color: #817AE3;
            padding-top: 0px;
        }

        /* -- Company Address -- */

        .company-address-container {
            width: 50%;
            text-transform: capitalize;
            padding-left: 80px;
            margin-bottom: 2px;
        }

        .company-address {
            margin-top: 12px;
            font-size: 12px;
            line-height: 15px;
            color: #595959;
            word-wrap: break-word;
        }

        /* -- Content Wrapper -- */

        .wrapper {
            display: block;
            padding-top: 0px;
            padding-bottom: 20px;
        }

        .customer-address-container {
            display: block;
            float: left;
            width: 58%;
            padding: 10px 0 10 20px;
            border:1px solid black;
            border-radius:5px;
            height:135px;
        }

        /* -- Shipping -- */

        .shipping-address-container {
            float: right;
            display: block;
        }

        .shipping-address-container--left {
            float:left;
            display: block;
            padding-left: 0;
        }

        .shipping-address-label {
            padding-top: 5px;
            font-size: 12px;
            line-height: 18px;
            margin-bottom: 0px;
        }

        .shipping-address-name {
            padding: 0px;
            font-size: 15px;
            line-height: 22px;
            margin: 0px;
            max-width: 160px;
        }

        .shipping-address {
            font-size: 10px;
            line-height: 15px;
            color: #595959;
            margin-top: 5px;
            width: 160px;
            word-wrap: break-word;
        }

        /* -- Billing -- */

        .billing-address-container {
            display: block;
            float: left;
        }

        .billing-address-label {
            padding-top: 5px;
            font-size: 10px;
            line-height: 18px;
            margin-bottom: 0px;
        }

        .billing-address-name {
            padding: 0px;
            font-size:9px;
            line-height: 22px;
            margin: 0px;
        }

        .billing-address {
            font-size: 10px;
            line-height: 15px;
            color: #595959;
            margin-top: 5px;
            word-wrap: break-word;
        }

        /* -- Estimate Details -- */

        .estimate-details-container {
            display: block;
            float: right;
            width:35%;
            padding: 10px 0px 0 10px;
            border:1px solid black;
            border-radius:5px;
            height:145px;
        }

        .attribute-label {
            font-size: 10px;
            line-height: 18px;
            text-align: left;
            color: #55547A
        }

        .attribute-value {
            font-size: 9px;
            line-height: 18px;
            text-align: right;
        }

        .estimate-details-container .attribute-label {
            font-size: 10px;
            width:50%;
            line-height: 18px;
            text-align: left;
            color: #55547A
        }

        .estimate-details-container .attribute-value {
            font-size: 10px;
            width:50%;
            line-height: 18px;
            text-align: left;
            color: #55547A
        }
        .attribute-value-left{
             font-size: 10px;
            line-height: 18px;
            text-align: left;
            padding-left:30px;
        }

        /* -- Items Table -- */

        .items-table {
            padding: 0px 30px 10px 30px;
            }

        .items-table hr {
            height: 0.1px;
            margin: 0 30px;
        }

        .item-table-heading {
            font-size: 10;
            text-align: center;
            color: rgba(0, 0, 0, 0.85);
            padding: 1px;
        }

        tr.item-table-heading-row th {
            border-bottom: 0.620315px solid #E8E8E8;
            font-size: 10px;
            line-height: 15px;
            word-break: break-word;
        }

        .item-table-heading-row {
        }

        tr.item-row td {
            font-size: 9px;
            line-height: 15px;
        }

        .item-cell {
            font-size: 9px;
            color: #040405;
            text-align: center;
            padding: 1px;
            border-color: #d9d9d9;
        }

        .item-description {
            color: #595959;
            font-size: 9px;
            line-height: 12px;
        }

        .item-cell-table-hr {
            margin: 0 30px 0 30px;
        }

        /* -- Total Display Table -- */

        .total-display-container {
            padding: 0 25px;
        }

        .total-display-table {
            box-sizing: border-box;
            page-break-inside: avoid;
            page-break-before: auto;
            /*margin-left: 500px;*/
            margin-top: 10px;
        }

        .total-table-attribute-label {
            font-size: 10px;
            color: #000;
            text-align: left;
            padding-left: 10px;
        }

        .total-table-attribute-value {
            font-weight: bold;
            text-align: right;
            font-size: 9px;
            color: #040405;
            padding-right: 10px;
            padding-top: 2px;
            padding-bottom: 2px;
        }

        .total-border-left {
            border: 1px solid #E8E8E8 !important;
            border-right: 0px !important;
            padding-top: 0px;
            padding: 8px !important;
        }

        .total-border-right {
            border: 1px solid #E8E8E8 !important;
            border-left: 0px !important;
            padding-top: 0px;
            padding: 8px !important;
        }

        /* -- Notes -- */

        .notes {
            font-size: 10px;
            color: #595959;
            margin-top: 15px;
            padding:5px 10px;
            width: 100%;
            text-align: left;
            page-break-inside: avoid;
            border:1px solid #E8E8E8;
        }

        .notes-label {
            font-size: 9px;
            line-height: 15px;
            letter-spacing: 0.05em;
            color: #040405;
            white-space: nowrap;
            height: 19.87px;
            padding-bottom: 10px;
        }

        /* -- Helpers -- */

        .text-primary {
            color: #5851DB;
        }

        .text-center {
            text-align: center
        }

        table .text-left {
            text-align: left;
        }

        table .text-right {
            text-align: right;
        }

        .border-0 {
            border: none;
        }

        .py-2 {
            padding-top: 2px;
            padding-bottom: 2px;
        }

        .py-8 {
            padding-top: 8px;
            padding-bottom: 8px;
        }

        .py-3 {
            padding: 3px 0;
        }

        .pr-20 {
            padding-right: 20px;
        }

        .pr-10 {
            padding-right: 10px;
        }

        .pl-20 {
            padding-left: 20px;
        }

        .pl-10 {
            padding-left: 10px;
        }

        .pl-0 {
            padding-left: 0;
        }
        .invoice_static_top{
            text-transform:uppercase;
            width:100%;
            text-align:center;
            font-size:18px;
            font-weight: bold;
            color:#00008B;
        }
        .items-table{
            border:1px solid #a9a8b1;
            font-size:10px;
            border-collapse:collapse;
        }
        .item-table-heading{
            background-color:rgba(241,245,249,1);
        }
        .items-table th, .items-table td{
            border:1px solid #a9a8b1;
            border-collapse:collapse;
        }
        .total-display-table th, .total-display-table td{
            border:1px solid #a9a8b1;
            border-collapse:collapse;
        }
        
        .bank_table{
            border:1px solid #a9a8b1;
            font-size:9px;
            margin-top:10px;
            border-collapse:collapse;
            word-break: break-word
        }
        .bank_table th{
            background-color:rgba(241,245,249,1);
        }
        .bank_table td, .bank_table th{
            padding:1px 5px;
        }
        .bank_table .border{
            border:1px solid #a9a8b1;
            border-collapse:collapse;
        }
        .header-bottom-divider{
            line-height:1px;
        }
        @page {
            @if($letterhead)
                background: url({{$letterhead}}) no-repeat 0 0;
                background-image-resize: 6;
            @endif
            margin-top:<?php echo $estimate->upper_margin?>cm;
            margin-bottom:<?php echo $estimate->lower_margin?>cm;        
        }
    </style>
</head>

<body>
        @if($estimate->is_edit == '1') 
        <!-- <watermarktext content="DRAFT QUOTATION" /> -->
    @endif
    <hr class="header-bottom-divider">
    <div class="invoice_static_top">{{\Lang::get('Quotation',[],'en')}}</div>
    <hr class="header-bottom-divider">
    <div class="wrapper">
        <div class="main-content" width="100%">
            <div class="customer-address-container">
                <div class="billing-address-container billing-address">
                    @if($billing_address)
                        <b>@lang('pdf_bill_to')</b> <br>
                        {!! $billing_address !!}
                    @endif
                </div>
            </div>

            <div class="estimate-details-container">
                <table>
                    <tr>
                        <td class="attribute-label">@lang('pdf_estimate_number')</td>
                        <td class="attribute-value"> &nbsp;{{$estimate->estimate_number}}</td>
                    </tr>
                    <tr>
                        <td class="attribute-label">@lang('pdf_estimate_date') </td>
                        <td class="attribute-value"> &nbsp;{{$estimate->formattedEstimateDate}}</td>
                    </tr>
                    <tr>
                        <td class="attribute-label">@lang('pdf_estimate_expire_date')</td>
                        <td class="attribute-value"> &nbsp;{{$estimate->formattedExpiryDate}}</td>
                    </tr>
                    @foreach($customFields as $customField)
                        <tr>
                            <td class="attribute-label">{{$customField->label != null ? $customField->label : ''}}</td><td class="attribute-value">{{$customField->value}}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
        <div style="margin-top:2px;margin-bottom:2px;">
                <table>
                    <tr>
                        <td class="attribute-label">Attention:</td>
                        <td class="attribute-value-left">{{$estimate->customer->contact_name}}</td>
                    </tr>
                    <tr>
                        <td class="attribute-label">Subject:</td>
                        <td class="attribute-value-left" >{{$estimate->subject ?? 'Quotation for Materials'}}</td>
                    </tr>
                </table>
                <hr class="header-bottom-divider">
                <table style="">
                    <tr >
                        <td class="attribute-label">Dear Sir,</td>
                    </tr>
                    <tr>
                        <td class="attribute-value">With reference to your enquiry, please find our competetive Quotation as follows:</td>
                    </tr>
                </table>
            </div>
            

            <div width="100%" >
                <table width="100%" class="items-table" cellspacing="0" border="0">
                    <tr class="item-table-heading-row">
                        <th  class=" item-table-heading">#</th>
                        <th  class="item-table-heading text-left" width="30%">{{\Lang::get('pdf_items_label',[],'en')}}</th>
                        <th class="item-table-heading">{{\Lang::get('pdf_quantity_label',[],'en')}}</th>
                        <th class="item-table-heading" width="9%">{{\Lang::get('Unit Of Measurement',[],'en')}}</th>
                        <th class="item-table-heading">{{\Lang::get('pdf_unit_price_label',[],'en')}}({{$estimate->customer->currency->symbol}})</th>
                        <th class="item-table-heading">{{\Lang::get('pdf_discount_label',[],'en')}}({{$estimate->customer->currency->symbol}})</th>
                        @if($estimate->tax_per_item === 'YES')
                        <th class="item-table-heading">{{\Lang::get('pdf_tax_label',[],'en')}}({{$estimate->customer->currency->symbol}})</th>
                        @endif
                        <th class="item-table-heading" >{{\Lang::get('pdf_amount_label',[],'en')}}({{$estimate->customer->currency->symbol}})</th>
                    </tr>
                    @php
                        $index = 1
                    @endphp
                    @foreach ($estimate->items as $item)
                        <tr class="item-row">
                            <td
                                class="item-cell"
                                style="vertical-align: middle;"
                            >
                                {{$index}}
                            </td>
                            <td
                                class="item-cell text-left"
                                style="vertical-align: middle;"
                            >
                                <span>{{$item->name}}</span><br>
                                <span class="item-description">{!! nl2br(htmlspecialchars($item->description)) !!}</span>
                            </td>
                            <td
                                class="item-cell"
                                style="vertical-align: middle;"
                            >
                                {{$item->quantity}}
                            </td>
                            <td
                                class="item-cell"
                                style="vertical-align: middle;"
                            >
                                @if($item->unit_name) {{$item->unit_name}} @else - @endif
                            </td>
                            <td
                                class="item-cell"
                                style="vertical-align: middle;"
                            >
                                {!! format_money_pdf($item->price, $estimate->customer->currency,false) !!}
                            </td>

                            <td
                                class="item-cell"
                                style="vertical-align: middle;"
                            >
                                @if($item->discount_type === 'fixed')
                                        {!! format_money_pdf($item->discount_val, $estimate->customer->currency,false) !!}
                                    @endif
                                    @if($item->discount_type === 'percentage')
                                        {{$item->discount}}%
                                    @endif
                            </td>
                            
                            @if($estimate->tax_per_item === 'YES')
                                <td
                                    class="item-cell"
                                    style="vertical-align: middle;"
                                >
                                    {!! format_money_pdf($item->tax, $estimate->customer->currency,false) !!}
                                </td>
                            @endif

                            <td
                                class="item-cell"
                                style="vertical-align: middle;"
                            >
                                {!! format_money_pdf($item->total, $estimate->customer->currency,false) !!}
                            </td>
                        </tr>
                        @php
                            $index += 1
                        @endphp
                    @endforeach
                    <tr>
                        <td></td>
                        <td style="text-align:center;">**** Nothing Follows ****</td>
                    </tr>
                </table>
            </div>
            <div width="100%">
                <table width="100%" cellspacing="0px" border="0" class="total-display-table">
                    <tr>
                        <td rowspan="10" class="border" style="text-align:center;text-transform: capitalize;font-size:10px;"><strong>{{\Lang::get('Amount in words',[],'en')}}</strong>
                            <br>{{$total_amount_in_en}} only
                        <td width="20%" class="border total-table-attribute-label">{{\Lang::get('pdf_subtotal',[],'en')}}</td>
                        <td width="30%" class="py-2 border item-cell total-table-attribute-value">
                            {!! format_money_pdf($estimate->sub_total, $estimate->customer->currency) !!}
                        </td>
                    </tr>

                    @if($estimate->discount > 0)
                        @if ($estimate->discount_per_item === 'YES')
                            <tr>
                                <td class="border total-table-attribute-label">
                                    @if($estimate->discount_type === 'fixed')
                                        Total {{\Lang::get('pdf_discount_label',[],'en')}}
                                    @endif
                                    @if($estimate->discount_type === 'percentage')
                                        Total {{\Lang::get('pdf_discount_label',[],'en')}} ({{$estimate->discount}}%)
                                    @endif
                                </td>
                                <td class="py-2 border item-cell total-table-attribute-value" >
                                    @if($estimate->discount_type === 'fixed')
                                        {!! format_money_pdf($estimate->discount_val, $estimate->customer->currency) !!}
                                    @endif
                                    @if($estimate->discount_type === 'percentage')
                                        {!! format_money_pdf($estimate->discount_val, $estimate->customer->currency) !!}
                                    @endif
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="border total-table-attribute-label">
                                    @if($estimate->discount_type === 'fixed')
                                        {{\Lang::get('pdf_deduction_label',[],'en')}}
                                    @endif
                                    @if($estimate->discount_type === 'percentage')
                                        {{\Lang::get('pdf_deduction_label',[],'en')}} ({{$estimate->discount}}%)
                                    @endif
                                </td>
                                <td class="py-2 border item-cell total-table-attribute-value" >
                                    @if($estimate->discount_type === 'fixed')
                                        {!! format_money_pdf($estimate->discount_val, $estimate->customer->currency) !!}
                                    @endif
                                    @if($estimate->discount_type === 'percentage')
                                        {!! format_money_pdf($estimate->discount_val, $estimate->customer->currency) !!}
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endif

                    @if ($estimate->tax_per_item === 'YES')
                        @foreach ($taxes as $tax)
                            <tr>
                                <td class="border total-table-attribute-label">
                                    <!-- {{$tax->name.' ('.$tax->percent.'%)'}} -->VAT (15%)
                                </td>
                                <td class="py-2 border item-cell total-table-attribute-value">
                                    {!! format_money_pdf($tax->amount, $estimate->customer->currency) !!}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        @foreach ($estimate->taxes as $tax)
                            <tr>
                                <td class="border total-table-attribute-label">
                                    <!-- {{$tax->name.' ('.$tax->percent.'%)'}} -->VAT (15%)
                                </td>
                                <td class="py-2 border item-cell total-table-attribute-value">
                                    {!! format_money_pdf($tax->amount, $estimate->customer->currency) !!}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    <tr>
                        <td class="border total-border-left total-table-attribute-label">
                            {{\Lang::get('pdf_total',[],'en')}}
                        </td>
                        <td
                            class="py-8 border total-border-right item-cell total-table-attribute-value"
                            style="color: #00008B"
                        >
                            {!! format_money_pdf($estimate->total, $estimate->customer->currency)!!}
                        </td>
                    </tr>
                </table>
            </div>

            @if($notes)
                <div class="notes">
                    <div class="notes-label">
                        {!! $note_heading !!}
                    </div>
                    {!! $notes !!}
                </div>
            @endif
            <div width="100%">
            <table class="bank_table" width="100%">
                <tr class="tr_blue">
                    <th class="border th_left" colspan="2">{{\Lang::get('Bank Details',[],'en')}}</th>
                    <th class="border"  width="20%">{{\Lang::get('Prepared By',[],'en')}}</th>
                </tr>
                <tr>
                    <th class="border th_left">{{\Lang::get('Account Name',[],'en')}}</th>
                    <td class="border">{{$estimate->comp_account_name != null ? $estimate->comp_account_name : ''}}</td>
                    <td rowspan="5" class="border" style="text-align:center; padding: 10px 5px">
                        @if($signature)
                            @if($signature_image)
                                <img style="max-width: 125px" src="{{ $signature_image }}" alt="prepared_by signature"><br>
                            @endif

                            <strong>{{$signature->name}}</strong><br>
                            <small>
                                @if($signature->designation)
                                    Designation: {{ $signature->designation }}<br>
                                @endif
                                @if($signature->contact_number)
                                    Contact No.: {{ $signature->contact_number }}<br>
                                @endif
                                @if($signature->email)
                                    Email: {{ $signature->email }}<br>
                                @endif
                            </small>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="border th_left">{{\Lang::get('Bank Name',[],'en')}}</th>
                    <td class="border">{{$estimate->comp_bank_name != null ? $estimate->comp_bank_name :''}}</td>
                </tr>
                <tr>
                    <th class="border th_left">{{\Lang::get('Account No',[],'en')}}</th>
                    <td class="border">{{$estimate->comp_account_no != null ? $estimate->comp_account_no : ''}}</td>
                </tr>
                <tr>
                    <th class="border th_left">{{\Lang::get('IBAN',[],'en')}}</th>
                    <td class="border">{{$estimate->comp_iban != null ? $estimate->comp_iban : ''}}</td>
                </tr>
                <tr>
                    <th class="border th_left">{{\Lang::get('Swift Code',[],'en')}}</th>
                    <td class="border">{{$estimate->comp_swift_code != null ? $estimate->comp_swift_code : ''}}</td>
                </tr>
            </table>

        </div>
        </div>
    </div>
</body>

</html>
