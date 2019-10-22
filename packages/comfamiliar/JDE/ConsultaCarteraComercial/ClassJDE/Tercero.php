<?php
namespace JDE\ClassJDE;
class Tercero {
    private $codigoTerceroJDE;
	private $nombreTercero;
	private $documentoTercero;
	
	function __construct($codigoTerceroJDE=null,$nombreTercero=null,$documentoTercero=null){
		$this->codigoTerceroJDE=$codigoTerceroJDE;
		$this->nombreTercero=$nombreTercero;
		$this->documentoTercero=$documentoTercero;
	}
	

    function getCodigoTerceroJDE() {
        return $this->codigoTerceroJDE;
    }

	function getNombreTercero() {
        return $this->nombreTercero;
    }

	function getDocumentoTercero() {
        return $this->documentoTercero;
    }

	function setCodigoTerceroJDE($codigoTerceroJDE) {
        $this->codigoTerceroJDE = $codigoTerceroJDE;
    }
	
	function setNombreTercero($nombreTercero) {
        $this->nombreTercero = $nombreTercero;
    }
	
	function setDocumentoTercero($documentoTercero) {
        $this->documentoTercero = $documentoTercero;
    }
}
