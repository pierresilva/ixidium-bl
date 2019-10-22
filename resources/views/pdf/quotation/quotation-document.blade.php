<STYLE TYPE="text/css">
  @page {
    margin-top: 3.5cm;
    margin-bottom: 4cm;
  }

  P {
    margin-bottom: 0.02in;
    direction: ltr;
    widows: 2;
    orphans: 2
  }

  .no-padding {
    font-size: 11pt !important;
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
    font-style: italic;
    width: 100%;
  }

  .table tr th {
    border: 1px solid;
    padding: 5px;
  }

  .table tr td {
    border: 1px solid;
    padding: 5px;
  }

  .table2 {
    border-collapse: collapse;
    border: 1px solid;
    font-style: italic;
  }

  .table2 tr th {
    border: 1px solid;
  }

  .table2 tr td {
    border-right: 1px solid;
  "
  }
</STYLE>
<P CLASS="western" STYLE="margin-bottom: 0in; line-height: 100%"><FONT FACE="Arial, serif"><SPAN
      LANG="es-ES"><I>Neiva, {{strftime("%d de %B de %Y") }}</I></SPAN></FONT></P>

<br>
<span class="western no-padding"><i>Señores:</i></span><br>
<span class="western no-padding"><i><b>{{$quotation['customer']['name']}}</b></i></span><br>
<span class="western no-padding"><i>Ciudad</i></span><br>

<P CLASS="western" STYLE="margin-left: 8cm; margin-bottom: 0in; line-height: 100%">
  <FONT FACE="Arial, serif"><SPAN LANG="es-ES"><I></I></SPAN></FONT><FONT FACE="Arial, serif">
    <SPAN LANG="es-ES"><I><B>COTIZACION No. {{$quotation['code']}}</B></I></SPAN></FONT></P>

<P CLASS="western" STYLE="margin-bottom: 0in; line-height: 100%"><FONT FACE="Arial, serif"><SPAN LANG="es-ES"><I>Cordial saludo,</I></SPAN></FONT></P>
<P LANG="es-ES" CLASS="western" STYLE="margin-bottom: 0in; line-height: 100%"><BR></P>
<P ALIGN=JUSTIFY STYLE="margin-top: 0.19in; margin-bottom: 0.19in; line-height: 100%">
  <FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT COLOR="#000000"><FONT FACE="Arial, serif"><FONT SIZE=2
                                                                                                         STYLE="font-size: 11pt"><I>De
              acuerdo a su solicitud comedidamente me permito enviar cotización de
              actividad </I></FONT></FONT></FONT><FONT COLOR="#000000"><FONT FACE="Arial, serif"><FONT
            SIZE=2 STYLE="font-size: 11pt"><I><B>“{{$quotation['event_name']}}” </B></I></FONT></FONT></FONT><FONT
        COLOR="#000000"><FONT
          FACE="Arial, serif"><FONT SIZE=2 STYLE="font-size: 11pt"><I>
              a realizarse en las instalaciones de {{$quotation['location']['location']}}
              .</I></FONT></FONT></FONT></FONT></FONT>
</P>
<P ALIGN=JUSTIFY STYLE="margin-top: 0.19in; margin-bottom: 0.19in; line-height: 100%">
  <BR>
</P>

<span class="western no-padding"><i>Lugar: {{$quotation['location']['location']}}</i></span><br>
<span class="western no-padding"><i>Fecha: {{strftime("%d de %B de %Y", strtotime($quotation['event_start_at_date']))}}</i>
</span><br>
<span class="western no-padding"><i>Participantes: {{$quotation['total_people']}} personas.</i></span><br>

<P ALIGN=JUSTIFY STYLE="margin-top: 0.19in; margin-bottom: 0.19in; line-height: 100%">
  <BR>
</P>

