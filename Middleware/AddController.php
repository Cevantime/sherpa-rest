<?php
/**
 * Created by PhpStorm.
 * User: cevantime
 * Date: 09/10/18
 * Time: 22:22
 */

namespace Sherpa\Rest\Middleware;


use DI\Container;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sherpa\Rest\Routing\CrudRoute;

class AddController implements MiddlewareInterface
{

    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $request->getAttribute('_route');

        if( ! ($route instanceof CrudRoute)) {
            return $handler->handle($request);
        }

        $controllerClass = $route->getControllerClass();
        $entityClass = $route->getEntityClass();
        $this->container->call($controllerClass . '::setEntityClass' , [$entityClass]);
        return $handler->handle($request);
    }
}