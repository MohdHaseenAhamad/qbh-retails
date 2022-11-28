<!DOCTYPE html>
<html lang="en">
<head>
    <title>@lang('Vat Sales Register Summary')</title>

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
        @if(count($invoices) > 0)
            <div class="company_details">
                    @if($invoices[0]->company->logo_path)
                        <div style="position: absolute; width: 125px; max-height: 75px; left: 0;bottom:0;"><img id="logo" style="width: 100%" src="{{ $invoices[0]->company->logo_path }}"></div>
                    @endif
                <div class="header" style="margin-top: -80px">

                    <p class="name">{{ $invoices[0]->company->name }}</p>
                    
                    @if( $invoices[0]->company->address->address_street_1)
                        <p class="address">{{ ucwords( $invoices[0]->company->address->address_street_1) }}
                            {{ 
                                ! $invoices[0]->company->address->city ? '' 
                                : (", ".ucwords( $invoices[0]->company->address->city)) 
                            }}
                            {{ 
                                ! $invoices[0]->company->address->state ? '' 
                                : (", ".ucwords( $invoices[0]->company->address->state)) 
                            }}
                            {{
                                ! $invoices[0]->company->address->country ? '' 
                                : (", ".ucwords( $invoices[0]->company->address->country->code))
                            }}
                        </p>
                    @endif

                    <h2>@lang('Vat Sales Register Summary')</h2>
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
                    <th>Invoice Number</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Amount Due</th>
                    <th>Paid Status</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php $sr_no = 1 @endphp
                @foreach ($invoices as $invoice)
                        <tr>
                            <td>{{ $sr_no++ }}</td>
                            <td>{{ $invoice->formattedInvoiceDate }}</td>
                            <td>{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->customer->name }}</td>
                            <td>{{ $invoice->status }}</td>
                            <td>{!! format_money_pdf($invoice->due_amount, $invoice->customer->currency,true) !!}</td>
                            <td>{{ $invoice->paid_status }}</td>
                            <td>{!! format_money_pdf($invoice->total, $invoice->customer->currency,true) !!}</td>                            
                        </tr>
                @endforeach
            </tbody>
        </table>

    </div>

</body>
</html>
