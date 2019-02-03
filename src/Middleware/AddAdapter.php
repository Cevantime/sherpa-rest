<?php
/**
 * Created by PhpStorm.
 * User: cevantime
 * Date: 03/02/19
 * Time: 21:17
 */

namespace Sherpa\Rest\Middleware;


use Psr\Container\ContainerInterface;

class AddAdapter extends AddProcessor
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container, 'Adapter');
    }
}