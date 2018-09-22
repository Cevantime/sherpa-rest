<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sherpa\Rest;

/**
 *
 * @author cevantime
 */
trait EntityClassAwareTrait
{
    /** @var string */
    protected $entityClass;
    
    function getEntityClass()
    {
        return $this->entityClass;
    }

    function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
    }
}
