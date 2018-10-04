<?php
/**
 * Created by PhpStorm.
 * User: cevantime
 * Date: 02/10/18
 * Time: 22:41
 */

namespace Sherpa\Rest\Middleware;


use Aura\Router\Route;
use DI\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sherpa\Rest\Utils\ClassNameResolver;
use Sherpa\Rest\Validator\DefaultRestValidator;
use Sherpa\Rest\Validator\RestValidatorInterface;

use function DI\create;
use function DI\get;

abstract class AddProcessor implements MiddlewareInterface
{
    /**
     * @var Container
     */
    private $container;

    private $type;

    public function __construct(Container $container, string $type)
    {
        $this->container = $container;
        $this->type = $type;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $classname = 'Sherpa\\Rest\\' . ucfirst($this->type) . '\\Rest' . ucfirst($this->type) . 'Interface';
        if ($this->container->has($classname)) {
            return $handler->handle($request);
        }
        $class = $this->getClass($request);
        $this->container->set($classname, $this->container->has($class) ? get($class) : create($class));
//        if ($this->type === 'Adapter') {
//            var_dump($classname);
//            var_dump($class);
//            die();
//        }
        return $handler->handle($request);
    }

    public function getClass(ServerRequestInterface $request)
    {
        $route = $request->getAttribute('_route');

        if ($validator = $request->getAttribute('_' . lcfirst($this->type))) {
            return $validator;
        } else if (class_exists($validator = 'App\\' . ucfirst($this->type) . '\\' . ClassNameResolver::getShortClassName($route->getEntityClass()) . ucfirst($this->type))) {
            return $validator;
        } else {
            return 'Sherpa\\Rest\\' . ucfirst($this->type) . '\\DefaultRest' . ucfirst($this->type);
        }
    }
}