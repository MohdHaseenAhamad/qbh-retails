<div width="100%" >
    <table width="100%" class="items-table" cellspacing="0" border="0">
        <tr class="item-table-heading-row">
            <th  class=" item-table-heading">#</th>
            <th  class="item-table-heading text-left" width="30%">{{\Lang::get('pdf_items_label',[],'en')}}</th>
            <th class="item-table-heading" style="white-space:nowrap;width:10px;">{{\Lang::get('pdf_quantity_label',[],'en')}}</th>
            <th class="item-table-heading" style="white-space:nowrap;width:10px;">{{\Lang::get('Unit Of Measurement',[],'en')}}</th>
            <th class="item-table-heading" style="white-space:nowrap;width:10px;">{{\Lang::get('pdf_unit_price_label',[],'en')}}({{$estimate->customer->currency->symbol}})</th>
            <th class="item-table-heading" style="white-space:nowrap;width:10px;">{{\Lang::get('pdf_discount_label',[],'en')}}({{$estimate->customer->currency->symbol}})</th>
            @if($estimate->tax_per_item === 'YES')
            <th class="item-table-heading" style="white-space:nowrap;width:10px;">{{\Lang::get('pdf_tax_label',[],'en')}}({{$estimate->customer->currency->symbol}})</th>
            @endif
            <th class="item-table-heading" style="white-space:nowrap;width:10px;">{{\Lang::get('pdf_amount_label',[],'en')}}({{$estimate->customer->currency->symbol}})</th>
        </tr>
        @php
            $index = 1
        @endphp
        @foreach ($estimate->items as $item)
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
                    {!! format_money_pdf($item->price, $estimate->customer->currency,false) !!}
                </td>

                <td
                    class="item-cell"
                    style="vertical-align: middle;"
                >
                    @if($item->discount_type === 'fixed')
                            {!! format_money_pdf($item->discount_val, $estimate->customer->currency,false) !!}
                        @endif
                        @if($item->discount_type === 'percentage')
                            {{$item->discount}}%
                        @endif
                </td>
                
                @if($estimate->tax_per_item === 'YES')
                    <td
                        class="item-cell"
                        style="vertical-align: middle;"
                    >
                        {!! format_money_pdf($item->tax, $estimate->customer->currency,false) !!}
                    </td>
                @endif

                <td
                    class="item-cell"
                    style="vertical-align: middle;"
                >
                    {!! format_money_pdf($item->total, $estimate->customer->currency,false) !!}
                </td>
            </tr>
            @php
                $index += 1
            @endphp
        @endforeach
    </table>
</div>

<hr class="item-cell-table-hr">

<div class="total-display-container">
    <table width="100%" cellspacing="0px"  class="total-display-table">
        <tr>
            <td rowspan="3" class=" text-center" style="border-bottom:2px solid black;"  width="20%">{{\Lang::get('Prepared By',[],'en')}}
                <br>
                @if($signature)
                    @if($signature_image)
                        <img style="max-width: 125px" src="{{ $signature_image }}" alt="prepared_by signature"><br>
                    @endif

                    <strong>{{$signature->name}}</strong><br>
                    <small>
                        @if($signature->designation)
                            Designation: {{ $signature->designation }}<br>
                        @endif
                        @if($signature->contact_number)
                            Contact No.: {{ $signature->contact_number }}<br>
                        @endif
                        @if($signature->email)
                            Email: {{ $signature->email }}<br>
                        @endif
                    </small>
                @endif
            </td>
            <td rowspan="3" class="border" width="20%" style="text-align:center;text-transform: capitalize;font-size:10px;"><strong>{{\Lang::get('Amount in words',[],'en')}}</strong>
                            <br>{{$total_amount_in_en}} only</td>
            <td width="20%" class="border total-table-attribute-label">{{\Lang::get('pdf_subtotal',[],'en')}}</td>
            <td width="30%" class="py-2 border item-cell total-table-attribute-value">
                {!! format_money_pdf($estimate->sub_total, $estimate->customer->currency) !!}
            </td>
        </tr>
        
        @if($estimate->discount > 0)
            @if ($estimate->discount_per_item === 'YES')
                <tr>
                    <td class="border total-table-attribute-label">
                        @if($estimate->discount_type === 'fixed')
                            Total {{\Lang::get('pdf_discount_label',[],'en')}}
                        @endif
                        @if($estimate->discount_type === 'percentage')
                            Total {{\Lang::get('pdf_discount_label',[],'en')}} ({{$estimate->discount}}%)
                        @endif
                    </td>
                    <td class="py-2 border item-cell total-table-attribute-value" >
                        @if($estimate->discount_type === 'fixed')
                            {!! format_money_pdf($estimate->discount_val, $estimate->customer->currency) !!}
                        @endif
                        @if($estimate->discount_type === 'percentage')
                            {!! format_money_pdf($estimate->discount_val, $estimate->customer->currency) !!}
                        @endif
                    </td>
                </tr>
            @else
                <tr>
                    <td class="border total-table-attribute-label">
                        @if($estimate->discount_type === 'fixed')
                            {{\Lang::get('pdf_deduction_label',[],'en')}}
                        @endif
                        @if($estimate->discount_type === 'percentage')
                            {{\Lang::get('pdf_deduction_label',[],'en')}} ({{$estimate->discount}}%)
                        @endif
                    </td>
                    <td class="py-2 border item-cell total-table-attribute-value" >
                        @if($estimate->discount_type === 'fixed')
                            {!! format_money_pdf($estimate->discount_val, $estimate->customer->currency) !!}
                        @endif
                        @if($estimate->discount_type === 'percentage')
                            {!! format_money_pdf($estimate->discount_val, $estimate->customer->currency) !!}
                        @endif
                    </td>
                </tr>
            @endif
        @endif

        @if ($estimate->tax_per_item === 'YES')
            @foreach ($taxes as $tax)
                <tr>
                    <td class="border total-table-attribute-label">
                        {{$tax->taxType->name.' ('.$tax->percent.'%)'}}
                    </td>
                    <td class="py-2 border item-cell total-table-attribute-value">
                        {!! format_money_pdf($tax->amount, $estimate->customer->currency) !!}
                    </td>
                </tr>
            @endforeach
        @else
            @foreach ($estimate->taxes as $tax)
                <tr>
                    <td class="border total-table-attribute-label">
                        {{$tax->taxType->name.' ('.$tax->percent.'%)'}}
                    </td>
                    <td class="py-2 border item-cell total-table-attribute-value">
                        {!! format_money_pdf($tax->amount, $estimate->customer->currency) !!}
                    </td>
                </tr>
            @endforeach
        @endif
        <tr>
            <td class="border total-border-left total-table-attribute-label">
                {{\Lang::get('pdf_total',[],'en')}}
            </td>
            <td
                class="py-8 border total-border-right item-cell total-table-attribute-value"
                style="color: #00008B"
            >
                {!! format_money_pdf($estimate->total, $estimate->customer->currency)!!}
            </td>
        </tr>
    </table>
</div>
