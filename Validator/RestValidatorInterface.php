<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sherpa\Rest\Validator;

use Psr\Http\Message\ServerRequestInterface;

/**
 *
 * @author cevantime
 */
interface RestValidatorInterface
{
    /**
     * @return boolean
     */
    public function validate(array &$data, array &$errors);
    
}