<table class="table">
  <tbody>
  <tr>
    <td align="center"><b>No.</b></td>
    <td align="center"><b>Concepto - Caracter&iacute;sticas del trabajo o servicio</b></td>
    <td align="center"><b>Cantidad</b></td>
    <td align="center"><b>Valor Unitario</b></td>
    <td align="center"><b>Valor Total</b></td>
  </tr>

  @foreach($products as $key => $product)
    <tr>
      <td align="center"><b>{{( $key + 1 )}}</b></td>
      <td>{{ucfirst(mb_strtolower($product['name']))}}
        @if ($product['description'])
          <br><span style="font-style: italic; font-size: 10px;">({{ucfirst(mb_strtolower($product['description']))}})</span>
        @endif
      </td>
      <td align="center">{{$product['quantity']}}</td>
      <td align="right">{{ number_format((float) $product['price'], 0, ',', '.') }}</td>
      <td align="right">{{ number_format((float) $product['total'], 0, ',', '.') }}</td>
    </tr>
  @endforeach

  <tr>
    <td colspan="4"><b>NO GRAVADO</b></td>
    <td align="right">{{ number_format((float) $quotation['taxes']['NO_GRAVADO'], 0, ',', '.') }}</td>
  </tr>
  <tr>
    <td colspan="4"><b>GRAVADO 19%</b></td>
    <td align="right">{{ number_format((float) $quotation['taxes']['BASE_IVA'], 0, ',', '.') }}</td>
  </tr>
  <tr>
    <td colspan="4"><b>IVA 19%</b></td>
    <td align="right">{{ number_format((float) $quotation['taxes']['IVA'], 0, ',', '.') }}</td>
  </tr>
  <tr>
    <td colspan="4"><b>IPOCONSUMO</b></td>
    <td align="right">{{ number_format((float) $quotation['taxes']['BASE_IPO'], 0, ',', '.') }}</td>
  </tr>
  <tr>
    <td colspan="4"><b>8%</b></td>
    <td align="right">{{ number_format((float) $quotation['taxes']['IPO'], 0, ',', '.') }}</td>
  </tr>
  <tr>
    <td colspan="4"><b>TOTAL</b></td>
    <td align="right">$ {{ number_format((float) $quotation['total'], 0, ',', '.') }}</td>
  </tr>
  </tbody>
</table>

<P CLASS="western" ALIGN=CENTER STYLE="margin-bottom: 0in"><BR>
</P>
<P CLASS="western" ALIGN=CENTER STYLE="margin-bottom: 0in"><FONT FACE="Arial, serif"><I><B>PROPUESTA
        VALIDA 15 DIAS CALENDARIO</B></I></FONT></P>
<P ALIGN=CENTER STYLE="margin-top: 0.19in; margin-bottom: 0.19in; line-height: 100%">
  <FONT FACE="Times New Roman, serif"><FONT SIZE=3><FONT FACE="Arial, serif"><I><B>TARIFA
            MINIMA PARA GRUPO DE {{$quotation['total_people']}} PERSONAS</B></I></FONT></FONT></FONT></P>
<P ALIGN=CENTER STYLE="margin-top: 0.19in; margin-bottom: 0.19in; line-height: 100%">
  <BR>
</P>
<P CLASS="western" STYLE="margin-bottom: 0.14in"><FONT FACE="Arial, serif"><I><B>Nota:
        La facturación será discriminada ítem por ítem y se reflejaran
        los impuestos de ley.</B></I></FONT></P>
<P CLASS="western" STYLE="margin-bottom: 0.14in"><BR>
</P>
<P CLASS="western" ALIGN=JUSTIFY STYLE="margin-bottom: 0in; line-height: 100%; page-break-after: avoid">
  <FONT FACE="Arial, serif"><SPAN LANG="es-ES"><I><B>REQUISITOS</B></I></SPAN></FONT></P>
<UL>
  <LI><P CLASS="western" ALIGN=JUSTIFY STYLE="margin-bottom: 0in; line-height: 100%">
      <FONT FACE="Arial, serif"><I>Carta de solicitud especificando el
          servicio. </I></FONT>
    </P></LI>
  <LI><P CLASS="western" ALIGN=JUSTIFY STYLE="margin-bottom: 0in; line-height: 100%">
      <FONT FACE="Arial, serif"><I>Confirmación del evento con 10 días
          de anticipación. </I></FONT>
    </P></LI>
  <LI><P CLASS="western" ALIGN=JUSTIFY STYLE="margin-bottom: 0in; line-height: 100%">
      <FONT FACE="Arial, serif"><SPAN LANG="es-ES"><I>El pago debe ser
                        cancelado antes de realizar el evento.</I></SPAN></FONT></P></LI>
  <LI><P CLASS="western" ALIGN=JUSTIFY STYLE="margin-bottom: 0in; line-height: 100%">
      <FONT FACE="Arial, serif"><SPAN LANG="es-ES"><I>Puede hacer el pago en Nuestras oficinas o realizar la
                        consignación a nuestra Cuenta Corriente: 456-0013174-6 - Banco: Bancolombia
                        (* En caso de pago en efectivo, debe ser cancelado antes de realizar el evento.
                        * Cotización valida a 30 días calendario
                        * No se permite el ingreso de bebidas ni comidas.
                        * LA COTIZACION NO IMPLICA LA CONFIRMACION DE LA RESERVA).</I></SPAN></FONT></P></LI>
