<STYLE TYPE="text/css">
    @page {
        margin-top: 3.5cm;
        margin-bottom: 2cm;
    }

    P {
        margin-bottom: 0.08in;
        direction: ltr;
        widows: 2;
        orphans: 2
    }

    P.western {
        font-family: "Calibri", serif
    }

    P.cjk {
        font-family: "Calibri"
    }

    P.ctl {
        font-family: "Times New Roman"
    }

    A:link {
        color: #0000ff;
        so-language: zxx
    }

    .table {
        border-collapse: collapse;
        border: 1px solid;
        padding: 10px;
        width: 100%;
    }

    .table tr th {
        border: 1px solid;
    }

    .table tr td {
        border: 1px solid;
        width: 50%;
    }

    .table2 {
        border-collapse: collapse;
        border: 1px solid;
        padding: 10px;
        width: 100%;
    }

    .table2 tr th {
        border: 1px solid;
    }

    .table2 tr td{
        border: 1px solid;
        width: 25%;
    }

    .table3 {
        border-collapse: collapse;
        border: 1px solid;
        padding: 10px;
        width: 100%;
    }

    .table3 tr th {
        border: 1px solid;
    }

    .table3 tr td{
        border: 1px solid;
        width: 100%;
    }

</STYLE>

<table>
    <tr>
        <th align="center" style="font-size: 20px"><b>CONFIRMACIÓN DE SERVICIO</b></th>
    </tr>
    <tr>
        <td align="right">
            {{strftime("%d de %B de %Y") }}
        </td>
    </tr>
    <tr>
        <td align="left">
            Con base en la propuesta entregada por Comfamiliar Huila deseamos confirmar la prestación del servicio de acuerdo a la cotización número <b>{{$quotation['code']}}</b> del pasado {{ strftime("%d de %B de %Y", strtotime($quotation['created_at'])) }}
        </td>
    </tr>
</table>


<CENTER>
    <table class="table">
        <tbody>
        <tr BGCOLOR="#d9d9d9">
            <th colspan="2" align="center">
                <strong>Informaci&oacute;n General</strong>
            </th>
        </tr>
        <tr>
            <td>
                Empresa
            </td>
            <td>{{$quotation['customer']['name']}}</td>
        </tr>
        <tr>
            <td>
                Nombre de la persona de contacto
            </td>
            <td>{{$quotation['customer']['contact_first_name']}} {{$quotation['customer']['contact_last_name']}}</td>
        </tr>
        <tr>
            <td>
                Correo electr&oacute;nico
            </td>
            <td>{{$quotation['customer']['email']}}</td>
        </tr>
        <tr>
            <td>
                NIT
            </td>
            <td>{{$quotation['customer']['document']}}</td>
        </tr>
        <tr>
            <td>
                Direcci&oacute;n
            </td>
            <td>{{$quotation['customer']['contact_address']}}</td>
        </tr>
        <tr>
            <td>
                Tel&eacute;fono
            </td>
            <td>{{$quotation['customer']['contact_cellphone']}}</td>
        </tr>
        </tbody>
    </table>

</CENTER>

<CENTER>

    <table class="table">
        <tbody>
        <tr BGCOLOR="#d9d9d9">
            <th colspan="2" align="center">
                <strong>Detalle del Servicio</strong>
            </th>
        </tr>
        <tr>
            <td>
                Nombre del Evento
            </td>
            <td>{{$quotation['event_name']}}</td>
        </tr>
        <tr>
            <td>
                Servicio Solicitado (En este campo es importante anotar lo que incluye la cotizaci&oacute;n)
            </td>
            <td>
                <ul>
                @foreach($products as $key => $product)
                    <li><b>Cant:</b> {{$product['quantity']}} - <b>Nombre: </b>{{ucfirst(mb_strtolower($product['name']))}}
                      @if ($product['description'])
                        <br><span style="font-style: italic; font-size: 10px;">({{ucfirst(mb_strtolower($product['description']))}})</span>
                      @endif
                    </li>
                @endforeach
                </ul>
            </td>
        </tr>
        <tr>
            <td>
                Fecha de realizaci&oacute;n Evento
            </td>
            <td>{{$quotation['event_start_at_date']}}</td>
        </tr>
        <tr>
            <td>
                Lugar del evento
            </td>
            <td>{{$quotation['location']['location']}}</td>
        </tr>
        <tr>
            <td>
                N&uacute;mero de participantes
            </td>
            <td>{{$quotation['total_people']}}</td>
        </tr>
        <tr>
            <td>
                Hora de Inicio
            </td>
            <td>{{ \Carbon\Carbon::parse($quotation['event_start_at_time'])->format('h:i A') }}</td>
        </tr>
        <tr>
            <td>
                Hora de Finalizaci&oacute;n
            </td>
            <td>{{ \Carbon\Carbon::parse($quotation['event_end_at_time'])->format('h:i A') }}</td>
        </tr>
        <tr>
            <td>
                Coordinador del evento
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                Número Celular del Evento
            </td>
            <td>&nbsp;</td>
        </tr>
        </tbody>
    </table>

