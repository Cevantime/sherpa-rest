<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sherpa\Rest\Adapter;

use Sherpa\Rest\Utils\Bag;
use Pagerfanta\Adapter\AdapterInterface;

/**
 *
 * @author cevantime
 */
interface RestAdapterInterface
{
    /**
     * @return AdapterInterface
     */
    public function getPageAdapterFromParams(Bag $params);
    
    public function getEntityFromParams($id, Bag $params);
    
    public function persistEntity($entity);
    
    public function removeEntity($entity);
}
