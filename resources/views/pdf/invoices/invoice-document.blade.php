<style TYPE="text/css">
    @page {
        margin-top: 1cm;
        margin-bottom: 1cm;
    }

    table {
        border-collapse: collapse;
        border: 1px solid;
        width: 100%;
        text-transform: uppercase;
        font-size: 10px;
    }

    table tr th {
        border: 1px solid;
    }

    table tr td {
        border: 1px solid;
    }

    table#tbl-main tr td strong {
        padding-right: 5px;
    }

    #markwater {
        position: fixed;
        left: 0px;
        top: 0px;
        right: 0px;
        bottom: 0px;
        text-align: center;
        z-index: -1000;
    }

</style>

<table border="1" cellspacing="0" cellpadding="0" id="tbl-main">

    <tr>
        <td align="center">
            <img src="{{config('app.url')}}/upload/logos/{{$location['url_logo']}}" style="width: 4cm;"><br>
            <small><strong>{{$location['location']}}</strong></small>
            <br/>
            <small>{{$location['address']}} - {{$location['phone']}}</small>
            <br>
        </td>
        <td colspan="3" align="center">
            <h3>CAJA DE COMPENSACIÓN {{$infoCompany['razon-social']}}</h3>
            <strong>Nit. {{$infoCompany['nit']}}</strong><br>
            <strong>{{$infoCompany['direccion']}} PBX {{$infoCompany['telefono']}} Neiva</strong><br>
            <span>RESOLUCI&Oacute;N DIAN <em>{{$invoice['dian_resolution']['description']}}</em> de <em>{{$invoice['dian_resolution']['expiration_date']}}</em> </span>
        </td>
    </tr>

    <tr>
        <td colspan="4" align="right"> FACTURA DE VENTA -
            <strong>{{$invoice['prefix']}} {{$invoice['consecutive']}}</strong></td>
    </tr>
    <tr>
        <td colspan="4" align="right">De {{$invoice['dian_resolution']['initial_number']}}
            a {{$invoice['dian_resolution']['final_number']}}</td>
    </tr>
    <tr>
        <td align="right"><strong>Fecha</strong></td>
        <td align="center">{{$invoice['date']}} - {{$invoice['time']}}</td>
        <td align="right"><strong>Punto de Venta</strong></td>
        <td>{{$invoice['warehouse']}}</td>
    </tr>
    <tr>
        <td align="right"><strong>Cliente</strong></td>
        <td colspan="3" align="center"> {{$invoice['third_party']}}</td>
    </tr>
    <tr>
        <td align="right"><strong>CC/Nit</strong></td>
        <td>{{$invoice['document_type']}} {{$invoice['document_number']}}</td>
        <td align="right"><strong>Caja No</strong></td>
        <td>{{$invoice['till']}}</td>
    </tr>
    <tr>
        <td align="right"><strong>Direcci&oacute;n</strong></td>
        <td>{{$invoice['address']}}</td>
        <td align="right"><strong>Tel&eacute;fono</strong></td>
        <td>{{$invoice['phone']}}</td>
    </tr>
    <tr>
        <td align="right"><strong>Observaci&oacute;n</strong></td>
        <td colspan="3">{{$invoice['observation']}}</td>
    </tr>
    <tr>
        <td colspan="4" style="padding:5px" align="center">
            <table border="1">

                <tr>
                    <td><strong>PLU</strong></td>
                    <td><strong>DETALLE</strong></td>
                    <td><strong>CANT.</strong></td>
                    <td><strong>V/UNIT.</strong></td>
                    <td><strong>DESC.</strong></td>
                    <td><strong>%IVA</strong></td>
                    <td><strong>%IMPO.</strong></td>
                    <td><strong>SUBTOTAL</strong></td>
                </tr>
                <tbody>
                @foreach($products as $product)
                    <tr>
                        <td align="center">{{$product['plu']}}</td>
                        <td>{{$product['name']}}</td>
                        <td align="center">{{$product['quantity']}}</td>
                        <td align="right">{{number_format((float) $product['unit_value'], 0, ',', '.')}}</td>
                        <td align="right">{{number_format((float) $product['total_discount'], 0, ',', '.')}}</td>
                        <td align="right">{{number_format((float) $product['taxes_iva'], 0, ',', '.')}}</td>
                        <td align="right">{{number_format((float) $product['taxes_impo'], 0, ',', '.')}}</td>
                        <td align="right">{{number_format((float) $product['subtotal'], 0, ',', '.')}}</td>
                    </tr>
                @endforeach
                </tbody>
                <tr>
                    <td colspan="7" align="right"><strong>VALOR TOTAL</strong></td>
                    <td align="right">$ {{number_format((float) $invoice['total'], 0, ',', '.')}}</td>
                </tr>

            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td colspan="2">
            <table border="1" style="padding:3px">
                <tr>
                    <td colspan="3" align="center">
                        <strong>FORMAS DE PAGO</strong>
                    </td>
                </tr>
                <tr>
                    <th>FORMA DE PAGO</th>
                    <th>OBSERVACI&Oacute;N</th>
                    <th>VALOR</th>
                </tr>
                <tbody>
                @foreach($methodPayments as $payment)
                    <tr>
                        <td>{{$payment['method_payment']}}</td>
                        <td>{{$payment['description']}}</td>
                        <td align="right">{{number_format((float) $payment['value'], 0, ',', '.')}}</td>
                    </tr>
                @endforeach
                </tbody>
                <tr>
                    <td align="center">
                        <strong>CAMBIO</strong>
                    </td>
                    <td align="right">{{number_format((float) $invoice['change'], 0, ',', '.')}}</td>
                    <td align="right">{{number_format((float) $invoice['total_method_payment'], 0, ',', '.')}}</td>
                </tr>

            </table>
        </td>
    </tr>
    <tr>
        <td align="center" colspan="2">
            <strong>GENERADA POR </strong> <br>
            {{$createdBy['first_name']}} {{$createdBy['last_name']}}<br/> CC. {{$createdBy['identification']}}
        </td>
        <td align="center">
            _____________________ <br>
            <strong>FIRMA AUTORIZADA</strong>
        </td>
        <td align="center">
            _____________________ <br>
            <strong>ACEPTA CLIENTE</strong>
        </td>
    </tr>

</table>

@if ($invoice['isCopy'])
    <div id="markwater">
        <img src="{{config('app.url')}}/images/{{$invoice['image']}}" style="margin-top: 7%">
    </div>
@endif