<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace JDE\ClassJDE;
class ErrorHandler {
    private $codigo;
    private $mensaje;
    function __construct($codigo="", $mensaje="") {
        $this->codigo = $codigo;
        $this->mensaje = $mensaje;
    }

    function getCodigo() {
        return $this->codigo;
    }

    function getMensaje() {
        return $this->mensaje;
    }
    function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    function setMensaje($mensaje) {
        $this->mensaje = $mensaje;
    }



}
