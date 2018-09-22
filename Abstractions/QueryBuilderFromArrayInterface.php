<?php

namespace Sherpa\Rest\Abstractions;

/**
 *
 * @author cevantime
 */
interface QueryBuilderFromArrayInterface
{
    public function createQueryBuilderFromArray($alias, array $criteria);
}
