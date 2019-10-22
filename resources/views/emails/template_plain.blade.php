{{ $params->subject }}

<?php echo "\r\n\r\n"; ?>

Hola {{ $params->name }},

<?php echo "\r\n"; ?>

@foreach ($params->intro_lines as $line)
    {{$line}}
@endforeach

<?php echo "\r\n"; ?>

Cordialmente, {{ config('app.name') }}

<?php echo "\r\n\r\n"; ?>

&copy; {{ date('Y') }} {{ config('app.name') }}
