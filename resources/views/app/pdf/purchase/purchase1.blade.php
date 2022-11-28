<html>
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Purchase Voucher - {{ $purchase->purchase_no }}</title>

  	<style type="text/css">
	  body{
		  font-family: "dejavu sans"
	  }
	  .arabic {
            font-family: "Lateef";
            font-size:11px;
            /*letter-spacing:1px;*/
        }
  		tr.text-cap td, tr.text-cap th{text-transform: capitalize}
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
    </style>
</head>
<body>

<section style="padding: 25px 0 25px 0">

	<div class="company_details">
        @if($company->logo_path)
            <div style="position: absolute; width: 125px; max-height: 75px; left: 0;"><img id="logo" style="width: 100%" src="{{ $company->logo_path }}"></div>
    		
    		<div class="header" style="margin-top: -70px">
		@else
			<div class="header">
        @endif



	      <p class="name arabic">{{ $company->name_ar }}</p>
	      <p class="address arabic">
	      	{{ $company->address_street_1_ar }} 
	      	{{ $company->address_street_2_ar }}
	      </p>

	      <!-- <h2></h2> -->
	      <p class="name" style="margin-bottom: 10px">{{ $company->name }}</p>
	      <p class="address">
	      	{{ $company->address->address_street_1 }} 
	      	{{ $company->address->address_street_2 }}
	      </p>
	    </div>
	    <div class="footer">
	    	<h3 class="blue_color">
	    		<center>
		    		{{\Lang::get('pdf_label_purchase_voucher',[],'en')}} / 
		    		<span class="arabic">{{\Lang::get('pdf_label_purchase_voucher',[],'ar')}}</span>
		    	</center>
	    	</h3>
	    </div>
  	</div>

	<table class="pdf_table" cellspacing="0">
	  <tr>
	    <th>{{\Lang::get('pdf_label_voucher_no',[],'en')}}</th>
	    <td class="blue_color"><b>{{ $purchase->purchase_no }}</b></td>
	    <td class="arabic">{{\Lang::get('pdf_label_voucher_no',[],'ar')}}</td>
	    <th>{{\Lang::get('pdf_label_voucher_date',[],'en')}}</th>
	    <td>{{ $purchase->formatted_purchase_date }}</td>
	    <td class="arabic">{{\Lang::get('pdf_label_voucher_date',[],'ar')}}</td>
	  </tr>
	  <tr>
	    <th>{{\Lang::get('pdf_label_supplier_invoice_no',[],'en')}}</th>
	    <td>{{ $purchase->invoice_no }}</td>
	    <td class="arabic">{{\Lang::get('pdf_label_supplier_invoice_no',[],'ar')}}</td>
	    <th>{{\Lang::get('pdf_label_supplier_invoice_date',[],'en')}}</th>
	    <td>{{ $purchase->formatted_invoice_date }}</td>
	    <td class="arabic">{{\Lang::get('pdf_label_supplier_invoice_date',[],'ar')}}</td>
	  </tr>
	  <tr>
	    <th>{{\Lang::get('pdf_label_order_no',[],'en')}}</th>
	    <td>{{ $purchase->order_no ?? 'N/A' }}</td>
	    <td class="arabic">{{\Lang::get('pdf_label_order_no',[],'ar')}}</td>
	    <th>{{\Lang::get('pdf_label_order_date',[],'en')}}</th>
	    <td>{{ $purchase->formatted_order_date ?? 'N/A' }}</td>
	    <td class="arabic">{{\Lang::get('pdf_label_order_date',[],'ar')}}</td>
	  </tr>
	  <tr>
	    <th>{{\Lang::get('pdf_label_reference',[],'en')}}</th>
	    <td>{{ $purchase->reference_number ?? 'N/A' }}</td>
	    <td class="arabic">{{\Lang::get('pdf_label_reference',[],'ar')}}</td>
	    <th>{{\Lang::get('pdf_label_supply_date',[],'en')}}</th>
	    <td>{{ $purchase->formatted_supply_date ?? 'N/A' }}</td>
	    <td class="arabic">{{\Lang::get('pdf_label_supply_date',[],'ar')}}</td>
	  </tr>

	  <tr>
	    <th>
	      <span class="arabic">{{\Lang::get('pdf_items_label',[],'ar')}}</span><br>
	      {{\Lang::get('pdf_items_label',[],'en')}}
	    </th>
	    <th>
	      <span class="arabic">{{\Lang::get('pdf_label_credit',[],'ar')}}</span><br>
	      {{\Lang::get('pdf_label_credit',[],'en')}}
	    </th>
	    <th>
	      <span class="arabic">{{\Lang::get('pdf_label_debit',[],'ar')}}</span><br>
	      {{\Lang::get('pdf_label_debit',[],'en')}}
	    </th>
	    <th colspan="2">
	      <span class="arabic">{{\Lang::get('pdf_label_account_ledger',[],'ar')}}</span><br>
	      {{\Lang::get('pdf_label_account_ledger',[],'en')}}
	    </th>
	    <th>
	      <span class="arabic">{{\Lang::get('pdf_label_dr_cr',[],'ar')}}</span><br>
	      {{\Lang::get('pdf_label_dr_cr',[],'en')}}
	    </th>
	  </tr>

	  @php $total_incl_amount = 0 @endphp

	  @foreach ($purchase->items as $item)
	  {{--dd($purchase->items[1])--}}

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
                {!! format_money_pdf($item->total, $purchase->supplier->currency) !!}
            </td>

            <td colspan="2">
            	<span class="right_align">
            		<span class="arabic">{{\Lang::get('pdf_label_purchase_material',[],'ar')}}</span> - {{ $purchase->material_no }} 
            	</span>
            	{{ $purchase->material_no }} - 
            	{{\Lang::get('pdf_label_purchase_material',[],'en')}}
            	<br>
            	<small>
            		{!! format_money_pdf($item->total, $purchase->supplier->currency) !!}
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
            	--
            </td>
            <td class="item-cell" style="vertical-align: middle;">
                {!! format_money_pdf($item->tax, $purchase->supplier->currency) !!}
            </td>

            <td colspan="2">
            	<span class="right_align class="arabic"">
            		{{\Lang::get('pdf_vat_input_account',[],'ar')}}
            	</span>
            	{{\Lang::get('pdf_vat_input_account',[],'en')}}
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
            	@php 
            		$item_incl_amount = $item->tax + $item->total;
            		$total_incl_amount += $item_incl_amount;
            	@endphp
            	{!! 
            		format_money_pdf($item_incl_amount, $purchase->supplier->currency) 
            	!!}
            </td>
            
            <td>
               -- 
            </td>

            <td colspan="2">
            	<span class="right_align">
            		{{ $purchase->supplier->name_ar }} - {{ $purchase->invoice_no }}
            	</span>
            	{{ $purchase->invoice_no }} - {{ $purchase->supplier->name }}
            	<br>
            	<small>
            		{!! format_money_pdf($item->total, $purchase->supplier->currency) !!}
            	</small><br>
            	Cr
            	<span class="right_align">
            		{{ $item_incl_amount }} {{ $purchase->purchase_no }}({{ $purchase->reference_number }})
            	</span>
            </td>

            <td>Cr</td>
	  	</tr>

	  	{{--
  		<tr>
	  		<th></th>
	  		<th>
	  			{!! format_money_pdf($item_incl_amount, $purchase->supplier->currency) !!}
	  		</th>
	  		<th>
	  			{!! format_money_pdf($item_incl_amount, $purchase->supplier->currency) !!}
	  		</th>
	  		<th colspan="6"></th>
	  	</tr>
	  	--}}

	  @endforeach

	  	<tr>
	  		<th>Total Amount</th>
	  		<th>
	  			{!! format_money_pdf($total_incl_amount, $purchase->supplier->currency) !!}
	  		</th>
	  		<th>
	  			{!! format_money_pdf($total_incl_amount, $purchase->supplier->currency) !!}
	  		</th>
	  		<th colspan="6"></th>
	  	</tr>

		<tr class="text-cap">
	    	<th>{{\Lang::get('Amount in words',[],'en')}}</th>
	    	<td colspan="2">
	    		{{$total_amount_in_en}} only
	    	</td>
	    	<td colspan="2">
	    		<span class="arabic"><{{$total_amount_in_ar.' '.\Lang::get('only',[],'ar')}}</span>
	    	</td>
	    	<td>{{\Lang::get('Amount in words',[],'ar')}}</td>
	  	</tr>

	</table>

	<br>
	<table width="100%">
		<tr>
			<th colspan>
		      <span class="right_align arabic">نوافق</span><br>
		      <span class="right_align">
		      	________________Approved By
		      </span>
		    </th>
		    <th colspan="2">
		      <span class="right_align arabic">روجع بواسطة</span><br>
		      <span class="right_align">
		      	________________Verified By
		      </span>
		    </th>
		    <th colspan="">
		      <span class="right_align arabic">استلمت من قبل</span><br>
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