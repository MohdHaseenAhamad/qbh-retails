<table width="100%" class="items-table" cellspacing="0" border="0">
                <tr class="item-table-heading-row">
                    <th  class=" item-table-heading">#</th>
                    <th  class="item-table-heading text-left" width="25%"><span class="arabic" >{{\Lang::get('pdf_items_label',[],'ar')}}</span><br>{{\Lang::get('pdf_items_label',[],'en')}}</th>
                    <th class="item-table-heading"><span class="arabic" >{{\Lang::get('pdf_quantity_label',[],'ar')}}</span><br>{{\Lang::get('pdf_quantity_label',[],'en')}}</th>
                    <th class="item-table-heading" ><span class="arabic" >{{\Lang::get('Unit Of Measurement',[],'ar')}}</span><br>{{\Lang::get('Unit Of Measurement',[],'en')}}</th>
                    <th class="item-table-heading" width="10%"><span class="arabic" >{{\Lang::get('pdf_unit_price_label',[],'ar')}}</span><br>{{\Lang::get('pdf_unit_price_label',[],'en')}}
                        ({{$proforma->customer->currency->symbol}})</th>
                    <th class="item-table-heading" ><span class="arabic" >{{\Lang::get('pdf_amount_label',[],'ar')}}</span><br>{{\Lang::get('pdf_amount_label',[],'en')}}
                    ({{$proforma->customer->currency->symbol}})</th>
                    @if($proforma->discount_per_item === 'YES')
                    <th class="item-table-heading"><span class="arabic" >{{\Lang::get('pdf_discount_label',[],'ar')}}</span><br>{{\Lang::get('pdf_discount_label',[],'en')}}
                    ({{$proforma->customer->currency->symbol}})</th>
                    @else
                    <th class="item-table-heading"><span class="arabic" >{{\Lang::get('pdf_deduction_label',[],'ar')}}</span><br>{{\Lang::get('pdf_deduction_label',[],'en')}}
                    ({{$proforma->customer->currency->symbol}})</th>
                    @endif

                    <th class="item-table-heading" ><span class="arabic" >{{\Lang::get('tax_rate',[],'ar')}}</span><br>{{\Lang::get('tax_rate',[],'en')}}</th>

                    @if($proforma->tax_per_item === 'YES')
                    <th class="item-table-heading"><span class="arabic" >{{\Lang::get('pdf_tax_label',[],'ar')}}</span><br>{{\Lang::get('pdf_tax_label',[],'en')}}
                    ({{$proforma->customer->currency->symbol}})</th>
                    @endif
                    <th class="item-table-heading" ><span class="arabic" >{{\Lang::get('pdf_taxable_amount_label',[],'ar')}}</span><br>{{\Lang::get('pdf_taxable_amount_label',[],'en')}}
                    ({{$proforma->customer->currency->symbol}})</th>
                </tr>
                @php
                    $index = 1;
                    $item_discount = 0;
                    $items_price = 0;
                @endphp
                @foreach ($proforma->items as $item)
                    <tr class="item-row">
                        <td
                            class="item-cell"
                            style="vertical-align: middle;"
                        >
                            {{$index}}
                        </td>
                        <td
                            class="item-cell text-left"
                            style="vertical-align: middle;"
                        >
                            <span>{{$item->name}}</span><br>
                            <span class="item-description">{!! nl2br(htmlspecialchars($item->description)) !!}</span>
                        </td>
                        <td
                            class="item-cell"
                            style="vertical-align: middle;"
                        >
                            {{$item->quantity}}
                        </td>
                        <td
                            class="item-cell"
                            style="vertical-align: middle;"
                        >
                            @if($item->unit_name) {{$item->unit_name}} @else - @endif
                        </td>
                        <td
                            class="item-cell"
                            style="vertical-align: middle;"
                        >
                            {!! format_money_pdf($item->price, $proforma->customer->currency,false) !!}
                        </td>
                        <td
                            class="item-cell"
                            style="vertical-align: middle;"
                        >
                           {!! format_money_pdf($item->price*$item->quantity, $proforma->customer->currency,false) !!}
                        </td>
                        @if($proforma->discount_per_item === 'YES')
                            <td
                                class="item-cell"
                                style="vertical-align: middle;"
                            >
                                @if($item->discount_type === 'fixed')
                                        {!! format_money_pdf($item->discount_val, $proforma->customer->currency,false) !!}
                                    @endif
                                    @if($item->discount_type === 'percentage')
                                        {{$item->discount}}%
                                    @endif
                            </td>
                        @else

                            <td
                                class="item-cell"
                                style="vertical-align: middle;"
                            >
                                @if($item->discount_type === 'fixed')
                                        {!! format_money_pdf($item->discount_val, $proforma->customer->currency,false) !!}
                                    @endif
                                    @if($item->discount_type === 'percentage')
                                        {{$item->discount}}%
                                    @endif
                            </td>
                        @endif
                        <td
                            class="item-cell"
                            style="vertical-align: middle;"
                        >

                            {{count($item->taxes) > 0 ? ($item->taxes[0] ? $item->taxes[0]->percent : 15) : 15}}%
                        </td>

                        @if($proforma->tax_per_item === 'YES')
                            <td
                                class="item-cell"
                                style="vertical-align: middle;"
                            >
                                {!! format_money_pdf($item->tax, $proforma->customer->currency,false) !!}
                            </td>
                        @endif

                        <td
                            class="item-cell"
                            style="vertical-align: middle;"
                        >
                            {!! format_money_pdf($item->total, $proforma->customer->currency,false) !!}
                        </td>
                    </tr>
                    @php
                        $index += 1;
                        $item_discount = $item_discount + ($item->discount_val ?? 0);
                        $items_price = $items_price + $item->price * $item->quantity;
                    @endphp
                @endforeach
            </table>