</UL>

<P CLASS="western" ALIGN=JUSTIFY STYLE="margin-left: 0.25in; margin-bottom: 0in; line-height: 100%"><b>
    <FONT FACE="Arial, serif"><SPAN LANG="es-ES"><I>La consignación se debe realizar a nombre de Comfamiliar Huila con el código de referencia N2:

    <ul>
        <li>Club los Lagos… 1230</li>
        <li>Playa Juncal……1226</li>
        <li>Las Termales…..1233</li>
    </ul>

      PAGO  POR CORRESPONSAL BANCARIO CONVENIO 35469,
      la copia de consignación debe ser enviada escaneada por correo electrónico
</I></SPAN></FONT></b></P>
<UL>
  <LI><P CLASS="western" ALIGN=JUSTIFY STYLE="margin-bottom: 0in; line-height: 100%">
      <FONT FACE="Arial, serif"><I><b>En caso de solicitar crédito el valor
            del evento debe ser como mínimo de un salario mínimo legal vigente
            y deben firmar un pagare en blanco o elaborar orden de compra
            debidamente firmada por la persona autorizada y deben estipular la
            siguiente información:</b></I></FONT></P>
</UL>
<P CLASS="western" ALIGN=JUSTIFY STYLE="margin-bottom: 0in; line-height: 100%">
  <BR>
</P>
<P CLASS="western" ALIGN=JUSTIFY STYLE="margin-bottom: 0in; line-height: 100%">
  “<FONT FACE="Arial, serif"><I><B>La presente orden sirve como
        título ejecutivo para el cobro de las obligaciones económicas a
        favor de Comfamiliar que de dicha orden se derivan; así mismo se
        indicara que se autoriza a Comfamiliar para facturar los valores
        adeudados”.</B></I></FONT></P>
<P CLASS="western" ALIGN=JUSTIFY STYLE="margin-bottom: 0in; line-height: 100%">
  <BR>
</P>
<P CLASS="western" ALIGN=JUSTIFY STYLE="margin-bottom: 0in; line-height: 100%">
  <FONT FACE="Arial, serif"><I>Además deben adjuntar los siguientes
      documentos:</I></FONT></P>
<P CLASS="western" ALIGN=JUSTIFY STYLE="margin-bottom: 0in; line-height: 100%">
  <FONT FACE="Arial, serif"><I>Solicitud de crédito, cámara de
      comercio no mayor a 30 días, Rut actualizado y fotocopia de la
      cedula del representante legal. </I></FONT>
</P>
<P CLASS="western" ALIGN=JUSTIFY STYLE="margin-bottom: 0in; line-height: 100%"><A NAME="_GoBack"></A>
  <BR>
</P>
<P CLASS="western" ALIGN=CENTER STYLE="margin-bottom: 0in; line-height: 100%">
  <FONT FACE="Arial, serif"><SPAN LANG="es-ES"><I><B>LA COTIZACION NO
IMPLICA LA CONFIRMACION DE LA RESERVA.</B></I></SPAN></FONT></P>

<P CLASS="western" ALIGN=CENTER STYLE="margin-bottom: 0in; line-height: 100%">
  <FONT COLOR="#000000"><FONT FACE="Arial, serif"><I><B>DEBERES</B></I></FONT></FONT><FONT COLOR="#000000"><FONT
      FACE="Arial, serif"><I>:</I></FONT></FONT></P>

