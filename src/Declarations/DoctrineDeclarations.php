<?php
/**
 * Created by PhpStorm.
 * User: cevantime
 * Date: 29/10/18
 * Time: 21:16
 */

namespace Sherpa\Rest\Declarations;


use Sherpa\App\App;
use Sherpa\Declaration\DeclarationInterface;
use Sherpa\Middlewares\RequestHandler;
use Sherpa\Rest\Middleware\AddDoctrineAdapter;

class DoctrineDeclarations implements DeclarationInterface
{
    public function register(App $app)
    {
        $app->pipe(AddDoctrineAdapter::class);
    }
}