<hr class="" style="color:#E8E8E8;margin-top:10px;margin: 0px 5px 10px 10px;" />

<div class="total-display-container">
    <table width="100%" cellspacing="0px" border="0" class="price_table total-display-table @if(count($proforma->items) > 12) page-break @endif">
                <tr>
                    <td class="border" rowspan="10" width="40%" style="text-align:center;text-transform: capitalize;font-size:10px;"><strong><span class="arabic" >{{\Lang::get('Amount in words',[],'ar')}}</span> / {{\Lang::get('Amount in words',[],'en')}}</strong>
                        <br><span class="arabic" >{{$total_amount_in_ar.' '.\Lang::get('only',[],'ar')}}</span> / <br> {{$total_amount_in_en}} only</td>
                    <td width="45%" class="border total-table-attribute-label"><span dir="rtl" class="arabic" >{{\Lang::get('pdf_subtotal_exc_tax',[],'ar')}}</span> / {{\Lang::get('pdf_subtotal_exc_tax',[],'en')}}</td>
                    <td width="15%" class="py-2 border item-cell total-table-attribute-value">
                        {!! format_money_pdf($items_price, $proforma->customer->currency) !!}
                    </td>
                </tr>


               <tr>
                            <td class="border total-table-attribute-label">
                                 @if ($proforma->discount_per_item === 'NO')
                                    <span dir="rtl" class="arabic">{{\Lang::get('pdf_deduction_label',[],'ar')}}</span> / {{\Lang::get('pdf_deduction_label',[],'en')}}
                                @else
                                    <span dir="rtl" class="arabic">{{\Lang::get('pdf_discount_label',[],'ar')}}</span> / {{\Lang::get('pdf_discount_label',[],'en')}}
                                @endif
                                
                            </td>
                            <td class="py-2 border item-cell total-table-attribute-value" >
                                    {!! format_money_pdf($item_discount + $proforma->discount_val, $proforma->customer->currency) !!}
                                
                            </td>
                        </tr>
                <tr>
                    <td class="border  total-table-attribute-label">
                        <span dir="rtl" class="arabic" >{{\Lang::get('total_taxable_amount',[],'ar')}}</span><br> / {{\Lang::get('total_taxable_amount',[],'en')}}
                    </td>
                    <td
                        class="py-8 border item-cell total-table-attribute-value"
                        style="color: #00008B"
                    >
                        {!! format_money_pdf($proforma->sub_total, $proforma->customer->currency) !!}
                    </td>
                </tr>

                @if ($proforma->tax_per_item === 'YES')
                    @foreach ($taxes as $tax)
                        <tr>
                            <td class="border total-table-attribute-label">
                                {{$tax->name.' ('.$tax->percent.'%)'}}
                            </td>
                            <td class="py-2 border item-cell total-table-attribute-value">
                                {!! format_money_pdf($tax->amount, $proforma->customer->currency) !!}
                            </td>
                        </tr>
                    @endforeach
                @else
                    @foreach ($proforma->taxes as $tax)
                        <tr>
                            <td class="border total-table-attribute-label">
                                {{$tax->name.' ('.$tax->percent.'%)'}}
                            </td>
                            <td class="py-2 border item-cell total-table-attribute-value">
                                {!! format_money_pdf($tax->amount, $proforma->customer->currency) !!}
                            </td>
                        </tr>
                    @endforeach
                @endif

                
                <tr>
                    <td class="border  total-table-attribute-label">
                        <span dir="rtl" class="arabic" >{{\Lang::get('pdf_total_amount_due',[],'ar')}}</span> / {{\Lang::get('pdf_total_amount_due',[],'en')}}
                    </td>
                    <td
                        class="py-8 border item-cell total-table-attribute-value"
                        style="color: #00008B"
                    >
                        {!! format_money_pdf($proforma->total, $proforma->customer->currency)!!}
                    </td>
                </tr>
            </table>
</div>
