<?php

namespace pierresilva\Foundation\Http;

use pierresilva\Foundation\ServesFeaturesTrait;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

/**
 * Base controller.
 */
class Controller extends BaseController
{
    use ValidatesRequests;
    use ServesFeaturesTrait;
}
