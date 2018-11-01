<?php



namespace Sherpa\Rest\Utils;

/**
 * Description of ClassnameResolver
 *
 * @author cevantime
 */
class ClassNameResolver
{
    public static function getShortClassName($className)
    {
        $classExp = explode('\\', $className);
        return end($classExp);
    }
}
