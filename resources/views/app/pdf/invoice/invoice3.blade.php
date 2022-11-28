<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>@lang('pdf_invoice_label') - {{$invoice->invoice_number}}</title>
    <style type="text/css">
        @page{
            margin-top: 140px;
        } 
        @page {
            margin-top:<?php echo $invoice->upper_margin?>cm;
            margin-bottom:<?php echo $invoice->lower_margin?>cm;
        }
        /* -- Base -- */

        body {
            font-family: "DejaVu Sans";
        }
        .arabic {
            font-family: "Lateef";
            font-size:13px;
            /*letter-spacing:1px;*/
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

        .company-address-container {
            margin-bottom: 2px;
        }

        .company-address {
            margin-top: 12px;
            font-size: 12px;
            line-height: 15px;
            color: #595959;
            word-wrap: break-word;
        }

        /* -- Content Wrapper  */

        .content-wrapper {
            display: block;
            padding-top: 0px;
            padding-bottom: 20px;
        }

        .customer-address-container {
            display: block;
            float: left;
            width: 45%;
            padding: 10px 0 0 0px;
        }

        /* -- Billing -- */

        .billing-address-container {
            width:100%;
            display: block;
            float: right;
        }

        .billing-address-container-right {
            width:100%;
            text-align: right;
            font-size:11px;
            float: right;
        }

        .billing-address {
            font-size: 10px;
            line-height: 15px;
            color: #595959;
            margin-top: 5px;
            width: 160px;
            word-wrap: break-word;
        }

        /*  -- Estimate Details -- */

        .invoice-details-container {
            margin-right:auto;
            padding: 10px 0px 0 0;
            display: block;
            float: right;
        }

        .attribute-label {
            font-size: 10px;
            line-height: 14px;
            text-align: left;
            color: #55547A
        }

        .attribute-value {
            font-size: 9px;
            line-height: 15px;
            text-align: right;
        }

        /* -- Items Table -- */

        .items-table {
            padding: 0px 5px 10px 20px;
            page-break-before: avoid;
            word-break: break-word
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
            border-top: none;
            page-break-inside: avoid;
            page-break-before: auto;
            /*page-break-after: auto;*/
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
            padding-right: 10px;
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
            font-size: 9px;
            color: #595959;
            padding:5px 10px;
            width: 100%;
            text-align: left;
            page-break-inside: avoid;
            border:1px solid #E8E8E8;
        }

        .notes-label {
            font-size: 10px;
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
        .background_client, .custom_table .tr_blue, .bank_table th, .item-table-heading{
            background-color:rgba(241,245,249,1);
        }
        .price_table .border, .client_table .border, .custom_table .border, .bank_table .border, .items-table th, .items-table td{
            border:1px solid #a9a8b1;
            border-collapse:collapse;
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
    </style>
</head>

<body>
    @if($invoice->is_edit == '1') 
        <watermarktext content="DRAFT INVOICE" />
    @endif
    <div class="header-container">
        <table width="100%">
            <tr  >
                <td  class="text-left company-address-container company-address"  width="40%">
                    <strong>{{\Lang::get('Name',[],'en')}} : </strong>{{$company->name ?? 'NA'}}<br>
                    <strong>{{\Lang::get('Street Name',[],'en')}} : </strong>{{$company_address['{COMPANY_ADDRESS_STREET_1}'] ?? 'NA'}}<br>
                    <strong>{{\Lang::get('Building No',[],'en')}} : </strong>{{$company_address['{COMPANY_ADDRESS_STREET_2}'] ?? 'NA'}}<br>
                    <strong>{{\Lang::get('State/Province',[],'en')}} : </strong>{{$company_address['{COMPANY_STATE}'] ?? 'NA'}}<br>
                    <strong>{{\Lang::get('Country',[],'en')}} : </strong>{{$company_address['{COMPANY_COUNTRY}'] ?? 'NA'}}<br>
                    <strong>{{\Lang::get('District',[],'en')}} : </strong>{{$company_address['{COMPANY_CITY}'] ?? 'NA'}}<br>
                    <strong>{{\Lang::get('CR No',[],'en')}} : </strong>{{$invoice->comp_cr ?? 'NA'}}<br>
                    <strong>{{\Lang::get('VAT Number',[],'en')}} : </strong>{{$invoice->comp_vat ?? 'NA'}}<br>

                </td>
                <td  class="header-section-left"  width="15%"  style="text-align:center;">
                    @if($logo)
                    <img class="header-logo" style="max-width: 140px" src="{{ $logo }}" alt="Company Logo">
                    @else
                    <h1 class="header-logo"> {{$invoice->comp_name}} </h1>
                    @endif
                </td>
                <td  class="text-right company-address-container company-address" dir="rtl" width="40%"  >
<!--                     {!! $company_addresss !!}
                    <p style="word-break:keep-all;"><strong>@lang('Company CR Number')</strong> - {{$company->cr}}</p>
                    <p><strong>@lang('Company VAT Number')</strong> - {{$company->vat}}</p> -->
                    <strong>{{\Lang::get('Name',[],'ar')}} : </strong><span class="arabic" >{{$invoice->comp_name_ar ?? 'NA'}}</span><br>
                    <strong>{{\Lang::get('Street Name',[],'ar')}} : </strong><span class="arabic" >{{$invoice->comp_address_street_1_ar ?? 'NA'}}</span><br>
                    <strong><span class="arabic" >{{\Lang::get('Building No',[],'ar')}}</span> : </strong><span class="arabic" >{{$invoice->comp_address_street_2_ar ?? 'NA'}}</span><br>
                    <strong><span class="arabic" >{{\Lang::get('State/Province',[],'ar')}}</span> : </strong><span class="arabic" >{{$invoice->comp_state_ar ?? 'NA'}}</span><br>
                    <strong><span class="arabic" >{{\Lang::get('Country',[],'ar')}}</span> : </strong><span class="arabic" >{{\Lang::get($company_address['{COMPANY_COUNTRY}'],[],'ar') ?? 'NA'}}</span><br>
                    <strong><span class="arabic" >{{\Lang::get('District',[],'ar')}}</span> : </strong><span class="arabic" >{{$invoice->comp_city_ar ?? 'NA'}}</span><br>
                    <strong><span class="arabic" >{{\Lang::get('CR No',[],'ar')}}</span> : </strong><span class="arabic" >{{$invoice->comp_cr_ar ?? 'NA'}}</span><br>
                    <strong><span class="arabic" >{{\Lang::get('VAT Number',[],'ar')}}</span> : </strong><span class="arabic" >{{$invoice->comp_vat_ar ?? 'NA'}}</span><br>
                </td>
            </tr>
        </table>
        <hr class="header-bottom-divider">
    </div>
    
    
<p class="invoice_static_top"><strong>{{\Lang::get('Tax Invoice',[],'en')}} / <span class="arabic" >{{\Lang::get('Tax Invoice',[],'ar')}}</span></strong></p>
    <div class="content-wrapper" style="margin-top: -10px;">
        <div class="main-content"  width="100%">
            <div class="customer-address-container"  width="50%">
                <div class="billing-address-container billing-address">
                    @if($billing_address)
                    <b>@lang('pdf_bill_to')</b> <br>
                    {!! $billing_address !!}
                    @endif
                    <p style="word-break:keep-all;"><strong>@lang('Client CR Number')</strong> - {{$invoice->cus_prefix}}</p>
                    <p><strong>@lang('Client VAT Number')</strong> - {{$invoice->cus_website}}</p>
                </div>
                
                
            </div>
            
            <div class="invoice-details-container" width="50%">
                <div class="billing-address-container-right billing-address">
                    <img class="qr_code" src="{{ $qrcode }}" alt="Qr Code">
                    <p><strong><span class="arabic" >{{\Lang::get('invoice_number',[],'ar')}}</span> / {{\Lang::get('invoice_number',[],'en')}}</strong> &nbsp;{{$invoice->invoice_number}}</p>
                </div>
            </div>
        </div>
        <div width="100%" style="margin-top:15px;margin-bottom:10px;">
            <table class="custom_table" width="100%">
                    <tr class="tr_blue">
                        <th class="border"><span class="arabic" >{{\Lang::get('Invoice Date',[],'ar')}}</span><br>{{\Lang::get('Invoice Date',[],'en')}}</th>
                        <th class="border"><span class="arabic" >{{\Lang::get('Due Date',[],'ar')}}</span><br>{{\Lang::get('Due Date',[],'en')}}</th>
                        @foreach($customFields as $key => $customField)
                            @if($key < 3)
                                <th class="border"><span class="arabic">{{$customField->label_ar != null ? $customField->label_ar : 'NA'}}</span><br>{{$customField->label != null ? $customField->label : ''}}</th>
                            @endif
                        @endforeach
                       
                    </tr>
                <tr>
                    <td class="border">{{$invoice->formattedInvoiceDate}}</td>
                    <td class="border">{{$invoice->formattedDueDate}}</td>
                    @foreach($customFields as $key => $customField)
                    @if($key < 3)
                        <td class="border">{{$customField->value}}</td>
                    @endif
                    @endforeach
                    
                </tr>
            </table>
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
