<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace JDE\ClassJDE;
class consultar {
	
	private $numeroIdentificacionTercero;
    
	function __construct($numeroIdentificacionTercero=''){
        $this->setNumeroIdentificacionTercero($numeroIdentificacionTercero);        
    }
	
/**
 * 
 * @return String
 */ 
	function getNumeroIdentificacionTercero() {
        return $this->numeroIdentificacionTercero;
    }
	
/**
 * 
 * @param String $numeroIdentificacionTercero
 */
    function setNumeroIdentificacionTercero($numeroIdentificacionTercero) {
        $this->numeroIdentificacionTercero = $numeroIdentificacionTercero;
    }

}
