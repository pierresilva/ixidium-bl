<?php
   ini_set('display_errors','1');
   error_reporting(E_ALL);
   include 'ConsultaCarteraComercial/ConsultaCarteraComercial.php';
   
   function Consulta($nit){
		
	    $a=new ParametrosConsulta($nit);
		$consulta=new ConsultaCarteraComercial($a);
		$response=$consulta->procesar();
		return $response;
	}
	
    $a=consulta('   891.100.445-6');
	var_dump($a);
?>