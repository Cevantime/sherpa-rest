<?php

namespace Sherpa\Rest;

use Aura\Router\Generator;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sherpa\App\App;
use Sherpa\Declaration\DeclarationInterface;
use Sherpa\Kernel\Kernel;
use Sherpa\Rest\Adapter\DefaultRestAdapter;
use Sherpa\Rest\Adapter\RestAdapterInterface;
use Sherpa\Rest\Builder\RestBuilderInterface;
use Sherpa\Rest\Builder\RestBuilder;
use Sherpa\Rest\Middleware\AddAdapter;
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
class Declaration implements DeclarationInterface
{
    
    public function register(App $app)
    {
        $routerContainer = $app->getRouter();
        
        $routerContainer->setMapFactory(function() use ($app) {
            return new CrudMap(new CrudRoute(), $app->get('namespace'));
        });
        $routerContainer->setRouteFactory(function(){
            return new CrudRoute();
        });
        
        $containerBuilder = $app->getContainerBuilder();
        
        $generator = $app->getRouter()->getGenerator();
        
        $containerBuilder->addDefinitions([
            Generator::class => function() use ($generator) {
                return $generator;
            }
        ]);

        $app->delayed(function(App $app){
            $container = $app->getContainer();
            $app->add(new AddValidator($container));
            $app->add(new AddBuilder($container));
            $app->add(new AddAdapter($container));
            $app->add(new AddTransformer($container));
            $app->add(new AddFormatter($container));
            $app->add(new AddController($container));
        });
    }

}
