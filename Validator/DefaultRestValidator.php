<?php
/**
 * Created by PhpStorm.
 * User: cevantime
 * Date: 02/10/18
 * Time: 22:43
 */

namespace Sherpa\Rest\Validator;


class DefaultRestValidator implements RestValidatorInterface
{

    /**
     * @return boolean
     */
    public function validate(InputBag $data)
    {
        return true;
    }

}