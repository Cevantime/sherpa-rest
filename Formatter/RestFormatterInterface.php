<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sherpa\Rest\Formatter;

use Pagerfanta\Adapter\AdapterInterface;

/**
 *
 * @author cevantime
 */
interface RestFormatterInterface
{
    public function paginate(AdapterInterface $adapter, int $page = 1);

    public function itemize($entity);
}
