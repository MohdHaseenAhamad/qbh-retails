<!DOCTYPE html>
<html lang="en">
<head>
    <title>@lang('Vat Purchase Register Summary')</title>

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
            margin-top: 1.2cm; 
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
        @if(count($purchases) > 0)
            <div class="company_details">

                @if($purchases[0]->company->logo_path)
                    <div style="position: absolute; width: 125px; max-height: 75px; left: 0;bottom:0;"><img id="logo" style="width: 100%"  src="{{ $purchases[0]->company->logo_path }}"></div>
                @endif
                <div class="header" style="margin-top: -80px">

                    <p class="name">{{ $purchases[0]->company->name }}</p>
                    
                    @if( $purchases[0]->company->address->address_street_1)
                        <p class="address">{{ ucwords( $purchases[0]->company->address->address_street_1) }}
                            {{ 
                                ! $purchases[0]->company->address->city ? '' 
                                : (", ".ucwords( $purchases[0]->company->address->city)) 
                            }}
                            {{ 
                                ! $purchases[0]->company->address->state ? '' 
                                : (", ".ucwords( $purchases[0]->company->address->state)) 
                            }}
                        </p>
                    @endif

                    <h2>@lang('Vat Purchase Register Summary')</h2>
                </div>
                <div class="footer">
                    <p>{{ $date_heading }}</p>
                </div>
            </div>
        @endif

        <table class="pdf_table" cellspacing="0">
            <thead>
                <tr class="info">
                    <th>#</th>
                    <th>Date</th>
                    <th>Purchase Voucher#</th>
                    <th>Supplier Invoice#</th>
                    <th>Supplier</th>
                    <th>Amount<small>(Exclusive VAT)</small></th>
                    <th>Tax Amount<small>(VAT)</small></th>
                    <th>Total (Inclusive VAT)</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $tax_total = 0;
                    $excl_total = 0;
                    $incl_total = 0;
                @endphp
                @foreach ($purchases as $key => $purchase)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $purchase->formattedPurchaseDate }}</td>
                        <td>{{ $purchase->purchase_no }}</td>
                        <td>{{ $purchase->invoice_no }}</td>
                        <td>{{ $purchase->name }}</td>
                        <td>
                            @php $excl_total += $purchase->sub_total @endphp
                            {!! format_money_pdf($purchase->sub_total) !!}
                        </td>
                        <td>
                            @php $tax_total += $purchase->tax @endphp
                            {!! format_money_pdf($purchase->tax) !!}
                        </td>
                        <td>
                            @php $incl_total += $purchase->total @endphp
                            {!! format_money_pdf($purchase->total) !!}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="5" align="right">
                        <b>Total</b>
                    </td>
                    <th>{!! format_money_pdf($excl_total) !!}</th>
                    <th>{!! format_money_pdf($tax_total) !!}</th>
                    <th>{!! format_money_pdf($incl_total) !!}</th>
                </tr>
            </tbody>
        </table>

    </div>

</body>
</html>
