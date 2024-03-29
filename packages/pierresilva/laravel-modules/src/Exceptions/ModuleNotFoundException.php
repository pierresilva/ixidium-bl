<?php

namespace pierresilva\Modules\Exceptions;


class ModuleNotFoundException extends \Exception {

	/**
	 * ModuleNotFoundException constructor.
	 */
	public function __construct( $slug ) {
		parent::__construct('Module with slug name [' . $slug . '] not found');
	}
}