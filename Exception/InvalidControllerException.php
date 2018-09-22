<?php


namespace Sherpa\Rest\Exception;

/**
 * Description of InvalidControllerException
 *
 * @author cevantime
 */
class InvalidControllerException extends \Exception
{
    public function __construct(string $controllerClass, \Throwable $previous = null)
    {
        parent::__construct('Invalid controller for crud : '.$controllerClass.' doesn\'t extend RestController' , 101, $previous);
    }
}
