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
    private $entityClass;

    public function __clone()
    {
        parent::__clone();
        $this->attributes([
            '_route' => $this
        ]);
    }

    /**
     * @return mixed
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * @param mixed $entityClass
     */
    public function setEntityClass($entityClass): self
    {
        $this->entityClass = $entityClass;
        return $this;
    }
}
