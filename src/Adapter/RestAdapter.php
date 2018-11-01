<?php

namespace Sherpa\Rest\Adapter;

use Sherpa\Rest\EntityClassAwareTrait;

/**
 *
 * @author cevantime
 */
abstract class RestAdapter implements RestAdapterInterface
{
    use EntityClassAwareTrait;
}
