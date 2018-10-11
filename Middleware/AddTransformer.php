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
use League\Fractal\TransformerAbstract;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sherpa\Rest\Builder\RestBuilderInterface;
use Sherpa\Rest\Exception\TransformerNotFoundException;
use Sherpa\Rest\Utils\ClassNameResolver;
use Sherpa\Rest\Validator\DefaultRestValidator;
use Sherpa\Rest\Validator\RestValidatorInterface;

use function DI\create;
use function DI\get;

class AddTransformer implements MiddlewareInterface
{
    /**
     * @var Container
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if($this->container->has(TransformerAbstract::class)) {
           return $handler->handle($request);
        }

        $transformer = $this->getBuilderClass($request);
        $this->container->set(TransformerAbstract::class, $this->container->get($transformer));

        return $handler->handle($request);
    }

    public function getBuilderClass(ServerRequestInterface $request)
    {
        $route = $request->getAttribute('_route');

        if ($validator = $route->getTransformer()) {
            return $validator;
        } else if (class_exists($validator = $this->container->get('namespace').'Transformer\\' . ClassNameResolver::getShortClassName($route->getEntityClass()) . 'Transformer')) {
            return $validator;
        }

        var_dump($route);die();

        throw new TransformerNotFoundException($route->getEntityClass());
    }
}