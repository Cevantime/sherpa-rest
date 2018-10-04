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

    public function crud($entityClass, $extends = null, $prefixRoute = '', $prefixPath = '')
    {
        $shortEntityName = ClassNameResolver::getShortClassName($entityClass);
        if (class_exists($controllerClass = 'App\\Controller\\' . $shortEntityName . 'Controller')) {
            $controller = new $controllerClass($entityClass);
        } else {
            $controller = new RestController($entityClass);
        }
        $snakeEntityName = Camelizer::snakify($shortEntityName);
        $this->makeCrud($controller, $extends, $prefixRoute . $snakeEntityName . '.', $prefixPath . '/' . $snakeEntityName);
    }

    public function crudFromController($controllerClass, $extends = null, $prefixRoute = '', $prefixPath = '')
    {
        $controller = new $controllerClass();
        $entity = Camelizer::snakify(ClassNameResolver::getShortClassName($controller->getEntityClass()));
        $this->makeCrud($controller, $extends, $prefixRoute . $entity . '.', $prefixPath . '/' . $entity);
    }

    public function removeRoute($name)
    {
        unset($this->routes[$name]);
    }

    private function makeCrud($controller, $extends, $prefixRoute, $prefixPath)
    {
        if (!($controller instanceof RestController)) {
            throw InvalidControllerException(get_class($controller));
        }

        $this->attach($prefixRoute, $prefixPath, function (Map $map) use ($controller, $extends) {
            $map->get('list', '', [$controller, 'getList'])->setEntityClass($controller->getEntityClass());
            $map->get('item', '/{id}', [$controller, 'getItem'])->setEntityClass($controller->getEntityClass())
                ->tokens(['id' => '\d+']);
            $map->post('create', '', [$controller, 'createItem'])->setEntityClass($controller->getEntityClass());
            $map->delete('delete', '/{id}', [$controller, 'deleteItem'])->setEntityClass($controller->getEntityClass())
                ->tokens(['id' => '\d+']);
            $map->put('update', '/{id}', [$controller, 'updateItem'])->setEntityClass($controller->getEntityClass())
                ->tokens(['id' => '\d+']);

            if (is_callable($extends)) {
                $extends($map, $controller);
            }
        });
    }

}
