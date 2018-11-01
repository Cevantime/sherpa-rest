<?php

namespace Sherpa\Rest\Routing;


use Sherpa\Routing\Route;

/**
 * Description of CrudRoute
 *
 * @author cevantime
 */
class CrudRoute extends Route
{
    private $controllerClass;
    private $entityClass;
    private $adapter;
    private $builder;
    private $formatter;
    private $transformer;
    private $validator;

    /**
     * @return mixed
     */
    public function getControllerClass()
    {
        return $this->controllerClass;
    }

    /**
     * @param mixed $controllerClass
     */
    public function setControllerClass($controllerClass): self
    {
        $this->controllerClass = $controllerClass;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * @param mixed $entityClass
     */
    public function setEntityClass($entityClass): self
    {
        $this->entityClass = $entityClass;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param mixed $adapter
     * @return CrudRoute
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * @param mixed $builder
     * @return CrudRoute
     */
    public function setBuilder($builder)
    {
        $this->builder = $builder;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFormatter()
    {
        return $this->formatter;
    }

    /**
     * @param mixed $formatter
     * @return CrudRoute
     */
    public function setFormatter($formatter)
    {
        $this->formatter = $formatter;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTransformer()
    {
        return $this->transformer;
    }

    /**
     * @param mixed $transformer
     * @return CrudRoute
     */
    public function setTransformer($transformer)
    {
        $this->transformer = $transformer;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * @param mixed $validator
     * @return CrudRoute
     */
    public function setValidator($validator)
    {
        $this->validator = $validator;
        return $this;
    }
}
