<?php
/**
 * Created by PhpStorm.
 * User: cevantime
 * Date: 22/09/18
 * Time: 01:31
 */

namespace Sherpa\Rest\Builder;


use Psr\Http\Message\ServerRequestInterface;
use Sherpa\Rest\EntityClassAwareTrait;
use Sherpa\Rest\Utils\ClassNameResolver;

class RestBuilder implements RestBuilderInterface
{
    use EntityClassAwareTrait;

    private $builder;
    private $request;

    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    public function build(array $data)
    {
        if($this->getBuilder() instanceof RestBuilderInterface) {
            return $this->getBuilder()->build($data);
        } else {
            return $this->getDefaultBuilder()->build($data);
        }
    }

    public function update(array $data, $object)
    {
        if($this->getBuilder() instanceof RestBuilderInterface) {
            return $this->getBuilder()->update($data, $object);
        } else {
            return $this->getDefaultBuilder()->build($data, $object);
        }
    }

    public function getBuilder()
    {
        if($this->builder === null && ($this->builder = $this->request->getAttribute('_builder')) === null) {
            $classname = 'App\\Builder\\' . ClassNameResolver::getShortClassName($this->getEntityClass()) . 'Builder';
            if(class_exists($classname)) {
                $this->builder = new $classname();
            }
        }
        return $this->builder;
    }

    public function getDefaultBuilder()
    {
        $defaultBuilder = new DefaultRestBuilder();
        $defaultBuilder->setEntityClass($this->getEntityClass());
        return $defaultBuilder;
    }
}