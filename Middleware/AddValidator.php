<?php
/**
 * Created by PhpStorm.
 * User: cevantime
 * Date: 02/10/18
 * Time: 23:59
 */

namespace Sherpa\Rest\Middleware;


use DI\Container;

class AddValidator extends AddProcessor
{
    public function __construct(Container $container)
    {
        parent::__construct($container, 'Validator');
    }
}