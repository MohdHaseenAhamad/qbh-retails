<!DOCTYPE html>
<html lang="en">
<head>
    <title>@lang('Proforma Invoice Summary')</title>

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
        @if(count($proformas) > 0)
            <div class="company_details">
                @if($proformas[0]->company->logo_path)
                    <div style="position: absolute; width: 125px; max-height: 75px; left: 0;bottom:0;"><img id="logo"  style="width: 100%" src="{{ $proformas[0]->company->logo_path }}"></div>
                @endif
                <div class="header" style="margin-top: -80px">

                    <p class="name">{{ $proformas[0]->company->name }}</p>
                    
                    @if( $proformas[0]->company->address->address_street_1)
                        <p class="address">{{ ucwords( $proformas[0]->company->address->address_street_1) }}
                            {{ 
                                ! $proformas[0]->company->address->city ? '' 
                                : (", ".ucwords( $proformas[0]->company->address->city)) 
                            }}
                            {{ 
                                ! $proformas[0]->company->address->state ? '' 
                                : (", ".ucwords( $proformas[0]->company->address->state)) 
                            }}
                            {{
                                ! $proformas[0]->company->address->country ? '' 
                                : (", ".ucwords( $proformas[0]->company->address->country->code))
                            }}
                        </p>
                    @endif

                    <h2>@lang('Proforma Invoice Summary')</h2>
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
                    <th>Proforma Number</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Amount Due</th>
                    <th>Paid Status</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php $sr_no = 1 @endphp
                @foreach ($proformas as $proforma)
                        <tr>
                            <td>{{ $sr_no++ }}</td>
                            <td>{{ $proforma->formattedProformaDate }}</td>
                            <td>{{ $proforma->proforma_number }}</td>
                            <td>{{ $proforma->customer->name }}</td>
                            <td>{{ $proforma->status }}</td>
                            <td>{!! format_money_pdf($proforma->due_amount, $proforma->customer->currency,true) !!}</td>
                            <td>{{ $proforma->paid_status }}</td>
                            <td>{!! format_money_pdf($proforma->total, $proforma->customer->currency,true) !!}</td>                            
                        </tr>
                @endforeach
            </tbody>
        </table>

    </div>

</body>
</html>