<CENTER>

  <table class="table2">
    <tbody>
    <tr>
      <th>
        <p><strong><em>CLIENTES</em></strong></p>
      </th>
      <th>
        <p><strong><em>CENTRO</em></strong></p>
      </th>
    </tr>
    <tr>
      <td>Respetar al personal responsable de la prestaci&oacute;n y administraci&oacute;n de los servicios del centro
        de convenciones.
      </td>
      <td>Realizar y prestar los servicios a cabalidad seg&uacute;n los requerimientos de los clientes plasmados en la
        previa cotizaci&oacute;n o contrataci&oacute;n.
      </td>
    </tr>
    <tr>
      <td>No realizar actos que afecten la infraestructura ni su nombre (prohibido pegar publicidad sobre las columnas,
        puertas, paredes y vidrios del C. convenciones
      </td>
      <td>Dar cumplimiento con la reserva del sal&oacute;n o el espacio una vez sea confirmado y cancelado el 50%. Como
        abono.
      </td>
    </tr>
    <tr>
      <td>Cancelar el 50% del valor cotizado para mantener la reserva.</td>
      <td>Prestar de manera &iacute;ntegra todos los servicios requeridos.</td>
    </tr>
    <tr>
      <td>Cancelar oportunamente el valor total de la contrataci&oacute;n con cinco (5) d&iacute;as de anticipaci&oacute;n
        de inicio del evento.
      </td>
      <td>Cumplir estrictamente con las horas contratadas por el cliente.</td>
    </tr>
    <tr>
      <td>Presentar de manera clara la informaci&oacute;n y requerimientos del evento a cotizar.</td>
      <td>Encender el aire acondicionado media hora de anterioridad a inicio del evento.</td>
    </tr>
    <tr>
      <td>Informar el personal de log&iacute;stica que estar&aacute; a cargo del evento.</td>
      <td>Apagar los equipos de aire acondicionado seg&uacute;n las horas contratadas.</td>
    </tr>
    <tr>
      <td>Contratar de forma integral (alimentaci&oacute;n, bebidas, log&iacute;stica, servicios t&eacute;cnicos
        adicionales) y en general las necesidades que exija el evento con el centro de convenciones
      </td>
      <td>Ofrecer media hora para dar termino total al evento, si se transcienden los t&eacute;rminos de tiempo se
        cobrar&aacute; la hora adicional
      </td>
    </tr>
    <tr>
      <td>Cumplir estrictamente con las horas contratadas de alquiler, (si se extiende el evento ser&aacute; cobrado las
        horas adicionales).
      </td>
      <td>Preservar la seguridad de sus eventos y sus asistentes.</td>
    </tr>
    <tr>
      <td>Utilizar de manera &oacute;ptima las instalaciones del centro de convenciones si se ocasionan da&ntilde;os el
        cliente asumir&aacute; el pago respectivo del arreglo.
      </td>
      <td>Prestar unas instalaciones &oacute;ptimas para el desarrollo de los eventos.</td>
    </tr>
    <tr>
      <td>Cancelar oportunamente los adicionales generados y autorizados en el desarrollo del evento si los hay.</td>
      <td></td>
    </tr>
    <tr>
      <td>Gestionar ante las entidades competentes el pago de impuestos si el evento lo amerita.</td>
      <td></td>
    </tr>
    <tr>
      <td>Presentar certificado SAYCO y &nbsp;ACIMPRO si el evento lo amerita.</td>
      <td></td>
    </tr>
    </tbody>
  </table>

</CENTER>
<P CLASS="western" ALIGN=CENTER STYLE="margin-bottom: 0in; line-height: 100%">
  <FONT COLOR="#000000"><FONT FACE="Arial, serif"><I><B>DERECHOS</B></I></FONT></FONT><FONT COLOR="#000000"><FONT
      FACE="Arial, serif"><I>:</I></FONT></FONT></P>
<CENTER>

  <table class="table2">
    <tbody>
    <tr>
      <th><b>CLIENTE</b></th>
      <th><b>CENTRO</b></th>
    </tr>
    <tr>
      <td>A ser bien atendido antes y &nbsp;durante el desarrollo del evento.</td>
      <td>Recibir una informaci&oacute;n clara y oportuna de los requerimientos del evento.</td>
    </tr>
    <tr>
      <td>Recibir un buen trato sin ning&uacute;n tipo de discriminaci&oacute;n.</td>
      <td>Recibir un buen&nbsp; trato por parte del cliente</td>
    </tr>
    <tr>
      <td>A un servicio oportuno eficiente y continuo.</td>
      <td>Dar inicio y terminaci&oacute;n seg&uacute;n las horas contratadas.</td>
    </tr>
    <tr>
      <td>A unas tarifas proporcionales y una informaci&oacute;n (cotizaci&oacute;n) oportuna.</td>
      <td>Adicionar a la factura los cargos agregados e imprevistos presentados durante el desarrollo del evento.</td>
    </tr>
    <tr>
      <td>A presentar quejas, peticiones, y reclamos.</td>
      <td>A un pago oportuno por los servicios prestados seg&uacute;n lo pactado.</td>
    </tr>
    <tr>
      <td>A hacer sugerencias</td>
      <td>Preservar las obligaciones adquiridas por el cliente.</td>
    </tr>
    <tr>
      <td>A preguntar, a replicar y a comparar los servicios.</td>
      <td>Instalaciones &oacute;ptimas para la prestaci&oacute;n de los servicios.</td>
    </tr>
    <tr>
      <td>A disfrutar de un ambiente adecuado y de unas instalaciones confortables.</td>
      <td></td>
    </tr>
    <tr>
      <td>A la reserva del lugar cotizado una vez este su 50% abonado.</td>
      <td></td>
    </tr>
    </tbody>
  </table>

</CENTER>
<P CLASS="western" STYLE="margin-bottom: 0in; line-height: 100%"><BR>
</P>
<P CLASS="western" ALIGN=JUSTIFY STYLE="margin-bottom: 0in; line-height: 100%">
  <FONT FACE="Arial, serif"><SPAN LANG="es-ES"><I>Cualquier inquietud
será atendida con mucho gusto en el teléfono 8713094 ext. 4101 o al
correo electrónico <A HREF="mailto:jefecentraldecotizaciones2@comfamiliarhuila.com">jefecentraldecotizaciones2@comfamiliarhuila.com</A></I></SPAN></FONT>
</P>
<P CLASS="western" ALIGN=JUSTIFY STYLE="margin-bottom: 0in; line-height: 100%">
  <BR>
</P>
<P CLASS="western" STYLE="margin-bottom: 0in; line-height: 100%"><FONT FACE="Arial, serif"><SPAN LANG="es-ES"><I>Inquietudes
y/o modificaciones,</I></SPAN></FONT></P>
<P LANG="es-ES" CLASS="western" STYLE="margin-bottom: 0in; line-height: 100%">
  <BR>
</P>
<P CLASS="western" STYLE="margin-bottom: 0in; line-height: 100%"><FONT FACE="Arial, serif"><SPAN LANG="es-ES"><I><B>Asesor
Comercial: {{$quotation['assesor_quotation']['third_party']['name']}} Cel.: {{$quotation['assesor_quotation']['third_party']['phone_principal']['phone']}}</B></I></SPAN></FONT>
</P>
<P CLASS="western" ALIGN=JUSTIFY STYLE="margin-bottom: 0in; line-height: 100%">
  <FONT FACE="Arial, serif"><SPAN LANG="es-ES"><I><B>Coordinador
Comercial: Alexander Roa Cruz Cel.: 321 383 32 95.</B></I></SPAN></FONT></P>
<P LANG="es-ES" CLASS="western" STYLE="margin-bottom: 0in; line-height: 100%">
  <BR>
</P>
<P LANG="es-ES" CLASS="western" STYLE="margin-bottom: 0in; line-height: 100%">
  <BR>
</P>
<P CLASS="western" STYLE="margin-bottom: 0in; line-height: 100%"><FONT FACE="Arial, serif"><SPAN LANG="es-ES"><I>Elaboró:</I></SPAN></FONT>
</P>
<P LANG="es-ES" CLASS="western" STYLE="margin-bottom: 0in; line-height: 100%">
  <img src="{{config('app.url')}}{{$quotation['auxiliary_quotation']['signature_path']}}" alt=""
       style="width: 4cm;"/>
</P>
<P CLASS="western" STYLE="margin-bottom: 0in; line-height: 100%"><FONT FACE="Arial, serif"><SPAN
      LANG="es-ES"><I><B>{{$quotation['auxiliary_quotation']['third_party']['name']}}</B></I></SPAN></FONT>
</P>
<P CLASS="western" STYLE="margin-bottom: 0in; line-height: 100%"><FONT FACE="Arial, serif"><SPAN LANG="es-ES"><I>Central
de cotizaciones</I></SPAN></FONT></P>
<P CLASS="western" STYLE="margin-bottom: 0in; line-height: 100%"><FONT FACE="Arial, serif"><SPAN LANG="es-ES"><I>Comfamiliar
Huila.</I></SPAN></FONT></P>
