<?php
namespace JDE\ClassJDE;
class Response {
    private $result;
	private $error;
	private $tercero;
	
	function __construct($result=null,$error=null,$tercero=null){
		$this->result=$result;
		$this->error=$error;
		$this->tercero=$tercero;
	}
	
	function getResult() {
        return $this->result;
    }
    
	function getError() {
        return $this->error;
    }

	function getTercero() {
        return $this->tercero;
    }

	function setResult($result) {
        $this->result = $result;
    }
	
    function setError($error) {
        $this->error = $error;
    }

	function setTercero($tercero) {
        $this->tercero = $tercero;
    }
}
