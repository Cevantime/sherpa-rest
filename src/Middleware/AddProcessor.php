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
use Psr\Container\ContainerInterface;
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

    private $classPrefix;

    public function __construct(ContainerInterface $container, string $type, string $classPrefix = 'Default')
    {
        $this->container = $container;
        $this->type = $type;
        $this->classPrefix = $classPrefix;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $request->getAttribute('_route');
        $classname = 'Sherpa\\Rest\\' . ucfirst($this->type) . '\\Rest' . ucfirst($this->type) . 'Interface';

        if ( ! $route->getEntityClass() || $this->container->has($classname)) {
            return $handler->handle($request);
        }

        $class = $this->getClass($route);

        $this->container->set($classname, $this->container->get($class));

        return $handler->handle($request);
    }

    public function getClass($route)
    {
        if ($processor = $route->{'get' . ucfirst($this->type)}()) {
            return $processor;
        } else if (class_exists($processor = $this->container->get('project.namespace') . ucfirst($this->type) . '\\' . ClassNameResolver::getShortClassName($route->getEntityClass()) . ucfirst($this->type))) {
            return $processor;
        } else {
            return 'Sherpa\\Rest\\' . ucfirst($this->type) . '\\'.ucfirst($this->classPrefix).'Rest' . ucfirst($this->type);
        }
    }
}