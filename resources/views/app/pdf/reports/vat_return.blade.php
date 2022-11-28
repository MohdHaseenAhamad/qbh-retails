<!DOCTYPE html>
<html lang="en">
<head>
    <title>@lang('pdf_purchase_report')</title>

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

        .arabic {
            font-family: "Lateef";
            font-size:11px;
            /*letter-spacing:1px;*/
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


            <div class="header" style="margin-top: -70px">

                @if($company->logo_path)
                    <div><img style="width: 125px; max-height: 95px;" id="logo"  src="{{ $company->logo_path }}"></div>
                @endif

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

                <h2>@lang('pdf_vat_return_report')</h2>
            </div>
            <div class="footer">
                <p>For the period starting from {{ $from_date }} - {{ $to_date }}</p>
            </div>
        </div>
        
        <table class="pdf_table">
            <tr>
                <th>#</th>
                <th></th>
                <th><span class="arabic">(لاير) مبلغ ضريبة القيمة المضافة</span><br>
                    VAT Amount (SAR)
                 </th>
                <th><span class="arabic">(لاير) مبلغ التعحيل   </span><br>
                  Adjustment (SAR)                                    
                 </th>
                <th><span class="arabic">(لاير)المبلغ</span><br>
                Amount (SAR)
                </th>
                <th></th>
                <th>#</th>
            </tr>

            <tr>
                <td>1</td>
                <td>Standard Rates Sales @ 5%</td>
                <td>0.0</td>
                <td>00.0</td>
                <td>00.0</td>
                <td class="arabic">
                    ‫‪٪٥‬ـ‬ ‫أالساسية@‬ ‫بالنسبة‬ ‫المضافة‬ ‫القيمة‬ ‫لضريبة‬ ‫الخاضعة‬ ‫المحلية‬ ‫المبيعات‬
                </td>
                <td>1</td>
            </tr>
            <tr>
                <td>1A</td>
                <td>Standard Rates Sales @ 15%</td>
                <td>
                    {!! 
                        format_money_pdf($amount['final_sales_vat'], $company->currency) 
                    !!}
                </td>
                <td>
                    {!! 
                        format_money_pdf($amount['credit'], $company->currency) 
                    !!}
                </td>
                <td>
                    {!! 
                        format_money_pdf($amount['sales'], $company->currency) 
                    !!}
                </td>
                <td class="arabic">‫‪٪١٥‬ـ‬ ‫أالساسية@‬ ‫بالنسبة‬ ‫المضافة‬ ‫القيمة‬ ‫لضريبة‬ ‫الخاضعة‬ ‫المحلية‬ ‫المبيعات‬</td>
                <td>1A</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Private Healthcare / Private Education /
                   First house sales to citizens</td>
                <th> </th>
                <td>00.0</td>
                <td>00.0</td>
                <td class="arabic">الرعاية الصحية الخاصة / التعليم الخاص / مبيعات المنازل الأولى للمواطنين</td>
                <td>2</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Zero Rated Domestic Sales</td>
                <th> </th>
                <td>00.0</td>
                <td>00.0</td>
                <td class="arabic">المبيعات المحلية المصنفة صفر</td>
                <td>3</td>
            </tr>
            <tr>
                <td>4</td>
                <td>Exports</td>
                <th> </th>
                <td>00.0</td>
                <td>00.0</td>
                <td class="arabic">صادرات</td>
                <td>4</td>
            </tr>
            <tr>
                <td>5</td>
                <td>Exempt Sales</td>
                <th> </th>
                <td>00.0</td>
                <td>00.0</td>
                <td class="arabic">المبيعات المعفاة</td>
                <td>5</td>
            </tr>
            <tr>
                <th>6</th>
                <th>Total Sales</th>
                <th>
                    {!! 
                        format_money_pdf($amount['final_sales_vat'], $company->currency) 
                    !!}
                </th>
                <th>
                    {!! 
                        format_money_pdf($amount['credit'], $company->currency) 
                    !!}
                </th>
                <th>
                    {!! 
                        format_money_pdf($amount['sales'], $company->currency) 
                    !!}
                </th>
                <th class="arabic">إجمالي المبيعات</th>
                <th>6</th>
            </tr>
            <tr>
                <td>7</td>
                <td>Standard Rated Domestic Purchases @ 5%</td>
                <td>00.0 </td>
                <td>00.0</td>
                <td>00.0</td>
                <td class="arabic">المشتريات المحلية المصنفة القياسية @ 5٪</td>
                <td>7</td>
            </tr>
            <tr>
                <td>7A</td>
                <td>Standard Rated Domestic Purchases @ 15%</td>
                <td>
                    {!! 
                        format_money_pdf($amount['final_purchase_vat'], $company->currency) 
                    !!}
                </td>
                <td>
                    {!! 
                        format_money_pdf($amount['debit'], $company->currency) 
                    !!}
                </td>
                <td>
                    {!! 
                        format_money_pdf($amount['purchase'], $company->currency) 
                    !!}
                </td>
                <td class="arabic">المشتريات المحلية المصنفة القياسية @ 15٪</td>
                <td>7A</td>
            </tr>
            <tr>
                <td>8</td>
                <td>Imports Subject to VAT paid at customs @ 5%</td>
                <td>00.0 </td>
                <td>00.0</td>
                <td>00.0</td>
                <td class="arabic">الواردات الخاضعة لضريبة القيمة المضافة المدفوعة في الجمارك بنسبة 5٪</td>
                <td>8</td>
            </tr>
            <tr>
                <td>8A</td>
                <td>Imports Subject to VAT paid at customs @ 15%</td>
                <td>00.0 </td>
                <td>00.0</td>
                <td>00.0</td>
                <td class="arabic">الواردات الخاضعة لضريبة القيمة المضافة المدفوعة في الجمارك @ 15٪</td>
                <td>8A</td>
            </tr>
            <tr>
                <td>9</td>
                <td>Imports Subject to VAT accounted from RCM @5% </td>
                <td>00.0 </td>
                <td>00.0</td>
                <td>00.0</td>
                <td class="arabic">الواردات الخاضعة لضريبة القيمة المضافة محسوبة من rcm @ 5٪</td>
                <td>9</td>
            </tr>
            <tr>
                <td>9a</td>
                <td>Imports Subject to VAT accounted from RCM @ 15%</td>
                <td>00.0 </td>
                <td>00.0</td>
                <td>00.0</td>
                <td class="arabic">الواردات الخاضعة لضريبة القيمة المضافة المحسوبة من rcm @ 15٪</td>
                <td>9a</td>
            </tr>
            <tr>
                <td>10</td>
                <td>Zero Rated Purchases</td>
                <th> </th>
                <td>00.0</td>
                <td>00.0</td>
                <td class="arabic">المشتريات المصنفة صفر</td>
                <td>10</td>
            </tr>
            <tr>
                <td>11</td>
                <td>Exempt Purchases</td>
                <th></th>
                <td>00.0</td>
                <td>00.0</td>
                <td class="arabic">المشتريات المعفاة</td>
                <td>11</td>
            </tr>
            <tr>
                <th>12</th>
                <th>Total Purchases</th>
                <th>
                    {!! 
                        format_money_pdf($amount['final_purchase_vat'], $company->currency) 
                    !!}
                </th>
                <th>
                    {!! 
                        format_money_pdf($amount['debit'], $company->currency) 
                    !!}
                </th>
                <th>
                    {!! 
                        format_money_pdf($amount['purchase'], $company->currency) 
                    !!}
                </th>
                <th class="arabic">إجمالي المشتريات</th>
                <th>12</th>
            </tr>
            <tr>
                <td>13</td>
                <td>Total VAT Due for Current Period</td>
                <th>
                    {!! 
                        format_money_pdf($amount['net_vat_due'], $company->currency) 
                    !!}
                </th>
                <th></th>
                <th></th>
                <td class="arabic">إجمالي ضريبة القيمة المضافة المستحقة عن الفترة الحالية</td>
                <td>13</td>
            </tr>
            <tr>
                <td>14</td>
                <td>Total VAT Due for Current Period</td>
                <th></th>
                <th></th>
                <th></th>
                <td class="arabic">إجمالي ضريبة القيمة المضافة المستحقة عن الفترة الحالية</td>
                <td>14</td>
            </tr>
            <tr>
                <td>15</td>
                <td>VAT Previous from forward Carried Credit </td>
                <th></th>
                <th></th>
                <th></th>
                <td class="arabic">ضريبة القيمة المضافة السابقة من رصيد مرحل آجل</td>
                <td>15</td>
            </tr>
            <tr>
                <th></th>
                <th>Net VAT Due (or Claim) </th>
                <th>
                    {!! 
                        format_money_pdf($amount['net_vat_due'], $company->currency) 
                    !!}
                </th>
                <th></th>
                <th></th>
                <th class="arabic">صافي ضريبة القيمة المضافة المستحقة (أو المطالبة)</th>
                <th></th>
            </tr>
        </table>    

    </div>

</body>
</html>
