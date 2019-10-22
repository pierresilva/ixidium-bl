<?php

namespace pierresilva\Modules\Exceptions;


class ModelNotFoundException extends \Exception {

    /**
     * ModuleNotFoundException constructor.
     */
    public function __construct( $model ) {
        parent::__construct('Model with class name [' . $model . '] not found');
    }
}