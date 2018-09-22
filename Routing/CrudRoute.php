<?php

namespace Sherpa\Rest\Routing;

use Aura\Router\Route;

/**
 * Description of CrudRoute
 *
 * @author cevantime
 */
class CrudRoute extends Route
{

    public function __clone()
    {
        parent::__clone();
        $this->attributes([
            '_route' => $this
        ]);
    }

}
