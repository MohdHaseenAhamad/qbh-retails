<!DOCTYPE html>
<html>

<head>
    <title>@lang('pdf_invoice_label') - {{$invoice->invoice_number}}</title>
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
            margin: 0 30px 0 30px;
            color: rgba(0, 0, 0, 0.2);
            border: 0.5px solid #EAF1FB;
        }

        /* -- Header -- */

        .header-container {
            /*background: #817AE3;*/
            background:linear-gradient(90deg,#9cf297,white);
            position: absolute;
            width: 100%;
            height: 150px;
            left: 0px;
            top: 0px;
        }

        .header-bottom-divider {
            color: rgba(0, 0, 0, 0.2);
            position: absolute;
            top: 90px;
            left: 0px;
            width: 100%;
        }

        .header-logo {
            margin-top: 20px;
            text-transform: capitalize;
            color: #817AE3;
            margin-bottom:10px;
        }
        .header-company-name{
            width:220px;
            padding:4px;
        }

        .header-section-left {
            display: inline-block;
            padding-top: 10px;
            padding-left: 30px;
            width: 30%;
        }

        .header-section-right {
            display: inline-block;
            /*width: 100%;*/
            float: right;
            padding: 40px 30px 20px 0px;
            text-align: right;
        }
        .company-address-heading,.client-address-heading{
            width:220px;
            background: #ffff66;
            padding:8px;
        }

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

        .header {
            font-size: 20px;
            color: rgba(0, 0, 0, 0.7);
        }

        .wrapper {
            display: block;
            /*padding-top: 16px;*/
            padding-bottom: 20px;
        }

        /* -- Company Details -- */

        .company-details-container {
            padding-top: 30px;
        }

        .company-address-container {
            float: left;
            width: 30%;
            text-transform: capitalize;
            margin-bottom: 2px;
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
            color: #595959;
            /*width: 280px;*/
            word-wrap: break-word;
        }

        .estimate-details-container {
            float: right;
            /*padding: 10px 30px 0 0;*/
        }

        .attribute-label {
            font-size: 12px;
            line-height: 18px;
            padding-right: 40px;
            text-align: left;
            color: #55547A
        }

        .attribute-value {
            font-size: 12px;
            line-height: 18px;
            text-align: right;
        }

        /* -- Customer Address -- */

        .customer-address-container {
            padding: 0px 0 0 0px;
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
            color: #595959;
            /*width: 280px;*/
            word-wrap: break-word;
        }
        .reference-line{
            margin-top:15px;
            border-top:2px solid black;
            border-bottom:2px solid black;
            background: rgba(86,96,219,0.3);
        }
        .reference-line p{
            padding:0px 10px;
            font-size:12px;
        }

        /* -- Items Table -- */

        .items-table {
            border-top:2px solid black;
            border-bottom:2px solid black;
            margin-top: 35px;
            padding: 0px 30px 10px 30px;
            page-break-before: avoid;
            
        }
        .items-table th, .items-table td,.total-display-table th,.total-display-table td{
            border:1px solid #a9a8b1;
            border-collapse:collapse;
        }

        .items-table  th{
            background:linear-gradient(90deg,#9cf297,white);
            
        }

        .items-table hr {
            height: 0.1px;
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

        /* -- Total Display Table -- */

      
        .total-display-table {
            box-sizing: border-box;
            page-break-inside: avoid;
            page-break-before: auto;
            
            /*margin-left: 500px;*/
            margin-top: 20px;
        }

        .total-table-attribute-label {
            font-size: 12px;
            color: #55547A;
            text-align: left;
            padding-left: 10px;
        }

        .total-display-table{
            border-top:2px solid black;
            border-bottom:2px solid black;
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

        .bank_table th{
            background-color:#817AE3;
        }

        .bank_table .border{
            border:1px solid #a9a8b1;
            border-collapse:collapse;
            border:1px solid #a9a8b1;
            font-size:10px;
            border-collapse:collapse;
             text-align:center;
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
                    <h1>TAX INVOICE</h1>
                </td>
            </tr>
        </table>
    </div>

    <div class="wrapper" style="padding-top: 130px;">
        <div class="company-details-container">
            <h5 class="company-address-heading">Company Address</h5>
            <div class="company-address-container company-address">
                {!! $company_addresss !!}
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
                        <td class="attribute-value"> &nbsp;{{$invoice->formattedDueDate}}</td>
                    </tr>
                </table>
            </div>
            <div style="clear: both;"></div>
        </div>

        <div class="customer-address-container">
            <h5 class="client-address-heading">Tax Invoice for :</h5>
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

        <div style="position:relative">
            @include('app.pdf.invoice.partials.table')
        </div>

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
</body>

</html>
