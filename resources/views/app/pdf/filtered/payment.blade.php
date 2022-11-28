<!DOCTYPE html>
<html lang="en">
<head>
    <title>@lang('Payments Summary')</title>

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

    
    <div style="padding: 25px 0 25px 0">
        @if(count($payments) > 0)
            <div class="company_details">
                @if($payments->company->logo_path)
                    <div style="width: 125px; max-height: 75px;"><img id="logo" style="width: 100%" src="{{ $payments->company->logo_path }}"></div>
                @endif
                <div class="header" style="margin-top: -80px">

                    <p class="name">{{ $payments->company->name }}</p>
                    
                    @if( $payments->company->address->address_street_1)
                        <p class="address">{{ ucwords( $payments->company->address->address_street_1) }}
                            {{ 
                                ! $payments->company->address->city ? '' 
                                : (", ".ucwords( $payments->company->address->city)) 
                            }}
                            {{
                                ! $payments->company->address->state ? '' 
                                : (", ".ucwords( $payments->company->address->state)) 
                            }}
                            {{
                                ! $payments->company->address->country ? '' 
                                : (", ".ucwords( $payments->company->address->country->code))
                            }}
                        </p>
                        </p>
                    @endif

                    <h2>@lang('Payments Summary')</h2>
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
                    <th>Payment Number</th>
                    <th>Invoice Number</th>
                    <th>Customer</th>
                    <th>Payment Mode</th>
                    <th>Total</th>
                    <th>Amount Due</th>
                </tr>
            </thead>
            <tbody>
                @php $sr_no = 1 @endphp
                @foreach ($payments as $payment)
                    <tr>
                        <td>{{ $sr_no++ }}</td>
                        <td>{{ $payment->formattedPaymentDate }}</td>
                        <td>{{ $payment->payment_number }}</td>
                        <td>{{ $payment->invoice->invoice_number }}</td>
                        <td>{{ $payment->customer->name }}</td>
                        <td>{{ $payment->paymentMethod->name ?? '-' }}</td>
                        <td>{!! $payment->status == 'CANCEL' ? '##' :  format_money_pdf($payment->amount, $payment->invoice->customer->currency,true) !!}</td>
                        <td>{!! format_money_pdf($payment->invoice->due_amount, $payment->invoice->customer->currency,true) !!}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

</body>
</html>
