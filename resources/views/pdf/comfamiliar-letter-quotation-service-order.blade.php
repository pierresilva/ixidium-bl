<!doctype html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <style>
        /**
            Set the margins of the page to 0, so the footer and the header
            can be of the full height and width !
         **/
        @page {
            margin: 0cm 0cm;
        }

        p {
            text-align: justify;
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
<!--<header>
    <table width="100%">
        <tr>
            <td valign="top"><img src="{{config('app.url')}}/images/letter/header-letter.jpg" alt=""
                                  style="width: 4cm;"/>
            </td>
        </tr>
    </table>
</header>-->

<footer>
    <table width="100%">
        <tr>
            <td style="font-weight: bold; font-size: 12px;"><em>SR-02-03</em></td>
        </tr>
    </table>
</footer>

<main style="margin-left: 1cm; margin-right: 1cm;">
    {!!$html!!}
</main>

</body>
</html>