</CENTER>

<CENTER>

    <table class="table2">
    <tbody>
        <tr BGCOLOR="#d9d9d9">
            <td colspan="3" align="center">
                <strong>Detalle de la Alimentaci&oacute;n</strong>
            </td>
        </tr>
        <tr>
            <td>
                <strong>Detalle</strong>
            </td>
            <td>
                <strong>Men&uacute;</strong>
            </td>
            <td>
                <strong>Hora en la que se debe servir la alimentación</strong>
            </td>
        </tr>

        @foreach($products as $key => $product)
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        @endforeach

        </tbody>
    </table>

</CENTER>

<CENTER>

    <table class="table">
        <tbody>
        <tr BGCOLOR="#d9d9d9">
            <th colspan="2" align="center">
                <strong>Servicio de Transporte</strong>
            </th>
        </tr>
        <tr>
            <td>
                Direcci&oacute;n donde se debe prestar el servicio
            </td>
            <td>
                &nbsp;
            </td>
        </tr>
        <tr>
            <td>
                Hora Salida
            </td>
            <td>
                &nbsp;
            </td>
        </tr>
        <tr>
            <td>
                Hora de Regreso
            </td>
            <td>
                &nbsp;
            </td>
        </tr>
        </tbody>
    </table>

</CENTER>

<CENTER>

    <table class="table3">
        <tbody>
        <tr BGCOLOR="#d9d9d9">
            <th align="center">
                <strong>Observaciones</strong>
            </th>
        </tr>
        <tr>
            <td style="height: 250px;">&nbsp;</td>
        </tr>
        </tbody>
    </table>

</CENTER>

<CENTER>

    <table class="table">
        <tbody>
        <tr BGCOLOR="#d9d9d9">
            <th colspan="2" align="center">
                <strong>Factura</strong>
            </th>
        </tr>
        <tr>
            <td>
                Fecha de cierre para recepci&oacute;n de facturas
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                Qu&eacute; anotaciones complementarias se deben hacer en el campo observaciones de la factura
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                Nit a Facturar
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                Raz&oacute;n social
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                Direccion de recepci&oacute;n de Facturas
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                Forma de Pago
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                C&oacute;digo Postal
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <strong>Valor total a Facturar</strong>
            </td>
            <td>$ {{ number_format((float) $quotation['total'], 0, ',', '.') }}</td>
        </tr>
        </tbody>
    </table>

</CENTER>
<P LANG="es-CO" ALIGN=JUSTIFY STYLE="font-style: normal; line-height: 100%">
    <BR>
</P>
<P LANG="es-CO" CLASS="western" ALIGN=LEFT STYLE="margin-bottom: 0.14in; font-style: normal; line-height: 115%">
    <FONT FACE="Calibri, sans-serif"><FONT SIZE=2 STYLE="font-size: 11pt"><FONT FACE="Arial, sans-serif">Firma
                Autorizada:</FONT><FONT COLOR="#000000"><FONT FACE="Arial, sans-serif"><SPAN LANG="es-ES">
</SPAN></FONT></FONT><FONT FACE="Arial, sans-serif">__________________</FONT></FONT></FONT></P>
<P LANG="es-CO" CLASS="western" ALIGN=LEFT STYLE="margin-bottom: 0.14in; font-style: normal; line-height: 115%">
    <FONT FACE="Calibri, sans-serif"><FONT SIZE=2 STYLE="font-size: 11pt"><FONT FACE="Arial, sans-serif">Nombre
                Completo:</FONT><FONT COLOR="#000000"><FONT FACE="Arial, sans-serif"><SPAN LANG="es-ES">
</SPAN></FONT></FONT></FONT></FONT>
</P>
<P LANG="es-CO" CLASS="western" ALIGN=LEFT STYLE="margin-bottom: 0.14in; font-style: normal; line-height: 115%">
    <FONT FACE="Calibri, sans-serif"><FONT SIZE=2 STYLE="font-size: 11pt"><FONT FACE="Arial, sans-serif">C.C.</FONT></FONT></FONT></P>
<P LANG="es-CO" CLASS="western" ALIGN=LEFT STYLE="margin-bottom: 0.14in; font-style: normal; line-height: 115%">
    <BR><BR>
</P>
