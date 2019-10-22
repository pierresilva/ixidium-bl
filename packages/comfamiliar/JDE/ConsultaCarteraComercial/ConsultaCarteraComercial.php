<?php

namespace JDE;

use JDE\ClassJDE\ErrorHandler;
use JDE\ClassJDE\Response;
use JDE\ClassJDE\Tercero;

class ConsultaCarteraComercial
{
    private $wsdl = "http://www.comfamiliarenlinea.com/WSJDEdwardsDMZCapaExtPrueba/Services/WSJDEdwards.svc?wsdl";
    private $soap_serv;
    private $consultar;
    private $response;

    function __construct($consultar = array())
    {
        $this->soap_serv = new \SoapClient($this->wsdl);
        $this->response = new Response();
        $this->setconsultar($consultar);

    }

    public function setconsultar($consultar)
    {
        $this->consultar = $consultar;
    }

    public function procesar()
    {

        $consultarRequest = new \stdClass();
        $consultarRequest = $this->consultar;
        try {
            $x = $this->soap_serv->consultar($consultarRequest);
            if ($x->consultarResult->Error->codigo != "") {
                $error = new ErrorHandler();
                $error->setCodigo($x->consultarResult->Error->codigo);
                $error->setMensaje($x->consultarResult->Error->mensaje);
                $terceroAux = new Tercero($x->consultarResult->Tercero->codigoTerceroJDE, $x->consultarResult->Tercero->nombreTercero, $x->consultarResult->Tercero->DocumentoTercero);
                $this->response = new Response("ERROR", $error, $terceroAux);
            } else {
                $terceroAux = new Tercero($x->consultarResult->Tercero->codigoTerceroJDE, $x->consultarResult->Tercero->nombreTercero, $x->consultarResult->Tercero->DocumentoTercero->documentoTercero);
                $this->response = new Response("Ok", null, $terceroAux);
            }
        } catch (Exception $ex) {
            $error = new Error("Service-error", $ex->getMessage());
            $this->response = new Response("ERROR", $error);
        }
        return $this->response;
    }

    public function getResponse()
    {
        return $this->response;
    }


}
