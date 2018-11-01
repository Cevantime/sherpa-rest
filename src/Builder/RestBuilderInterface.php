<?php
/**
 * Created by PhpStorm.
 * User: cevantime
 * Date: 22/09/18
 * Time: 01:22
 */

namespace Sherpa\Rest\Builder;


use Sherpa\Rest\Validator\InputBag;

interface RestBuilderInterface
{
    public function build(InputBag $data);
    public function update(InputBag $data, $entity);
}