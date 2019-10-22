<STYLE TYPE="text/css">
    @page {
        margin-top: 0.5cm;
        margin-bottom: 0.5cm;
    }

    /******************* Estilos para la tabla con bordes ************************/
    .table-with-border {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
        font-size: 12px;
    }

    .table-with-border td, .table-with-border th {
        border: 1px solid #ddd;
        padding: 5px;
    }

    .table-with-border tr:nth-child(even){background-color: #f2f2f2;}

    .table-with-border th {
        padding-top: 10px;
        padding-bottom: 10px;
        background-color: #169451;
        color: white;
        text-align: center;
    }
    /*****************************************************************************/


    /******************* Estilos para la tabla sin bordes ************************/
    .table-without-border {
        border: none;
        font-size: 13px;
        width: 100%;
        text-transform: uppercase;
    }

    .table-without-border tr th, td{
        border: none;
    }
    /*****************************************************************************/

</STYLE>

<table class="table-without-border">
    <tr>
        <td style="font-size: 24px; width: 100%">
            <IMG SRC="{{config('app.url')}}/upload/logos/{{$location->url_logo}}" ALIGN=LEFT style="width: 4cm;">
            <p style="text-align: center; margin-top: -10%;"><b>PLAN DE CALIDAD</b></p>
        </td>
    </tr>
</table>

<table class="table-without-border">
    <tr>
        <th><b>EVENTO:</b></th>
        <td>{{$quotation['event_name']}}</td>
        <th><b>EMPRESA</b></th>
        <td>{{$quotation['customer']['name']}}</td>

        @if ($quotation['event_start_at_date'] === $quotation['event_end_at_date'])
            <td colspan="3" style="font-size: 16px;" align="center"><b>{{strftime("%d de %B de %Y", strtotime($quotation['event_start_at_date']))}}</b></td>
        @else
            <td colspan="3" style="font-size: 16px;" align="center"><b>DEL {{strftime("%d de %B de %Y", strtotime($quotation['event_start_at_date']))}} AL {{strftime("%d de %B de %Y", strtotime($quotation['event_end_at_date']))}}</b></td>
        @endif

    </tr>
    <tr>
        <th><b>FORMA DE PAGO:</b></th>
        <td>{{$quotation['data_confirmation']['payment_method']['name']}}</td>
        <th><b>NIT:</b></th>
        <td>{{$quotation['customer']['document']}}</td>
        <td style="font-size: 16px;" colspan="3" align="center"><b>ORDEN No. {{$quotation['code_quality_plan']}}</b></td>
    </tr>
    <tr>
        <th><b>COORDINADOR:</b></th>
        <td>{{$quotation['event_coordinator']}}</td>
        <th><b>DIRECCION:</b></th>
        <td>{{$quotation['customer']['contact_address']}}</td>
        <th><b>No. PERSONAS:</b></th>
        <td>{{$quotation['total_people']}}</td>
        <td></td>
    </tr>
    <tr>
        <th><b>TELEFONO:</b></th>
        <td>{{$quotation['customer']['contact_cellphone']}}</td>
        <th><b>HORA:</b></th>
        <td>{{ \Carbon\Carbon::parse($quotation['event_start_at_time'])->format('h:i A') }} - {{ \Carbon\Carbon::parse($quotation['event_end_at_time'])->format('h:i A') }}</td>
        <td colspan="3" align="right"></td>
    </tr>
</table>

<br>

<table class="table-with-border">
    <thead>
    <TR>
        <TH><B>SERV. SOLICITADO</B></TH>
        <TH><B>REQUISITOS</B></TH>
        <TH><B>CANT</B></TH>
        <TH><B>HORA</B></TH>
        <TH><B>OBSERVACIONES</B></TH>
        <TH><B>MENU</B></TH>
        <TH><B>INSTALACIONES</B></TH>
        <TH><B>V/UNIDAD</B></TH>
        <TH><B>TOTAL</B></TH>
        <TH><B>PLU</B></TH>
        <TH><B>SEGUIMIENTO</B></TH>
        <TH><B>REGISTRO</B></TH>
    </TR>
    </thead>

    <TBODY>
    @foreach($products as $key => $product)
        <TR>
            <TD>{{$product['solicited_service']}}</TD>
            <TD>{{$product['name']}}</TD>
            <TD ALIGN="CENTER">{{$product['quantity']}}</TD>
            <TD ALIGN="CENTER">{{$product['start_at_time']}}</TD>
            <TD>{{$product['observation']}}</TD>
            <TD>{!! $product['description'] !!}</TD>
            <TD>{{$product['installation']}}</TD>
            <TD align="right">${{ number_format((float) $product['price']) }}</TD>
            <TD align="right">${{ number_format((float) $product['total']) }}</TD>
            <TD ALIGN="CENTER">{{$product['product']['code']}}</TD>
            <TD>{{$product['tracing']}}</TD>
            <TD>{{$product['registry']}}</TD>
        </TR>
    @endforeach
    </TBODY>

    <TBODY>
    <TR>
        <TD></TD>
        <TD COLSPAN=7 align="center"><B>TOTAL</B></TD>
        <TD  align="right"><B>${{ number_format((float) $quotation['total']) }}</B></TD>
        <TD></TD>
        <TD></TD>
        <TD></TD>
    </TR>
    </TBODY>

    <TBODY>
    <TR>
        <TD><B>OBSERVACIONES</B></TD>
        <TD COLSPAN=11 STYLE="text-align: justify;">{{$quotation['data_confirmation']['observation']}}</TD>
    </TR>
    </TBODY>

</TABLE>

<table class="table-with-border">
    <tbody>
        <tr>
            <th width="10%">ELABORO: </th>
            <td style="height: 25px; text-transform: uppercase;" colspan="{{count($areas)}}">{{$user['first_name'] . ' ' . $user['last_name']}}</td>
        </tr>
        <tr>
            <th width="10%">APROBO: </th>

            @foreach($areas as $key => $area)
                <td align="center" style="height: 60px; vertical-align: bottom; text-transform: uppercase;"><b>{{ $area }}</b></td>
            @endforeach

        </tr>
    </tbody>
</table>
