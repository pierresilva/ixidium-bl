Hola <i>{{ $demo->receiver }}</i>,
<p>Esto es un email con propositos de prueba! Tambien, es la version HTML.</p>

<p><u>Valores de prueba:</u></p>

<div>
<p><b>Demo Uno:</b>&nbsp;{{ $demo->demo_one }}</p>
<p><b>Demo Dos:</b>&nbsp;{{ $demo->demo_two }}</p>
</div>

<p><u>Valores pasados por el metodo:</u></p>

<div>
<p><b>testVarOne:</b>&nbsp;{{ $testVarOne }}</p>
<p><b>testVarTwo:</b>&nbsp;{{ $testVarTwo }}</p>
</div>

Gracias,
<br/>
<i>{{ $demo->sender }}</i>
