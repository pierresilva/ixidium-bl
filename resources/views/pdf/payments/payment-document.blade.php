<STYLE TYPE="text/css">
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

  #markwater {
    position: fixed;
    left: 0px;
    top: 0px;
    right: 0px;
    bottom: 0px;
    text-align: center;
    z-index: -1000;
  }

</STYLE>

<table>
  <tr>
    <td colspan="3" align="center" style="width: 25%;">
      <IMG SRC="{{config('app.url')}}/upload/logos/{{$location['url_logo']}}" ALIGN=LEFT style="width: 4cm;">
    </td>
    <td colspan="2" rowspan="4" align="center" style="width: 50%;">
      <b>{{$location['location']}}<br/>{{$location['address']}} - {{$location['phone']}}
        <br/>{{$infoCompany['razon-social']}}<br/>Nit. {{$infoCompany['nit']}}<br/>{{$infoCompany['direccion']}}
        PBX {{$infoCompany['telefono']}} Neiva</b>
    </td>
    <td align="center" style="width: 25%;">
      <b>RECIBO NRO. {{$payment['prefix']}} - {{$payment['number_payment']}}</b>
    </td>
  </tr>
  <tr>
    <td colspan="3" align="center">
      <b>FECHA</b>
    </td>
    <td rowspan="3" align="right">
      $ {{ number_format((float) $payment['value'], 0, ',', '.') }}
    </td>
  </tr>
  <tr>
    <td align="center">
      <b>mm</b>
    </td>
    <td align="center">
      <b>dd</b>
    </td>
    <td align="center">
      <b>aaaa</b>
    </td>
  </tr>
  <tr>
    <td align="center">
      {{$date['month']}}
    </td>
    <td align="center">
      {{$date['day']}}
    </td>
    <td align="center">
      {{$date['year']}}
    </td>
  </tr>
  <tr>
    <td colspan="3" align="right">
      <b>RECIBIMOS DE:</b>
    </td>
    <td colspan="2">
      {{$payment['client']}}
    </td>
    <td>
      <b>NIT / CC</b> {{$payment['document_number']}}
    </td>
  </tr>
  <tr>
    <td colspan="3" align="right">
      <b>POR CONCEPTO DE:</b>
    </td>
    <td colspan="3">
      {{$payment['payment_type']}}
    </td>
  </tr>
  <tr>
    <td colspan="3" align="right">
      <b>DESCRIPCI&Oacute;N:</b>
    </td>
    <td colspan="3">
      {{$payment['description']}}
    </td>
  </tr>
  <tr>
    <td colspan="3" align="right">
      <b>SON:</b>
    </td>
    <td colspan="3">
      {{$payment['value_text']}} PESOS MCTE
    </td>
  </tr>
  <tr>
    <td colspan="6">
      @if (count($paymentProducts) > 0 && $payment['payment_controlled'])
        <table>
          <tr>
            <th align="center">Beneficiarios</th>
            <th align="center">Producto</th>
            <th align="center">Valor unitario</th>
            <th align="center">Cantidad</th>
            <th align="center">Total</th>
          </tr>
          @foreach($paymentProducts as $key => $value)
            <tr>
              <td>
                <table>
                  <tr>
                    <th align="center">Tipo Doc.</th>
                    <th align="center">Nro. Doc.</th>
                    <th align="center">Nombre</th>
                  </tr>
                  @foreach($value['affiliates'] as $key2 => $value2)
                    <tr>
                      <td align="center">{{$value2['document_type']}}</td>
                      <td>{{$value2['document_number']}}</td>
                      <td>{{$value2['name']}}</td>
                    </tr>
                  @endforeach
                </table>
              </td>
              <td>{{$value['product']}}</td>
              <td align="right"> $ {{ number_format((float) $value['value_with_subsidy'], 0, ',', '.') }}</td>
              <td align="center">{{$value['quantity']}}</td>
              <td align="right"> $ {{ number_format((float) $value['total'], 0, ',', '.') }}</td>
            </tr>
          @endforeach
        </table>
      @endif
    </td>
  </tr>
  <tr>

    <td colspan="3" align="center">
      <b>FORMAS DE PAGO</b>
    </td>

    <td colspan="3">
      <table style="border-left: none; border-right: none; border-top: none; border-bottom: none;">
        @foreach($methodPayment as $key => $value)
          <tr>
            <td style="border-left: none; border-right: none;">{{$value['method_payment']}}</td>
            <td style="border-left: none; border-right: none;" align="right">
              $ {{ number_format((float) $value['value'], 0, ',', '.') }}</td>
            <td style="border-left: none; border-right: none; padding-left: 10px;">{{$value['description']}}</td>
          </tr>
        @endforeach
      </table>

    </td>
  </tr>

  <tr>
    <td colspan="5" align="right">
      <b>TOTAL:</b>
    </td>
    <td align="right">
      $ {{ number_format((float) $payment['total_method_payment'], 0, ',', '.') }}
    </td>
  </tr>
  <tr>
    <td colspan="3" align="center">
      <b>REVISADO</b>
    </td>
    <td colspan="2" align="center">
      <b>CONTABILIZADO</b>
    </td>
    <td align="center">
      <b>FIRMA Y SELLO CAJERO</b>
    </td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td align="center">
      <br><br><br><br><br>
      {{$createdBy['first_name']}} {{$createdBy['last_name']}}<br/> CC. {{$createdBy['identification']}}
    </td>
  </tr>
  <tr>
    <td colspan="6">
      <b>AA-08-05</b>
    </td>
  </tr>
</table>

@if ($payment['is_copy'])
  <div id="markwater">
    <img src="{{config('app.url')}}/images/{{$payment['image']}}" style="margin-top: 7%">
  </div>
@endif
