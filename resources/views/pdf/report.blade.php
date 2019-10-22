<!doctype html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

  <style>
    @page {
      margin-top: 0.5cm;
      margin-bottom: 0.5cm;
    }

    P {
      margin-bottom: 0.5cm;
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

    /** Define now the real margins of every page in the PDF **/
    body {
      margin-top: 0cm;
      margin-left: 0cm;
      margin-right: 0cm;
      margin-bottom: 0cm;
    }

    /** Define the header rules **/
    header {
      position: fixed;
      top: -2cm;
      left: 2cm;
      right: 0cm;
    }

    /** Define the footer rules **/
    footer {
      position: fixed;
      bottom: -0.5cm;
      left: 1cm;
      right: 0cm;
      height: 0.5cm;
    }

    footer .pagenum:after {
      content: counter(page);
    }

    .page-break {
      page-break-after: always;
    }

    * {
      font-family: Verdana, Arial, sans-serif;
    }

    table {
      font-size: small;
    }

    tfoot tr td {
      font-weight: bold;
      font-size: small;
    }

    .gray {
      background-color: lightgray
    }

    tr.border_bottom td {
      border-bottom: 1pt solid black;
    }
  </style>

</head>
<body>
<h3>Reporte: {{$report_name}}</h3>
<footer>
  <table width="100%">
    <tr>
      <td style="font-weight: bold; font-size: 12px;"><em>{{ date('d/m/Y') }}</em></td>
    </tr>
  </table>
</footer>

<main style="margin-left: 1cm; margin-right: 1cm;">
  <table whith="100%" class="table">
    <thead>
    <tr>
      @foreach($cols as $col)
        <th>{{ $col->title }}</th>
      @endforeach
    </tr>
    </thead>
    @forelse ($data as $item)
      <tr>
        @foreach($item as $k => $v)
          <td>{{$v}}</td>
        @endforeach
      </tr>
    @empty
      <tr><td>No hay datos</td></tr>
    @endforelse
  </table>
  @if(count($totals) > 0)
  <table class="table">
    @foreach($totals as $total)
    <tr>
      <td align="right"><b>TOTAL {{ $total['title'] }}:&nbsp;&nbsp;</b></td>
      <td align="left">&nbsp;&nbsp;{{ number_format($total['total']) }}</td>
    </tr>
    @endforeach
  </table>
  @endif
</main>

</body>
</html>
