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

<main style="margin-left: 1cm; margin-right: 1cm;">
    {!!$html!!}
</main>

</body>
</html>
