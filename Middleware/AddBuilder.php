<?php
/**
 * Created by PhpStorm.
 * User: cevantime
 * Date: 03/10/18
 * Time: 00:00
 */

namespace Sherpa\Rest\Middleware;


use DI\Container;

class AddBuilder extends AddProcessor
{
    public function __construct(Container $container)
    {
        parent::__construct($container, 'Builder');
    }
}