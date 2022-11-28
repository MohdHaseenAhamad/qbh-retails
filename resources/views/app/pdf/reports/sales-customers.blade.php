<!DOCTYPE html>
<html lang="en">
<head>
    <title>@lang('pdf_sales_customers_label')</title>

    <style type="text/css">
         body {
            font-family: "DejaVu Sans";
                        /*font-family: "Lateef";*/
        }
        .arabic {
            font-family: "Lateef";
            font-size:13px;
            /*letter-spacing:1px;*/
        }
        @page {
            margin-top: 0.2cm; 
            margin-bottom: 0.5cm;
            margin-left: 0.2cm; 
            margin-right: 0.2cm; 
            footer: page-footer;
        }
        table.pdf_table {
            width: 100%;
        }
        table.pdf_table th {
            font-weight: bold;
            background: #a6caf0;
        }
        table.pdf_table td, table.pdf_table th {
            border: 0.5px solid #ccc;
            font-size: 10px;
            text-align: center;
            padding: 2px;
        }
        div.company_details {
            text-align: center;
        }
        div.company_details div.header p.name {
            font-weight: bold;
            color: #555;
            font-size: 15px;
            margin-bottom: 0;
        }
        div.company_details div.header p.address {
            margin: 0px 0 8px;
            font-size: 12px;
            color: #555;
        }
        div.company_details div.header h2 {
            margin: 5px;
            text-transform: uppercase;
            color: #034b96;
            font-size: 18px;
        }
        div.company_details div.footer {
            border-top: 2px solid #227fe0;
        }
        div.company_details div.footer p {
            margin: 5px;
            font-size: 12px;
        }
    </style>

</head>
<body>

    <htmlpagefooter name="page-footer">
        <div style="font-size: 10px; color: #555; padding-bottom: 5px; text-align: center;">Page No. {PAGENO} of {nbpg}</div>
    </htmlpagefooter>

    <div style="padding: 25px 0 25px 0">

        <div class="company_details">
            @if($company->logo_path)
                <div style="position: absolute; width: 125px; max-height: 75px; left: 0;bottom:0;"><img id="logo"  style="width: 100%" src="{{ $company->logo_path }}"></div>
            @endif
            <div class="header" style="margin-top: -80px">

                <p class="name">{{ $company->name }}</p>
                
                @if($company->address->address_street_1)
                    <p class="address">{{ ucwords($company->address->address_street_1) }}
                        {{ 
                            !$company->address->city ? '' 
                            : (", ".ucwords($company->address->city)) 
                        }}
                        {{ 
                            !$company->address->state ? '' 
                            : (", ".ucwords($company->address->state)) 
                        }}
                    </p>
                @endif

                <h2>@lang('pdf_customer_sales_report')</h2>
            </div>
            <div class="footer">
                <p>For the period starting from {{ $from_date }} - {{ $to_date }}</p>
            </div>
        </div>

        <table class="pdf_table" cellspacing="0">
            <thead>
                <tr class="info">
                    <th>#</th>
                    <th>Invoice No</th>
                    <th>Invoice Date</th>
                    <th>Client Name</th>
                    <th>Client VAT#</th>
                    <th>Qty.</th>
                    <th>VAT<small>(@15%)</small></th>
                    <th>Total <small>(Exclusive VAT)</small></th>
                    <th>Total <small>(Inclusive VAT)</small></th>
                </tr>
            </thead>
            <tbody>
                
                @php 
                    $sr_no = 1;
                    $vat_total = 0;
                    $excl_vat_total = 0;
                    $incl_vat_total = 0
                @endphp
                @foreach ($customers as $customer)
                        <tr>
                            <td>{{ $sr_no++ }}</td>
                            <td>{{ $customer->invoice_number }}</td>
                            <td>{{ $customer->invoice_date }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->website ?? 'N/A' }}</td>
                            <td>
                                {{ $customer->sum_quantity }}
                            </td>
                            <td>
                                @php 
                                    $vat_amt = $customer->tax;
                                    $vat_total += $vat_amt;
                                @endphp
                                {!! 
                                    format_money_pdf($vat_amt, $customer->currency) 
                                !!}
                            </td>
                            <td>
                                @php 
                                    $exclusive_vat = ($customer->sub_total);
                                    $excl_vat_total += $exclusive_vat;
                                @endphp
                                {!! 
                                    format_money_pdf($exclusive_vat, $customer->currency) 
                                !!}
                            </td>
                            <td>
                                @php 
                                    $incl_vat = ($customer->sub_total + $customer->tax);
                                    $incl_vat_total += $incl_vat;
                                @endphp
                                {!! 
                                    format_money_pdf($incl_vat, $customer->currency) 
                                !!}
                            </td>
                        </tr>
                @endforeach

                @if($customers)
                    <tr>
                        <th colspan="6" align="right">Total</th>
                        <th>
                            {!! format_money_pdf($vat_total, $company->currency) !!}
                        </th>
                        <th>
                            {!! format_money_pdf($excl_vat_total, $company->currency) !!}
                        </th>
                        <th>
                            {!! format_money_pdf($incl_vat_total, $company->currency) !!}
                        </th>
                    </tr>
                @endif

            </tbody>
        </table>

    </div>

</body>
</html>
