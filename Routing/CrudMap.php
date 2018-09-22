<?php

namespace Sherpa\Rest\Routing;

use Aura\Router\Map;
use Sherpa\Rest\Controller\RestController;
use Sherpa\Rest\Exception\InvalidControllerException;
use Sherpa\Rest\Utils\Camelizer;
use Sherpa\Rest\Utils\ClassNameResolver;

/**
 * Description of CrudMap
 *
 * @author cevantime
 */
class CrudMap extends Map
{

    public function crud($entityClass, $extends = null)
    {
        $shortEntityName = ClassNameResolver::getShortClassName($entityClass);
        if (class_exists($controllerClass = 'App\\Controller\\' . $shortEntityName . 'Controller')) {
            $controller = new $controllerClass($entityClass);
        } else {
            $controller = new RestController($entityClass);
        }
        $snakeEntityName = Camelizer::snakify($shortEntityName);
        $this->makeCrud($controller, $extends, $snakeEntityName . '.', '/' . $snakeEntityName);
    }

    public function crudFromController($controllerClass, $extends = null)
    {
        $controller = new $controllerClass();
        $entity = Camelizer::snakify(ClassNameResolver::getShortClassName($controller->getEntityClass()));
        $this->makeCrud($controller, $extends, $entity . '.', '/' . $entity);
    }

    private function makeCrud($controller, $extends, $prefixRoute, $prefixPath)
    {
        if (!($controller instanceof RestController)) {
            throw InvalidControllerException(get_class($controller));
        }

        $this->attach($prefixRoute, $prefixPath, function (Map $map) use ($controller, $extends) {
            $map->get('list', '', [$controller, 'getList']);
            $map->get('item', '/{id}', [$controller, 'getItem'])
                ->tokens(['id' => '\d+']);
            $map->post('create', '', [$controller, 'createItem']);
            $map->delete('delete', '/{id}', [$controller, 'deleteItem'])
                ->tokens(['id' => '\d+']);
            if (is_callable($extends)) {
                $extends($map, $controller);
            }
            $map->getRoute('toto')->accepts();
        });
    }

}
