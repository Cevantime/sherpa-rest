<?php
/**
 * Created by PhpStorm.
 * User: cevantime
 * Date: 02/10/18
 * Time: 23:59
 */

namespace Sherpa\Rest\Middleware;


use DI\Container;
use Psr\Container\ContainerInterface;

class AddAdapter extends AddProcessor
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container, 'Adapter');
    }
}