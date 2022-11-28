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
            width: 100%;
        }

        .header-logo {
            text-transform: capitalize;
            color: #00008B;
            padding-top: 0px;
        }

        /* -- Company Address -- */

        .company-address-container {
            width: 50%;
            margin-top:20px;
            text-transform: capitalize;
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
        }

        
        /* -- Billing -- */

        

        .billing-address-label {
            padding-top: 5px;
            font-size: 11px;
            line-height: 15px;
            margin-bottom: 0px;
            color:#595959;
            text-align:left;
        }

        .billing-address-name {
            padding: 0px;
            font-size: 15px;
            line-height: 22px;
            margin: 0px;
            max-width: 160px;
        }

        .billing-address {
            font-size: 9px;
            line-height: 15px;
            color: #595959;
            padding-top: 5px;
            word-wrap: break-word;
            text-align:left;
        }

        

        /* -- Estimate Details -- */

        .estimate-details-container {
        }
        .estimate-details-container-right {
            padding: 10px 0px 0 0;
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
            text-align: right;
        }

        /* -- Items Table -- */

        .items-table {
            padding: 30px 30px 10px 30px;
            page-break-before: avoid;
        }

        .items-table hr {
            height: 0.1px;
            margin: 0 30px;
        }

        .item-table-heading {
            font-size: 13.5;
            text-align: center;
            color: rgba(0, 0, 0, 0.85);
            padding: 5px;
            padding-bottom: 10px;
        }

        tr.item-table-heading-row th {
            border-bottom: 0.620315px solid #E8E8E8;
            font-size: 12px;
            line-height: 18px;
        }

        .item-table-heading-row {
            margin-bottom: 10px;
        }

        tr.item-row td {
            font-size: 12px;
            line-height: 18px;
        }

        .item-cell {
            font-size: 13;
            color: #040405;
            text-align: center;
            padding: 5px;
            padding-top: 10px;
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
            margin-top: 20px;
        }

        .total-table-attribute-label {
            font-size: 12px;
            color: #000;
            text-align: left;
            padding-left: 10px;
        }

        .total-table-attribute-value {
            font-weight: bold;
            text-align: right;
            font-size: 12px;
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
            font-size: 12px;
            color: #595959;
            margin-top: 15px;
            padding:5px 10px;
            width: 100%;
            text-align: left;
            page-break-inside: avoid;
            border:1px solid #E8E8E8;
        }

        .notes-label {
            font-size: 13px;
            line-height: 22px;
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
        .bank_table,.items-table{
            border:1px solid #a9a8b1;
            font-size:10px;
            border-collapse:collapse;
        }
        .background_client, .custom_table .tr_blue, .bank_table th, .item-table-heading{
            background-color:rgba(241,245,249,1);
        }
        .client_table td, .custom_table td, .bank_table td, .bank_table th{
            padding:2px 5px;
        }
        .client_table .border, .custom_table .border, .bank_table .border, .items-table th, .items-table td{
            border:1px solid #a9a8b1;
            border-collapse:collapse;
        }
        .invoice_static_top{
            text-transform:uppercase;
            width:100%;
            text-align:center;
            font-size:18px;
            font-weight: bold;
            font-weight: bold;
            color:#00008B;
        }
        .middle_static_line{
            width:100%;
            text-align:left;
            font-size:12px;
            margin-bottom:18px;
        }
        .client-address{
            margin-bottom:10px;
            width:100%;
        }
        @page {
            margin-top:<?php echo $estimate->upper_margin?>cm;
            margin-bottom:<?php echo $estimate->lower_margin?>cm;        
        }
    </style>
</head>

<body>
        @if($estimate->is_edit == '1') 
        <!-- <watermarktext content="DRAFT QUOTATION" /> -->
    @endif
    <div class="header-container">
        <table width="100%">
            <tr>
                <td  class="text-left company-address-container company-address">
                    <!-- {!! $company_addresss !!}
                    <p style="word-break:keep-all;"><strong>@lang('Company CR Number')</strong> - {{$company->cr}}</p>
                    <p><strong>@lang('Company VAT Number')</strong> - {{$company->vat}}</p> -->
                    <strong>{{\Lang::get('Name',[],'en')}} : </strong>{{$company->name ?? 'NA'}}<br>
                    <strong>{{\Lang::get('Street Name',[],'en')}} : </strong>{{$company_address['{COMPANY_ADDRESS_STREET_1}'] ?? 'NA'}}<br>
                    <strong>{{\Lang::get('Building No',[],'en')}} : </strong>{{$company_address['{COMPANY_ADDRESS_STREET_2}'] ?? 'NA'}}<br>
                    <strong>{{\Lang::get('State/Province',[],'en')}} : </strong>{{$company_address['{COMPANY_STATE}'] ?? 'NA'}}<br>
                    <strong>{{\Lang::get('Country',[],'en')}} : </strong>{{$company_address['{COMPANY_COUNTRY}'] ?? 'NA'}}<br>
                    <strong>{{\Lang::get('District',[],'en')}} : </strong>{{$company_address['{COMPANY_CITY}'] ?? 'NA'}}<br>
                    <strong>{{\Lang::get('CR No',[],'en')}} : </strong>{{$company->cr ?? 'NA'}}<br>
                    <strong>{{\Lang::get('VAT Number',[],'en')}} : </strong>{{$company->vat ?? 'NA'}}<br>

                </td>
                <td  class="header-section-left">
                    @if($logo)
                    <img class="header-logo" style="max-width: 140px" src="{{ $logo }}" alt="Company Logo">
                    @else
                    <h1 class="header-logo"> {{$estimate->company->name}} </h1>
                    @endif
                </td>
                <td  class="text-right company-address-container company-address" dir="rtl">
<!--                     {!! $company_addresss !!}
                    <p style="word-break:keep-all;"><strong>@lang('Company CR Number')</strong> - {{$company->cr}}</p>
                    <p><strong>@lang('Company VAT Number')</strong> - {{$company->vat}}</p> -->
                    <strong>{{\Lang::get('Name',[],'ar')}} : </strong>{{$company->name_ar ?? 'NA'}}<br>
                    <strong>{{\Lang::get('Street Name',[],'ar')}} : </strong>{{$company->address_street_1_ar ?? 'NA'}}<br>
                    <strong>{{\Lang::get('Building No',[],'ar')}} : </strong>{{$company->address_street_2_ar ?? 'NA'}}<br>
                    <strong>{{\Lang::get('State/Province',[],'ar')}} : </strong>{{$company->state_ar ?? 'NA'}}<br>
                    <strong>{{\Lang::get('Country',[],'ar')}} : </strong>{{\Lang::get($company_address['{COMPANY_COUNTRY}'],[],'ar') ?? 'NA'}}<br>
                    <strong>{{\Lang::get('District',[],'ar')}} : </strong>{{$company->city_ar ?? 'NA'}}<br>
                    <strong>{{\Lang::get('CR No',[],'ar')}} : </strong>{{$company->cr_ar ?? 'NA'}}<br>
                    <strong>{{\Lang::get('VAT Number',[],'ar')}} : </strong>{{$company->vat_ar ?? 'NA'}}<br>
                </td>
            </tr>
        </table>
    </div>

    <hr class="header-bottom-divider">
    <div class="invoice_static_top">
        {{\Lang::get('Quotation',[],'en')}}
    </div>
       <hr class="header-bottom-divider">
    <div class="wrapper">
        <div class="main-content">
            <div width="100%" class="client-address">
                <div class="customer-address-container" width="69%">
                    <div class="billing-address-container billing-address">
                        @if($billing_address)
                            <b>@lang('pdf_bill_to')</b> <br>
                            {!! $billing_address !!}
                        @endif
                    </div>
                    <div style="clear: both;"></div>
                </div>

                <div class="estimate-details-container" width="30%">
                    
                    <div class="billing-address-container-right billing-address" width="100%"style="text-align: right;">
                        <table style="text-align:right" width="100%">
                            <tr>
                                <th class="billing-address-label"  style="text-align:left">@lang('pdf_estimate_number')</th><td class="billing-address">{{$estimate->estimate_number}}</td>
                            </tr>
                            <tr>
                                <th class="billing-address-label" style="text-align:left">@lang('pdf_estimate_date')</th><td class="billing-address">{{$estimate->formattedEstimateDate}}</td>
                        </tr>
                        <tr>
                            <th class="billing-address-label" style="text-align:left">@lang('pdf_estimate_expire_date')</th><td class="billing-address">{{$estimate->formattedExpiryDate}}</td>
                        </tr>
                        @foreach($customFields as $customField)
                        <tr>
                            <th class="billing-address-label" style="text-align:left">{{$customField->label != null ? $customField->label : ''}}</th><td class="billing-address">{{$customField->value}}</td>
                        </tr>
                        @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <hr class="header-bottom-divider">
            <div class="middle_static_line">With reference to your enquiry, please find our competitive Quotation as follows:</div>
            <div width="100%" >
            <table width="100%" class="items-table" cellspacing="0" border="0">
                <tr class="item-table-heading-row">
                    <th  class=" item-table-heading">#</th>
                    <th  class="item-table-heading text-left" width="30%">{{\Lang::get('pdf_items_label',[],'en')}}</th>
                    <th class="item-table-heading">{{\Lang::get('pdf_quantity_label',[],'en')}}</th>
                    <th class="item-table-heading" >{{\Lang::get('Unit Of Measurement',[],'en')}}</th>
                    <th class="item-table-heading">{{\Lang::get('pdf_unit_price_label',[],'en')}}</th>
                    <th class="item-table-heading">{{\Lang::get('pdf_discount_label',[],'en')}}</th>
                    @if($estimate->tax_per_item === 'YES')
                    <th class="item-table-heading">{{\Lang::get('pdf_tax_label',[],'en')}}</th>
                    @endif
                    <th class="item-table-heading" >{{\Lang::get('pdf_amount_label',[],'en')}}</th>
                </tr>
                @php
                    $index = 1
                @endphp
                @foreach ($estimate->items as $item)
                    <tr class="item-row">
                        <td
                            class="item-cell"
                            style="vertical-align: top;"
                        >
                            {{$index}}
                        </td>
                        <td
                            class="item-cell text-left"
                            style="vertical-align: top;"
                        >
                            <span>{{$item->name}}</span><br>
                            <span class="item-description">{!! nl2br(htmlspecialchars($item->description)) !!}</span>
                        </td>
                        <td
                            class="item-cell"
                            style="vertical-align: top;"
                        >
                            {{$item->quantity}}
                        </td>
                        <td
                            class="item-cell"
                            style="vertical-align: top;"
                        >
                            @if($item->unit_name) {{$item->unit_name}} @else - @endif
                        </td>
                        <td
                            class="item-cell"
                            style="vertical-align: top;"
                        >
                            {!! format_money_pdf($item->price, $estimate->customer->currency) !!}
                        </td>

                        <td
                            class="item-cell"
                            style="vertical-align: top;"
                        >
                            @if($item->discount_type === 'fixed')
                                    {!! format_money_pdf($item->discount_val, $estimate->customer->currency) !!}
                                @endif
                                @if($item->discount_type === 'percentage')
                                    {{$item->discount}}%
                                @endif
                        </td>

                        @if($estimate->tax_per_item === 'YES')
                            <td
                                class="item-cell"
                                style="vertical-align: top;"
                            >
                                {!! format_money_pdf($item->tax, $estimate->customer->currency) !!}
                            </td>
                        @endif

                        <td
                            class="item-cell"
                            style="vertical-align: top;"
                        >
                            {!! format_money_pdf($item->total, $estimate->customer->currency) !!}
                        </td>
                    </tr>
                    @php
                        $index += 1
                    @endphp
                @endforeach
            </table>
        </div>

        <div width="100%">
            <table width="100%" cellspacing="0px" border="0" class="total-display-table @if(count($estimate->items) > 12) page-break @endif">
                <tr>
                    <td rowspan="10" style="text-align:center;text-transform: capitalize;font-size:11px;"><strong>{{\Lang::get('Amount in words',[],'en')}}</strong>
                        <br>{{$total_amount_in_en}} only
                    <td width="20%" class="border-0 total-table-attribute-label">{{\Lang::get('pdf_subtotal',[],'en')}}</td>
                    <td width="30%" class="py-2 border-0 item-cell total-table-attribute-value">
                        {!! format_money_pdf($estimate->sub_total, $estimate->customer->currency) !!}
                    </td>
                </tr>

                @if($estimate->discount > 0)
                    @if ($estimate->discount_per_item === 'YES')
                        <tr>
                            <td class="border-0 total-table-attribute-label">
                                @if($estimate->discount_type === 'fixed')
                                    Total {{\Lang::get('pdf_discount_label',[],'en')}}
                                @endif
                                @if($estimate->discount_type === 'percentage')
                                    Total {{\Lang::get('pdf_discount_label',[],'en')}} ({{$estimate->discount}}%)
                                @endif
                            </td>
                            <td class="py-2 border-0 item-cell total-table-attribute-value" >
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
                            <td class="border-0 total-table-attribute-label">
                                @if($estimate->discount_type === 'fixed')
                                    Total {{\Lang::get('pdf_deduction_label',[],'en')}}
                                @endif
                                @if($estimate->discount_type === 'percentage')
                                    Total {{\Lang::get('pdf_deduction_label',[],'en')}} ({{$estimate->discount}}%)
                                @endif
                            </td>
                            <td class="py-2 border-0 item-cell total-table-attribute-value" >
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
                            <td class="border-0 total-table-attribute-label">
                                <!-- {{$tax->name.' ('.$tax->percent.'%)'}} -->VAT (15%)
                            </td>
                            <td class="py-2 border-0 item-cell total-table-attribute-value">
                                {!! format_money_pdf($tax->amount, $estimate->customer->currency) !!}
                            </td>
                        </tr>
                    @endforeach
                @else
                    @foreach ($estimate->taxes as $tax)
                        <tr>
                            <td class="border-0 total-table-attribute-label">
                                <!-- {{$tax->name.' ('.$tax->percent.'%)'}} -->VAT (15%)
                            </td>
                            <td class="py-2 border-0 item-cell total-table-attribute-value">
                                {!! format_money_pdf($tax->amount, $estimate->customer->currency) !!}
                            </td>
                        </tr>
                    @endforeach
                @endif

                <tr>
                    <td class="py-3"></td>
                    <td class="py-3"></td>
                </tr>
                <tr>
                    <td class="border-0 total-border-left total-table-attribute-label">
                        {{\Lang::get('pdf_total',[],'en')}}
                    </td>
                    <td
                        class="py-8 border-0 total-border-right item-cell total-table-attribute-value"
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
        @if($estimate->bank_detail_id)
            <div width="100%" style="margin-top:5px;">
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
                                    @if($signature->designation != 'null')
                                        Designation: {{ $signature->designation }}<br>
                                    @endif
                                    @if($signature->contact_number != 'null')
                                        Contact No.: {{ $signature->contact_number }}<br>
                                    @endif
                                    @if($signature->email != 'null')
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
        @endif
        </div>
    </div>
</body>

</html>
