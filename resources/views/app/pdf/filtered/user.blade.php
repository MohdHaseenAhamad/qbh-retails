<!DOCTYPE html>
<html lang="en">
<head>
    <title>@lang('Users Summary')</title>

    <style type="text/css">
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
    <div style="padding: 0 0 25px 0">
        @if(count($users) > 0)
            <div class="company_details">
                <h2>@lang('Users Summary')</h2>
            </div>
        @endif

        <table class="pdf_table" cellspacing="0">
            <thead>
                <tr class="info">
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Added On</th>
                </tr>
            </thead>
            <tbody>
                @php $sr_no = 1 @endphp
                @foreach ($users as $user)
                        <tr>
                            <td>{{ $sr_no++ }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->created_at }}</td>
                        </tr>
                @endforeach
            </tbody>
        </table>

    </div>

</body>
</html>
