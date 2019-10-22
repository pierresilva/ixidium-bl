<?php
namespace JDE\ClassJDE;
class DocumentoTercero {
    
	private $companiaDocumento;
	private $nombreCompaniaDocumento;
	private $tipoDocumento;
	private $descripcionTipoDocumento;
	private $numeroDocumento;
	private $importePendiente;
	private $fechaFactura;
	private $fechaVencimiento;
	private $diasVencimiento;
	
function __construct($companiaDocumento=null,$nombreCompaniaDocumento=null,$tipoDocumento=null,$descripcionTipoDocumento=null,$numeroDocumento=null,$importePendiente=null,$fechaFactura=null,$fechaVencimiento=null,$diasVencimiento=null){
		$this->$companiaDocumento=$companiaDocumento;
		$this->$nombreCompaniaDocumento=$nombreCompaniaDocumento;
		$this->$tipoDocumento=$tipoDocumento;
		$this->$descripcionTipoDocumento=$descripcionTipoDocumento;
		$this->$numeroDocumento=$numeroDocumento;
		$this->$importePendiente=$importePendiente;
		$this->$fechaFactura=$fechaFactura;
		$this->$fechaVencimiento=$fechaVencimiento;
		$this->$diasVencimiento=$diasVencimiento;
	}
	
    function getCompaniaDocumento() {
        return $this->companiaDocumento;
    }

	function getNombreCompaniaDocumento() {
        return $this->nombreCompaniaDocumento;
    }

	function getTipoDocumento() {
        return $this->tipoDocumento;
    }

	function getDescripcionTipoDocumento() {
        return $this->descripcionTipoDocumento;
    }

	function getNumeroDocumento() {
        return $this->numeroDocumento;
    }

	function getImportePendiente() {
        return $this->importePendiente;
    }
	
	function getFechaFactura() {
        return $this->fechaFactura;
    }

	function getFechaVencimiento() {
        return $this->fechaVencimiento;
    }

	function getDiasVencimiento() {
        return $this->diasVencimiento;
    }
	
	function setCompaniaDocumento($companiaDocumento) {
        $this->companiaDocumento=$companiaDocumento;
    }

	function setNombreCompaniaDocumento($nombreCompaniaDocumento) {
        $this->nombreCompaniaDocumento=$nombreCompaniaDocumento;
    }

	function setTipoDocumento($tipoDocumento) {
        $this->tipoDocumento=$tipoDocumento;
    }

	function setDescripcionTipoDocumento($descripcionTipoDocumento) {
        $this->descripcionTipoDocumento=$descripcionTipoDocumento;
    }

	function setNumeroDocumento($numeroDocumento) {
        $this->numeroDocumento=$numeroDocumento;
    }

	function setImportePendiente($importePendiente) {
        $this->importePendiente=$importePendiente;
    }
	
	function setFechaFactura($fechaFactura) {
        $this->fechaFactura=$fechaFactura;
    }

	function setFechaVencimiento($fechaVencimiento) {
         $this->fechaVencimiento=$fechaVencimiento;
    }

	function setDiasVencimiento($diasVencimiento) {
        $this->diasVencimiento=$diasVencimiento;
    }
	
}
