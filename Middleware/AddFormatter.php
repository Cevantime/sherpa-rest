<?php
/**
 * Created by PhpStorm.
 * User: cevantime
 * Date: 03/10/18
 * Time: 00:19
 */

namespace Sherpa\Rest\Middleware;


use DI\Container;

class AddFormatter extends AddProcessor
{
    public function __construct(Container $container)
    {
        parent::__construct($container, 'Formatter');
    }
}