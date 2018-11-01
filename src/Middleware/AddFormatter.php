<?php
/**
 * Created by PhpStorm.
 * User: cevantime
 * Date: 03/10/18
 * Time: 00:19
 */

namespace Sherpa\Rest\Middleware;


use DI\Container;
use Psr\Container\ContainerInterface;

class AddFormatter extends AddProcessor
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container, 'Formatter');
    }
}