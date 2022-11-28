<!DOCTYPE html>
<html>

<head>
    <title>@lang('pdf_invoice_label') - {{$invoice->invoice_number}}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
         
        @page {
            margin-top:<?php echo $invoice->upper_margin?>cm;
            margin-bottom:<?php echo $invoice->lower_margin?>cm;
        }
        /* -- Base -- */
        body {
            font-family: "DejaVu Sans";
        }

        html {
            margin: 0px;
            padding: 0px;
            margin-top: 50px;
        }
        .arabic {
            font-family: "Lateef";
            font-size:13px;
            /*letter-spacing:1px;*/
        }
        .price_table .border, 
        table {
            border-collapse: collapse;
        }

        hr {
            margin: 0 30px 0 30px;
            color: rgba(0, 0, 0, 0.2);
            border: 0.5px solid #EAF1FB;
        }

        /* -- Header -- */

        .header-container {
            background:#2470b7;
            position: absolute;
            width: 100%;
            height: 150px;
            left: 0px;
            top: 0px;
        }

        .header-section-left {
            display: inline-block;
            padding-top: 10px;
            padding-left: 30px;
            width: 30%;
        }

        .header-logo {
            position: absolute;
            text-transform: capitalize;
            color: #fff;
        }

        .header-company-name{
            color:white;
            background:linear-gradient(90deg,#817AE3,white);
            width:220px;
            padding:4px;
        }

        .header-section-right {
            display: inline-block;
            /*width: 100%;*/
            float: right;
            padding: 40px 30px 20px 0px;
            text-align: right;
            color: white;
        }

        .header {
            font-size: 20px;
            color: rgba(0, 0, 0, 0.7);
        }

        /*  -- Estimate Details -- */

        .invoice-details-container {
            text-align: right;
            /*width: 40%;*/
        }

        .invoice-details-container h1 {
            margin: 0;
            font-size: 24px;
            line-height: 15px;
            text-align: right;
        }

        .invoice-details-container h4 {
            margin: 0;
            font-size: 10px;
            line-height: 15px;
            text-align: right;
        }

        .invoice-details-container h3 {
            margin-bottom: 1px;
            margin-top: 0;
        }

        /* -- Content Wrapper -- */

        .content-wrapper {
            display: block;
            /*margin-top: 60px;*/
            padding-bottom: 20px;
        }

        .company-details-container {
            padding-top: 30px;
        }


        .company-address-container {
            float: left;
            width: 50%;
            text-transform: capitalize;
            margin-bottom: 2px;
        }

        .company-address-container h1 {
            font-size: 15px;
            line-height: 22px;
            letter-spacing: 0.05em;
            margin-bottom: 0px;
        }

        .company-address {
            margin-top: 2px;
            text-align: left;
            font-size: 12px;
            line-height: 15px;
            /*color: #595959;*/
            /*width: 280px;*/
            word-wrap: break-word;
        }

        .estimate-details-container {
            float: right;
            /*padding: 10px 30px 0 0;*/
        }
        .address-container {
            display: block;
            padding-top: 20px;
            margin-top: 18px;
        }
        .attribute-label {
            font-size: 12px;
            line-height: 18px;
            padding-right: 40px;
            text-align: left;
            /*color: #55547A*/
        }

        .attribute-value {
            font-size: 12px;
            line-height: 18px;
            text-align: right;
        }

        /* -- Company -- */

        .company-address-container {
            /*padding: 0 0 0 30px;*/
            display: inline;
            float: left;
            width: 30%;
        }

        .company-address-container h1 {
            font-weight: bold;
            font-size: 15px;
            letter-spacing: 0.05em;
            margin-bottom: 0;
            /* margin-top: 18px; */
        }

        .company-address {
            font-size: 10px;
            line-height: 15px;
            color: #595959;
            margin-top: 0px;
            word-wrap: break-word;
        }

        /* -- Billing -- */

        .billing-address-container {
             float: left;
            width: 50%;
            text-transform: capitalize;
            margin-bottom: 2px;
        }

        .billing-address-label {
            font-size: 12px;
            line-height: 18px;
            padding: 0px;
            margin-bottom: 0px;
        }

        .billing-address-name {
            /*max-width: 160px;*/
            font-size: 15px;
            line-height: 22px;
            padding: 0px;
            margin: 0px;
        }

        .billing-address {
            margin-top: 2px;
            text-align: left;
            font-size: 12px;
            line-height: 15px;
            /*color: #595959;*/
            /*width: 280px;*/
            word-wrap: break-word;
        }

        /* -- Items Table -- */

        .items-table {
            padding: 0px 5px 10px 10px;
            page-break-before: avoid;
            word-break: break-word;
             border-top:2px solid black;
            border-bottom:2px solid black;
            /*page-break-after: auto;*/
        }

        .items-table hr {
            height: 0.1px;
        }

        .item-table-heading {
            font-size: 9px;
            text-align: center;
            padding: 1px 5px 1px 5px;
        }

        tr.item-table-heading-row th {
            border-bottom: 0.620315px solid #E8E8E8;
            font-size: 9px;
            line-height: 14px;
        }

        tr.item-row td {
            font-size: 9px;
            /*line-height: 18px;*/
        }

        .item-cell {
            font-size: 9px;
            text-align: center;
            padding: 1px;
            color: #040405;
        }

        .item-description {
            color: #595959;
            font-size: 8px;
            line-height: 12px;
        }

        /* -- Total Display Table -- */

        .total-display-container {
            /*padding: 0 25px;*/
        }

        .total-display-table {
             border-top:2px solid black;
            border-bottom:2px solid black;
        }

        .total-table-attribute-label {
            font-size: 9px;
            color: #000;
            text-align: left;
            padding-left: 10px;
        }

        .total-table-attribute-value {
            font-weight: bold;
            text-align: right;
            font-size: 9px;
            color: #040405;
            padding-right: 9px;
            padding-top: 1px;
            padding-bottom: 1px;
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
            font-size: 13px;
            color: #595959;
            margin-top: 15px;
            /*margin-left: 30px;*/
            /*width: 442px;*/
            text-align: left;
            page-break-inside: avoid;
        }

        .notes-label {
            font-size: 13px;
            line-height: 15px;
            letter-spacing: 0.05em;
            color: #040405;
            /*width: 108px;*/
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
        .qr_code{
            height:100px;
        }
        .bottom_static{
            margin-top:-10px;
            text-align:center;
            /*position: fixed;*/
             font-size:11px;
    /*bottom: 0;*/
            width:100%;
        }
        .custom_table{
            text-align:center;
        }
        .bank_table{
             border-top:2px solid black;
            border-bottom:2px solid black;
        }
        .background_client, .custom_table .tr_blue, .bank_table th, .item-table-heading{
            background-color:#1f4e7a;
            color:white;
        }
        .price_table .border, .client_table .border, .custom_table .border, .bank_table .border, .items-table th, .items-table td{
            border:1px solid #a9a8b1;
            font-size:10px;
            border-collapse:collapse;
             text-align:center;
        }
        .invoice_static_top{
            text-transform:uppercase;
            width:100%;
            text-align:center;
            font-size:15px;
            color:#00008B;
            margin-top:1px;
            margin-bottom:0px;
        }

        .company-address-heading,.client-address-heading{
            width:220px;
            margin-bottom:2px;
        }
    </style>
</head>

<body>
     @if($invoice->is_edit == '1') 
        <watermarktext content="DRAFT INVOICE" />
    @endif
    <div class="header-container">
        <table width="100%">
            <tr>
                <td width="60%" class="header-section-left">
                    @if($logo)
                        <img class="header-logo" style="max-width: 140px" src="{{ $logo }}" alt="Company Logo">
                    @else
                        <h1 class="header-logo"> {{$invoice->comp_name}} </h1>
                    @endif
                    @if($invoice->comp_name)
                        <h2 class="header-company-name"> {{$invoice->comp_name}} </h2>
                    @endif
                </td>

                <td width="5%" class="header-section-right invoice-details-container">
                    <h1>@lang('pdf_invoice_label')</h1>
                </td>
            </tr>
        </table>
    </div>
    <div class="content-wrapper" style="margin-top: 110px;">
        <div class="company-details-container">
            <h5 class="company-address-heading">Company Address</h5>
            <div class="company-address-container company-address">
                {{$invoice->comp_address_street_1.', '.$invoice->comp_city.', '.$invoice->comp_state.', '.$invoice->comp_zip}}
                <br>
                {{$invoice->comp_phone}}
            </div>

            <div class="estimate-details-container">
               <table>
                    <tr>
                        <td class="attribute-label">@lang('pdf_invoice_number')</td>
                        <td class="attribute-value"> &nbsp;{{$invoice->invoice_number}}</td>
                    </tr>
                    <tr>
                        <td class="attribute-label">@lang('pdf_invoice_date') </td>
                        <td class="attribute-value"> &nbsp;{{$invoice->formattedInvoiceDate}}</td>
                    </tr>
                    <tr>
                        <td class="attribute-label">@lang('pdf_invoice_due_date')</td>
                        <td class="attribute-value"> &nbsp;{{$invoice->FormattedDueDate}}</td>
                    </tr>
                </table>
            </div>
            <div style="clear: both;"></div>
        </div>
        <div class="customer-address-container">
            <h5 class="client-address-heading">Invoice for :</h5>
            <div class="billing-address-container billing-address">
                <p>To</p>
                {{$invoice->cus_name}}
                <br>
                @if($invoice->customer->company)
                    {{$invoice->customer->company->name}}
                @endif
                <br>
                {{$invoice->cus_address_street_1.', '.$invoice->cus_city.', '.$invoice->cus_state.', '.$invoice->cus_zip}}
                <br>
                {{$invoice->cus_phone}}
            </div>
            <div class="estimate-details-container">
               <table>
                    @foreach($customFields as $customField)
                        <tr>
                            <td class="attribute-label">{{$customField->label != null ? $customField->label : ''}}</td><td class="attribute-value">{{$customField->value}}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div style="clear: both;"></div>

        </div>
        
        @include('app.pdf.invoice.partials.table')

        <div class="notes">
            @if($notes)
                <div class="notes-label">
                    {!! $note_heading !!}
                </div>

                {!! $notes !!}
            @endif
        </div>
        @if($invoice->bank_detail_id)
            <div width="100%">
                <table class="bank_table" width="100%">
                    <tr class="tr_blue">
                        <th class="border th_left" colspan="2"><span class="arabic" >{{\Lang::get('Bank Details',[],'ar')}} / </span>{{\Lang::get('Bank Details',[],'en')}}</th>
                    </tr>
                    <tr>
                        <th class="border th_left" width=50%><span class="arabic" >{{\Lang::get('ACCOUNT NAME',[],'ar')}}</span> / {{\Lang::get('Account Name',[],'en')}}</th>
                        <td class="border">
                            <span class="arabic" >{{$invoice->comp_account_name_ar != null ? $invoice->comp_account_name_ar.' / ' : ''}}</span>
                             {{$invoice->comp_account_name != null ? $invoice->comp_account_name : ''}}</td>
                    </tr>
                    <tr>
                        <th class="border th_left"><span class="arabic" >{{\Lang::get('BANK NAME',[],'ar')}}</span> / {{\Lang::get('Bank Name',[],'en')}}</th>
                        <td class="border">
                            <span class="arabic" >{{$invoice->comp_bank_name_ar != null ? $invoice->comp_bank_name_ar.' / ' : ''}}</span>{{$invoice->comp_bank_name != null ? $invoice->comp_bank_name :''}}</td>
                    </tr>
                    <tr>
                        <th class="border th_left"><span class="arabic" >{{\Lang::get('ACCOUNT No',[],'ar')}}</span> / {{\Lang::get('Account No',[],'en')}}</th>
                        <td class="border"><span class="arabic" >{{$invoice->comp_account_no_ar != null ? $invoice->comp_account_no_ar.' / ' : ''}}</span>{{$invoice->comp_account_no != null ? $invoice->comp_account_no : ''}}</td>
                    </tr>
                    <tr>
                        <th class="border th_left"><span class="arabic" >{{\Lang::get('IBAN',[],'ar')}}</span> / {{\Lang::get('IBAN',[],'en')}}</th>
                        <td class="border"><span class="arabic" >{{$invoice->comp_iban_ar != null ? $invoice->comp_iban_ar.' / ' : ''}}</span>{{$invoice->comp_iban != null ? $invoice->comp_iban : ''}}</td>
                    </tr>
                    <tr>
                        <th class="border th_left"><span class="arabic" >{{\Lang::get('SWIFT CODE',[],'ar')}}</span> / {{\Lang::get('Swift Code',[],'en')}}</th>
                        <td class="border"><span class="arabic" >{{$invoice->comp_swift_code_ar != null ? $invoice->comp_swift_code_ar.' / ' : ''}}</span>{{$invoice->comp_swift_code != null ? $invoice->comp_swift_code : ''}}</td>
                    </tr>
                </table>

            </div>
        @endif
    </div>
    <div class="bottom_static">
        This is an e-invoice does not require signature<br/>
        {{$invoice->formattedInvoiceDate}}
    </div>
</body>

</html>
