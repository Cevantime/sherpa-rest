<?php
/**
 * Created by PhpStorm.
 * User: cevantime
 * Date: 22/09/18
 * Time: 01:23
 */

namespace Sherpa\Rest\Builder;


use Sherpa\Rest\EntityClassAwareTrait;
use Sherpa\Rest\Validator\InputBag;
use Symfony\Component\PropertyAccess\PropertyAccess;

class DefaultRestBuilder implements RestBuilderInterface
{
    use EntityClassAwareTrait;

    public function build(InputBag $data)
    {
        $classRef = new \ReflectionClass($this->getEntityClass());
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $entity = $classRef->newInstance();
        foreach ($data as $key => $value) {
            if($propertyAccessor->isWritable($entity, $key)) {
                $propertyAccessor->setValue($entity, $key, $value);
            }
        }
        return $entity;
    }

    public function update(InputBag $data, $entity)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        foreach ($data as $key => $value) {
            if($propertyAccessor->isWritable($entity, $key)) {
                $propertyAccessor->setValue($entity, $key, $value);
            }
        }
        return $entity;
    }
}