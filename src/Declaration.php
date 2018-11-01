<?php

namespace Sherpa\Rest;

use Aura\Router\Generator;
use DI\ContainerBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sherpa\App\App;
use Sherpa\Declaration\DeclarationInterface;
use Sherpa\Kernel\Kernel;
use Sherpa\Middlewares\RequestHandler;
use Sherpa\Rest\Adapter\DoctrineRestAdapter;
use Sherpa\Rest\Adapter\RestAdapterInterface;
use Sherpa\Rest\Builder\RestBuilderInterface;
use Sherpa\Rest\Builder\RestBuilder;
use Sherpa\Rest\Middleware\AddDoctrineAdapter;
use Sherpa\Rest\Middleware\AddBuilder;
use Sherpa\Rest\Middleware\AddController;
use Sherpa\Rest\Middleware\AddFormatter;
use Sherpa\Rest\Middleware\AddTransformer;
use Sherpa\Rest\Middleware\AddValidator;
use Sherpa\Rest\Routing\CrudMap;
use Sherpa\Rest\Routing\CrudRoute;
use Sherpa\Rest\Formatter\DefaultRestFormatter;
use Sherpa\Rest\Formatter\RestFormatterInterface;
use function DI\create;
use function DI\get;
use Sherpa\Rest\Validator\RestValidator;
use Sherpa\Rest\Validator\RestValidatorInterface;

/**
 * Description of Declaration
 *
 * @author cevantime
 */
class Declaration extends \Sherpa\Declaration\Declaration
{

    public function custom(App $app)
    {
        $routerContainer = $app->getRouter();

        $routerContainer->setMapFactory(function () use ($app) {
            return new CrudMap(new CrudRoute(), $app->get('project.namespace'));
        });
        $routerContainer->setRouteFactory(function () {
            return new CrudRoute();
        });

        $app->pipe(AddValidator::class, 0, RequestHandler::class);
        $app->pipe(AddBuilder::class, 0, RequestHandler::class);
        $app->pipe(AddTransformer::class, 0, RequestHandler::class);
        $app->pipe(AddFormatter::class, 0, RequestHandler::class);
        $app->pipe(AddController::class, 0, RequestHandler::class);
    }

    public function definitions(ContainerBuilder $builder)
    {
        $builder->addDefinitions([
            Generator::class => function (ContainerInterface $container) {
                return $container->get('router')->getGenerator();
            }
        ]);
    }

}
