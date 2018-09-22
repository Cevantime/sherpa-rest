<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sherpa\Rest\Adapter;

use Pagerfanta\Adapter\AdapterInterface;

/**
 *
 * @author cevantime
 */
interface RestDbAdapterInterface
{
    /**
     * @return AdapterInterface
     */
    public function getPageAdapterFromParams(array $params);
    
    public function getEntityFromParams($id, array $params);
    
    public function persistEntity($entity);
    
    public function removeEntity($entity);
}
