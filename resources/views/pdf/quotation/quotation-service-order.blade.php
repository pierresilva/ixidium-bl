<STYLE TYPE="text/css">
    @page {
        margin-top: 0.5cm;
        margin-bottom: 0.5cm;
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
        text-transform: uppercase;
        font-size: 14px;
    }

    .table tr td {
        border: 1px solid;
        width: 50%;
        text-transform: uppercase;
        font-size: 12px;
    }

</STYLE>


<table style="width: 100%">
    <tbody>
    <tr>
        <td align="right"><span
                    style="font-size: 7pt; font-family: arial, helvetica, sans-serif; text-align: right">
            <em>ORDEN DE SERVICIO CORRESPONDIENTE A SOLICITUD # {{$quotation['code']}}</em></span>
        </td>
    </tr>
    </tbody>
</table>

<table class="table">
    <tbody>
    <tr>
        <th align="center" colspan="3" width="50%">
            <br>
            <img src="{{config('app.url')}}/images/letter/header-letter.jpg" alt="" style="width: 4cm;"/>
            <br>
            <b>NIT 891.180.008-2 </b><br>
            <b>FAX: 8713753 NEIVA - CALLE 11 No. 5 - 63</b></br>
        </th>
        <th align="center" colspan="2" width="50%">
            <b>ORDEN DE SERVICIO INTERNA N&deg; {{$quotation['consecutive_service_order']}} </b>
        </th>
    </tr>
    <tr>
        <td colspan="5" width="651">
            <b>FECHA:</b> {{strftime("%d de %B de %Y") }}
        </td>
    </tr>
    <tr>
        <td colspan="5" width="651">
            <b>FACTURAR A RAZON SOCIAL:</b> {{$quotation['data_confirmation']['business_name_to_invoice']}}
        </td>
    </tr>
    <tr>
        <td colspan="5" width="651">
            <b>NIT:</b> {{$quotation['data_confirmation']['document_to_invoice']}} <b>DIRECCION PARA RADICAR
                FACTURA:</b> {{$quotation['data_confirmation']['address_file_invoice']}}
        </td>
    </tr>
    <tr>
        <td colspan="5" width="651">
            <b>CONCEPTO PARA FACTURAR:</b> {{$quotation['data_confirmation']['concept_to_invoice']}} <b>FECHA CIERRE DE
                FACTURACION:</b> {{strftime("%d de %B de %Y", strtotime($quotation['data_confirmation']['date_receipt_invoice']))}}
        </td>
    </tr>
    <tr>
        <td colspan="5" width="651">
            <b>COORDINADOR EVENTO:</b> {{$quotation['event_coordinator']}} <b>TELEFONO FIJO:</b> {{$quotation['customer']['contact_phone']}}
            <b>CELULAR:</b> {{$quotation['event_phone']}}
        </td>
    </tr>
    <tr>
        <td colspan="5" width="651">
            <b>LUGAR:</b> {{$quotation['location']['location']}} <!-- <b>SALON:</b> -->
        </td>
    </tr>
    <tr>
        <td colspan="5" width="651">
            <b>NOMBRE EVENTO:</b> {{$quotation['event_name']}} <b>HORA
                INICIO:</b> {{ \Carbon\Carbon::parse($quotation['event_start_at_time'])->format('h:i A') }} <b>HORA
                FINALIZACION:</b> {{ \Carbon\Carbon::parse($quotation['event_end_at_time'])->format('h:i A') }}
        </td>
    </tr>
    <tr>
        <td colspan="5" width="651">
            <b>NUMERO DE PERSONAS:</b> {{$quotation['total_people']}}
            <b>ADULTOS:</b> {{$quotation['assistance_adults']}}
            <b>NI&Ntilde;OS:</b> {{$quotation['assistance_children']}} <!--<b>INFANTE:</b>-->
        </td>
    </tr>
    <tr>
        <td colspan="5" width="651">
            <b>FECHA DEL EVENTO:</b> {{strftime("%d de %B de %Y", strtotime($quotation['event_start_at_date']))}} <b>FECHA
                DE
                CONFIRMACIÓN:</b> {{strftime("%d de %B de %Y", strtotime($quotation['data_confirmation']['date_confirmation']))}}
        </td>
    </tr>
    <tr>
        <td colspan="5" width="651">
            <b>FORMA DE PAGO:</b> {{$quotation['data_confirmation']['payment_method']['name']}}
        </td>
    </tr>
    <tr>
        <td align="center" width="400">
            <b>Concepto - Caracter&iacute;sticas del trabajo o servicio</b>
        </td>
        <td align="center">
          <b>Hora</b>
        </td>
        <td align="center" width="60">
            <b>Cantidad</b>
        </td>
        <td align="center" width="78">
            <b>Valor Unitario</b>
        </td>
        <td align="center" width="191">
            <b>Valor Total</b>
        </td>
    </tr>

    @foreach($products as $key => $product)
      <tr>
        <td><b>{{$product['name']}}</b>

          @if ($product['description'])
            <br><span style="font-style: italic; font-size: 10px;">({{ucfirst(mb_strtolower($product['description']))}})</span>
          @endif

          @if ($product['observation'])
            <br><span style="font-size: 10px;"><em>[ {{$product['observation']}} ]</em></span>
          @endif

        </td>
        <td align="center">{{$product['start_at_time']}}</td>
        <td align="center">{{$product['quantity']}}</td>
        <td align="right">$ {{ number_format((float) $product['price']) }}</td>
        <td align="right">$ {{ number_format((float) $product['total']) }}</td>
      </tr>
    @endforeach



    <tr>
        <td colspan="5"><br><br></td>
    </tr>

    <tr>
        <td width="322"><b>NO GRAVADO</b></td>
        <td></td>
        <td width="60"></td>
        <td width="78"></td>
        <td width="191">
            <p style="text-align: right;">{{ number_format((float) $quotation['taxes']['NO_GRAVADO'], 2, ',', '.') }}</p>
        </td>
    </tr>
    <tr>
        <td width="322"><b>GRAVADO 19%</b></td>
        <td width="60"></td>
        <td></td>
        <td width="78"></td>
        <td width="191">
            <p style="text-align: right;">{{ number_format((float) $quotation['taxes']['BASE_IVA'], 2, ',', '.') }}</p>
        </td>
    </tr>
    <tr>
        <td width="322"><b>IVA 19%</b></td>
        <td width="60"></td>
        <td width="78"></td>
        <td></td>
        <td width="191">
            <p style="text-align: right;">{{ number_format((float) $quotation['taxes']['IVA'], 2, ',', '.') }}</p>
        </td>
    </tr>
    <tr>
        <td width="322"><b>IPOCONSUMO</b></td>
        <td width="60"></td>
        <td width="78"></td>
        <td></td>
        <td width="191">
            <p style="text-align: right;">{{ number_format((float) $quotation['taxes']['BASE_IPO'], 2, ',', '.') }}</p>
        </td>
    </tr>
    <tr>
        <td width="322"><b>8%</b></td>
        <td width="60"></td>
        <td width="78"></td>
        <td></td>
        <td width="191">
            <p style="text-align: right;">{{ number_format((float) $quotation['taxes']['IPO'], 2, ',', '.') }}</p>
        </td>
    </tr>
    <tr>
        <td width="322"><b>TOTAL</b></td>
        <td width="60"></td>
        <td width="78"></td>
        <td></td>
        <td align="right"><b>$ {{ number_format((float) $quotation['total']) }}</b></td>
    </tr>
    <tr>
        <td colspan="5">
            <b>OBSERVACIONES:</b> {{$quotation['data_confirmation']['observation_consultant']}}
        </td>
    </tr>
    <tr>
        <td colspan="2" style="padding-top: 20px;vertical-align: center">
            ELABORADO POR: <img src="{{config('app.url')}}{{$quotation['auxiliary_quotation']['signature_path']}}" alt=""
                                style="width: 4cm;"/>
        </td>
        <td colspan="3" rowspan="2" align="center">En caso de incumplimiento por parte del Proveedor &eacute;ste deber&aacute; pagar como sanci&oacute;n el 30% del valor de la orden.<br/> El proveedor se obliga a informar con anterioridad de 48 Horas en caso de no poder cumplir con lo requerido.</td>
    </tr>
    <tr>
        <td colspan="2" align="center">
            <b>{{$quotation['auxiliary_quotation']['third_party']['name']}}</b>
        </td>
    </tr>

    <tr>
        <td colspan="5" width="651">
            PERSONA QUE RECIBE DEL CENTRO:
        </td>
    </tr>
    <tr>
        <td width="322" colspan="2">
            &nbsp;
        </td>
        <td width="60">
            &nbsp;
        </td>
        <td width="78">
            &nbsp;
        </td>
        <td width="191">
            &nbsp;
        </td>
    </tr>
    <tr>
        <td width="322" colspan="2">
            NOMBRE
        </td>
        <td width="60">
            FIRMA
        </td>
        <td width="78">
            FECHA
        </td>
        <td width="191">
            HORA
        </td>
    </tr>
    </tbody>
