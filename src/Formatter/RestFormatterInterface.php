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
    /**
     *
     * @param AdapterInterface $adapter
     * @param int $page
     * @return array
     */
    public function paginate(AdapterInterface $adapter, int $page = 1);

    /**
     * @param $entity
     * @return array
     */
    public function itemize($entity);
}
