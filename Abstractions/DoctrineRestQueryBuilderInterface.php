<?php

namespace Sherpa\Rest\Abstractions;

use Sherpa\Rest\Utils\Bag;

/**
 *
 * @author cevantime
 */
interface DoctrineRestQueryBuilderInterface
{
    public function createQueryBuilderFromArray($alias, Bag $criteria);
    public function createQueryBuilderFromIdentifier($alias, $identifier, Bag $criteria);
}
