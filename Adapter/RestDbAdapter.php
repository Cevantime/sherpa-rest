<?php

namespace Sherpa\Rest\Adapter;

use Sherpa\Rest\EntityClassAwareTrait;

/**
 *
 * @author cevantime
 */
abstract class RestDbAdapter implements RestDbAdapterInterface
{
    use EntityClassAwareTrait;
}
