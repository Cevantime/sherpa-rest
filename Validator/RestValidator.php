<?php

namespace Sherpa\Rest\Validator;

use Sherpa\Rest\EntityClassAwareTrait;
use Psr\Http\Message\ServerRequestInterface;
use Sherpa\Rest\Utils\ClassNameResolver;

/**
 * Description of RestValidator
 *
 * @author cevantime
 */
class RestValidator implements RestValidatorInterface
{
    use EntityClassAwareTrait;

    private $validator;
    private $request;

    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    public function validate(ServerRequestInterface $request)
    {
        if($this->getValidator() instanceof RestValidatorInterface) {
            return $this->getValidator()->validate($request);
        }

        return true;
    }

    public function getValidator()
    {
        if($this->validator === null && ($this->validator = $this->request->getAttribute('_validator')) === null) {
            $classname = 'App\\Form\\' . ClassNameResolver::getShortClassName($this->getEntityClass()) . 'Validator';
            if(class_exists($classname)) {
                $this->validator = new $classname();
            }
        }
        return $this->validator;
    }
}
