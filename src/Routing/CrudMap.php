<?php

namespace Sherpa\Rest\Routing;

use Aura\Router\Route;
use Sherpa\Rest\Controller\RestCrudController;
use Sherpa\Rest\Utils\Camelizer;
use Sherpa\Rest\Utils\ClassNameResolver;
use Sherpa\Routing\Map;

/**
 * Description of CrudMap
 *
 * @author cevantime
 */
class CrudMap extends Map
{
    protected $namespace;

    public function __construct(Route $protoRoute, string $namespace)
    {
        parent::__construct($protoRoute);
        $this->namespace = $namespace;
    }

    public function crud($entityClass, $extends = null, $prefixRoute = '', $prefixPath = '')
    {
        $shortEntityName = ClassNameResolver::getShortClassName($entityClass);
        if (!class_exists($controllerClass = $this->namespace . 'Controller\\' . $shortEntityName . 'Controller')) {
            $controllerClass = RestCrudController::class;
        }
        $snakeEntityName = Camelizer::snakify($shortEntityName);
        $this->makeCrud($controllerClass, $entityClass, $extends, $prefixRoute . $snakeEntityName . '.', $prefixPath . '/' . $snakeEntityName);
    }

    public function crudFromController($controllerClass, $entityClass, $extends = null, $prefixRoute = '', $prefixPath = '')
    {
        $this->makeCrud($controllerClass, $entityClass, $extends, $prefixRoute, $prefixPath);
    }

    public function removeRoute($name)
    {
        unset($this->routes[$this->protoRoute->getNamePrefix() . $name]);
    }

    private function makeCrud($controllerClass, $entityClass, $extends, $prefixRoute, $prefixPath)
    {

        $this->attach($prefixRoute, $prefixPath, function (Map $map) use ($controllerClass, $entityClass, $extends) {
            $map->getProtoRoute()->setEntityClass($entityClass);
            $map->getProtoRoute()->setControllerClass($controllerClass);
            if (is_callable($extends)) {
                $extends($map, $controllerClass);
            }
            $map->get('list', '', $controllerClass . '::getList');
            $map->get('item', '/{id}', $controllerClass . '::getItem');
            $map->post('create', '', $controllerClass . '::createItem');
            $map->delete('delete', '/{id}', $controllerClass . '::deleteItem');
            $map->put('update', '/{id}', $controllerClass . '::updateItem');
        });
    }

}
