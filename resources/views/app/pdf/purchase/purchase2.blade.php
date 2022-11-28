<html>
<head>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title>Purchase Voucher - {{ $purchase->purchase_no }}</title>

    <style type="text/css">
      span.right_align {
        float: right;
        display: block;
        width: 100%;
        text-align: right;
    }
      .blue_color{color: #00008B;}
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
            /*font-size: 10px;*/
            font-size: 12px;            
            text-align: center;
            /*padding: 2px;*/
            padding: 5px;
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
        div.company_details div.footer .left{
           float: left;
          }
        div.company_details div.footer .right{
           float: right;
          }

        @page {
            @if($letterhead)
                background: url({{$letterhead}}) no-repeat 0 0;
                background-image-resize: 6;
            @endif
                margin-top:<?php echo $purchase->upper_margin?>cm;
                margin-bottom:<?php echo $purchase->lower_margin?>cm;
        }

    </style>
</head>
<body>

<section>

  <div class="company_details">
      <div class="header">

        <p class="name arabic">{{ $company->name_ar }}</p>
        <p class="address arabic">
          {{ $company->address_street_1_ar }} 
          {{ $company->address_street_2_ar }}
        </p>

        <!-- <h2></h2> -->
        <p class="name">{{ $company->name }}</p>
        <p class="address">
          {{ $company->address->address_street_1 }} 
          {{ $company->address->address_street_2 }}
        </p>
      </div>
      <div class="footer">
        <h3 class="blue_color">
          <center>
            {{\Lang::get('pdf_label_purchase_voucher',[],'en')}} / 
            {{\Lang::get('pdf_label_purchase_voucher',[],'ar')}}  
          </center>
        </h3>
      </div>
    </div>

  <table class="pdf_table" cellspacing="0">
    <tr>
      <th>{{\Lang::get('pdf_label_voucher_no',[],'en')}}</th>
      <td class="blue_color"><b>{{ $purchase->purchase_no }}</b></td>
      <td>{{\Lang::get('pdf_label_voucher_no',[],'ar')}}</td>
      <th>{{\Lang::get('pdf_label_voucher_date',[],'en')}}</th>
      <td>{{ $purchase->formatted_purchase_date }}</td>
      <td>{{\Lang::get('pdf_label_voucher_date',[],'ar')}}</td>
    </tr>
    <tr>
      <th>{{\Lang::get('pdf_label_supplier_invoice_no',[],'en')}}</th>
      <td>{{ $purchase->invoice_no }}</td>
      <td>{{\Lang::get('pdf_label_supplier_invoice_no',[],'ar')}}</td>
      <th>{{\Lang::get('pdf_label_supplier_invoice_date',[],'en')}}</th>
      <td>{{ $purchase->invoice_date }}</td>
      <td>{{\Lang::get('pdf_label_supplier_invoice_date',[],'ar')}}</td>
    </tr>
    <tr>
      <th>{{\Lang::get('pdf_label_order_no',[],'en')}}</th>
      <td>{{ $purchase->order_no ?? 'N/A' }}</td>
      <td>{{\Lang::get('pdf_label_order_no',[],'ar')}}</td>
      <th>{{\Lang::get('pdf_label_order_date',[],'en')}}</th>
      <td>{{ $purchase->formatted_order_date ?? 'N/A' }}</td>
      <td>{{\Lang::get('pdf_label_order_date',[],'ar')}}</td>
    </tr>
    <tr>
      <th>{{\Lang::get('pdf_label_reference',[],'en')}}</th>
      <td>{{ $purchase->reference_number ?? 'N/A' }}</td>
      <td>{{\Lang::get('pdf_label_reference',[],'ar')}}</td>
      <th>{{\Lang::get('pdf_label_supply_date',[],'en')}}</th>
      <td>{{ $purchase->formatted_supply_date ?? 'N/A' }}</td>
      <td>{{\Lang::get('pdf_label_supply_date',[],'ar')}}</td>
    </tr>

    <tr>
      <th>
        <span>{{\Lang::get('pdf_items_label',[],'ar')}}</span><br>
        {{\Lang::get('pdf_items_label',[],'en')}}
      </th>
      <th>
        <span>{{\Lang::get('pdf_label_credit',[],'ar')}}</span><br>
        {{\Lang::get('pdf_label_credit',[],'en')}}
      </th>
      <th>
        <span>{{\Lang::get('pdf_label_debit',[],'ar')}}</span><br>
        {{\Lang::get('pdf_label_debit',[],'en')}}
      </th>
      <th colspan="2">
        <span>{{\Lang::get('pdf_label_account_ledger',[],'ar')}}</span><br>
        {{\Lang::get('pdf_label_account_ledger',[],'en')}}
      </th>
      <th>
        <span>{{\Lang::get('pdf_label_dr_cr',[],'ar')}}</span><br>
        {{\Lang::get('pdf_label_dr_cr',[],'en')}}
      </th>
    </tr>

    @foreach ($purchase->items as $item)

      <tr>
        <td class="item-cell text-left" style="vertical-align: middle;">
                <span>{{$item->name}}</span><br>
                <span class="item-description">
                  {!! nl2br(htmlspecialchars($item->description)) !!}
                </span>
            </td>
            
            <td>
              --
            </td>
            
            <td class="item-cell" style="vertical-align: middle;">
                {!! format_money_pdf($item->price*$item->quantity, $purchase->supplier->currency,false) !!}
            </td>

            <td>
              <span class="right_align">
                {{\Lang::get('pdf_label_purchase_material',[],'ar')}} - {{ $purchase->material_no }} 
              </span>
              {{ $purchase->material_no }} - 
              {{\Lang::get('pdf_label_purchase_material',[],'en')}}
              <br>
              <small>
                {!! format_money_pdf($item->price*$item->quantity, $purchase->supplier->currency,false) !!}
              </small>
            </td>

            <td>Dr</td>
      </tr>

      <tr>
        <td class="item-cell text-left" style="vertical-align: middle;">
                <span>{{$item->name}}</span><br>
                <span class="item-description">
                  {!! nl2br(htmlspecialchars($item->description)) !!}
                </span>
            </td>
            
            <td>
              @php $item_excl_amount = $item->tax + ($item->price*$item->quantity) @endphp
              {!! format_money_pdf($item_excl_amount, $purchase->supplier->currency,false) !!}
            </td>
            
            <td>
               -- 
            </td>

            <td>
              <span class="right_align">
                {{ $purchase->supplier->name_ar }} - {{ $purchase->invoice_no }}
              </span>
              {{ $purchase->invoice_no }} - {{ $purchase->supplier->name }}
              <br>
              <small>
                {!! format_money_pdf($item->price*$item->quantity, $purchase->supplier->currency,false) !!}
              </small><br>
              Cr
              <span class="right_align">
                {{ $item_excl_amount }} {{ $purchase->purchase_no }}({{ $purchase->reference_number }})
              </span>
            </td>

            <td>Cr</td>
      </tr>

    @endforeach

      <tr>
        <th>{{\Lang::get('Amount in words',[],'en')}}</th>
        <td>
          {{$total_amount_in_en}} only
        </td>
        <td colspan="2">
          {{$total_amount_in_ar.' '.\Lang::get('only',[],'ar')}}</span>
        </td>
        <td>{{\Lang::get('Amount in words',[],'ar')}}</td>
      </tr>

  </table>

  <br>
  <table width="100%">
    <tr>
      <th colspan>
          <span class="right_align">نوافق</span><br>
          <span class="right_align">
            ________________Approved By
          </span>
        </th>
        <th colspan="2">
          <span class="right_align">روجع بواسطة</span><br>
          <span class="right_align">
            ________________Verified By
          </span>
        </th>
        <th colspan="">
          <span class="right_align">استلمت من قبل</span><br>
          <span class="right_align">
            ________________Received By
          </span>
        </th>
        <th></th>
      </tr>
  </table>

</section>

</body>
</html>