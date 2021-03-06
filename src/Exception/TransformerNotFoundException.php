<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sherpa\Rest\Exception;

/**
 * Description of TransformerNotFoundException
 *
 * @author cevantime
 */
class TransformerNotFoundException extends \Exception
{
    public function __construct(string $entityClass, \Throwable $previous = null)
    {
        parent::__construct('Transfomer not found for entity '.$entityClass, 100, $previous);
    }
}
