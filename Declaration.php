<?php

namespace Sherpa\Rest;

use Aura\Router\Generator;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sherpa\App\App;
use Sherpa\Declaration\DeclarationInterface;
use Sherpa\Rest\Adapter\DoctrineAdapter;
use Sherpa\Rest\Adapter\RestDbAdapterInterface;
use Sherpa\Rest\Builder\RestBuilderInterface;
use Sherpa\Rest\Builder\RestBuilder;
use Sherpa\Rest\Routing\CrudMap;
use Sherpa\Rest\Routing\CrudRoute;
use Sherpa\Rest\Formatter\RestFormatter;
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
            return new CrudMap(new CrudRoute());
        });
        $routerContainer->setRouteFactory(function(){
            return new CrudRoute();
        });
        
        $containerBuilder = $app->getContainerBuilder();
        
        $generator = $app->getRouter()->getGenerator();
        
        $containerBuilder->addDefinitions([
            Generator::class => function() use ($generator) {
                return $generator;
            },
            RestFormatterInterface::class => create(RestFormatter::class)->constructor(
                get(ServerRequestInterface::class), 
                get(Generator::class)
            ),
            RestDbAdapterInterface::class => create(DoctrineAdapter::class)->constructor(
                get(EntityManagerInterface::class),
                get(RestFormatterInterface::class)
            ),
            RestValidatorInterface::class => create(RestValidator::class)->constructor(
                get(ServerRequestInterface::class)
            ),
            RestBuilderInterface::class => create(RestBuilder::class)->constructor(
                get(ServerRequestInterface::class)
            )
        ]);
        
    }

}
