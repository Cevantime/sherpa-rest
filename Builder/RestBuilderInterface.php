<?php
/**
 * Created by PhpStorm.
 * User: cevantime
 * Date: 22/09/18
 * Time: 01:22
 */

namespace Sherpa\Rest\Builder;


interface RestBuilderInterface
{
    public function build(array $data);
    public function update(array $data, $object);
}