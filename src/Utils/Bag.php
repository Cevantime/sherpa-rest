<?php
/**
 * Created by PhpStorm.
 * User: cevantime
 * Date: 11/10/18
 * Time: 21:14
 */

namespace Sherpa\Rest\Utils;


/**
 * A handy interface for getting at include parameters.
 */
class Bag implements \ArrayAccess, \IteratorAggregate
{
    /**
     * @var array
     */
    protected $params = [];
    protected $index = 0;

    /**
     * Create a new parameter bag instance.
     *
     * @param array $params
     *
     * @return void
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * Get parameter values out of the bag.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return $this->__get($key);
    }

    /**
     * Get parameter values out of the bag via the property access magic method.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return isset($this->params[$key]) ? $this->params[$key] : null;
    }

    /**
     * Check if a param exists in the bag via an isset() check on the property.
     *
     * @param string $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->params[$key]);
    }

    /**
     * Disallow changing the value of params in the data bag via property access.
     *
     * @param string $key
     * @param mixed $value
     *
     * @throws \LogicException
     *
     * @return void
     */
    public function __set($key, $value)
    {
        $this->params[$key === null ? $this->index++ : $key] = $value;
    }

    /**
     * Disallow unsetting params in the data bag via property access.
     *
     * @param string $key
     *
     * @throws \LogicException
     *
     * @return void
     */
    public function __unset($key)
    {
        unset($this->params[$key]);
    }

    /**
     * Check if a param exists in the bag via an isset() and array access.
     *
     * @param string $key
     *
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->__isset($key);
    }

    /**
     * Get parameter values out of the bag via array access.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->__get($key);
    }

    /**
     * Disallow changing the value of params in the data bag via array access.
     *
     * @param string $key
     * @param mixed $value
     *
     * @throws \LogicException
     *
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->__set($key, $value);
    }

    /**
     * Disallow unsetting params in the data bag via array access.
     *
     * @param string $key
     *
     * @throws \LogicException
     *
     * @return void
     */
    public function offsetUnset($key)
    {
        $this->__unset($key);
    }

    /**
     * IteratorAggregate for iterating over the object like an array.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->params);
    }

    public function toArray()
    {
        return $this->params;
    }
}