</table>

<table style="width: 100%;">
    <tbody>
    <tr>
        <td colspan="2"><b>DOCUMENTACIÓN REQUERIDA:</b></td>
    </tr>

    @foreach($documents as $key => $document)
        @if ($document['data']['show_in_service_order'] === 'true')
            <tr>
                <td colspan="2" style="font-size: 11px; text-transform: uppercase;">
                    @if ($document['show'])
                        <img src="{{config('app.url')}}/images/letter/checked.png" style="width: 12px;"/>
                    @else
                        <img src="{{config('app.url')}}/images/letter/unchecked.png" style="width: 12px;"/>
                    @endif

                    {{$document['name']}}
                </td>
            </tr>
        @endif
    @endforeach

    <!--
    <tr>
        <td colspan="2" style="font-size: 11px;">- ORDEN DE SERVICIO DEL CLIENTE</td>
    </tr>
    <tr>
        <td colspan="2" style="font-size: 11px;">- PAGARE / CARTA DE INSTRUCCIONES PARA DILIGENCIAR PAGARE EN BLANCO</td>
    </tr>
    <tr>
        <td colspan="2" style="font-size: 11px;">- CARTA DE CONVENIO (CONVENIO INSTITUCIONAL PARA EL SUMINISTRO DE SERVICIOS Y/O MERCANCIAS A CREDITO</td>
    </tr>
    <tr>
        <td colspan="2" style="font-size: 11px;">- SOLICITUD DE SERVICIO / CREDITO COMERCIAL PARA EMPRESAS</td>
    </tr>
    <tr>
        <td colspan="2" style="font-size: 11px;">- CAMARA DE COMERCIO NO MAYOR A 30 DIAS</td>
    </tr>
    <tr>
        <td colspan="2" style="font-size: 11px;">- RUT ACTUALIZADO</td>
    </tr>
    <tr>
        <td colspan="2" style="font-size: 11px;">- FOTOCOPIA DE LA CEDULA DEL REPRESENTANTE LEGAL.</td>
    </tr>
    -->

    <tr>
        <td align="center" style="font-size: 11px;"><br>CUMPLE CON LA DOCUMENTACION REQUERIDA:</td>
        <td align="center" style="font-size: 11px;"><br>ESTA AL DÍA EN CARTERA:</td>
    </tr>
    <tr>
        <td align="center" style="font-size: 11px;">
            @if ($quotation['data_confirmation']['has_documentation_required'])
                <img src="{{config('app.url')}}/images/letter/checked.png" style="width: 12px;"/> SI
                <img src="{{config('app.url')}}/images/letter/unchecked.png" style="width: 12px;"/> NO
            @else
                <img src="{{config('app.url')}}/images/letter/unchecked.png" style="width: 12px;"/> SI
                <img src="{{config('app.url')}}/images/letter/checked.png" style="width: 12px;"/> NO
            @endif
        </td>
        <td align="center" style="font-size: 11px;">
            @if ($quotation['data_confirmation']['has_uptodate_portfolio'])
                <img src="{{config('app.url')}}/images/letter/checked.png" style="width: 12px;"/> SI
                <img src="{{config('app.url')}}/images/letter/unchecked.png" style="width: 12px;"/> NO
            @else
                <img src="{{config('app.url')}}/images/letter/unchecked.png" style="width: 12px;"/> SI
                <img src="{{config('app.url')}}/images/letter/checked.png" style="width: 12px;"/> NO
            @endif
        </td>
    </tr>
    <tr>
        <td colspan="2" style="font-size: 11px;"><br>PERSONA QUE AUTORIZA CREDITO SIN DOCUMENTACION COMPLETA:</td>
    </tr>
    <tr>
        <td style="font-size: 11px;">NOMBRE Y APELLIDO:_________________________________</td>
        <td style="font-size: 11px;">FIRMA: ______________</td>
    </tr>
    <tr>
        <td colspan="2" style="font-size: 11px;">CARGO: ____________________</td>
    </tr>
    </tbody>
</table>
