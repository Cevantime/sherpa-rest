<?php

namespace Sherpa\Rest\Abstractions;

/**
 *
 * @author cevantime
 */
interface DoctrineRestQueryBuilderInterface
{
    public function createQueryBuilderFromArray($alias, array $criteria);
    public function createQueryBuilderFromIdentifier($alias, $identifier, array $criteria = []);
}
