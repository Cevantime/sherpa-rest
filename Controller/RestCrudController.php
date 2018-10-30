<?php

namespace Sherpa\Rest\Controller;

use Doctrine\ORM\EntityManagerInterface;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use Middlewares\HttpErrorException;
use Psr\Http\Message\ServerRequestInterface;
use Sherpa\Rest\Adapter\RestAdapter;
use Sherpa\Rest\Adapter\RestAdapterInterface;
use Sherpa\Rest\Builder\RestBuilderInterface;
use Sherpa\Rest\EntityClassAwareTrait;
use Sherpa\Rest\Exception\TransformerNotFoundException;
use Sherpa\Rest\Formatter\RestFormatterInterface;
use Sherpa\Rest\Service\RestFormatter;
use Sherpa\Rest\Utils\ClassNameResolver;
use Sherpa\Rest\Validator\RestValidatorInterface;
use Sherpa\Rest\Validator\InputBag;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Description of RestController
 *
 * @author cevantime
 */
class RestCrudController implements RestCrudControllerInterface
{
    use EntityClassAwareTrait;

    /**
     * @var RestAdapterInterface
     */
    protected $adapter;

    /**
     * @var RestFormatterInterface
     */
    protected $formatter;

    /**
     * @var RestValidatorInterface
     */
    protected $validator;

    /**
     * @var RestBuilderInterface
     */
    protected $builder;

    /**
     * RestController constructor.
     * @param RestAdapterInterface $adapter
     * @param RestFormatterInterface $formatter
     * @param RestValidatorInterface $validator
     * @param RestBuilderInterface $builder
     */
    public function __construct(
        RestAdapterInterface $adapter,
        RestFormatterInterface $formatter,
        RestValidatorInterface $validator,
        RestBuilderInterface $builder
    ) {
        $this->adapter = $adapter;
        $this->formatter = $formatter;
        $this->validator = $validator;
        $this->builder = $builder;
    }

    public function getList(ServerRequestInterface $request)
    {
        return $this->createListResponse($request);
    }

    public function getItem(ServerRequestInterface $request, $id)
    {
        $this->setEntityClasses([$this->adapter, $this->formatter]);
        $entity = $this->adapter->getEntityFromParams($id, $this->extractInputFromRequest($request)->getSentData());
        if (!$entity) {
            $this->createError();
        }
        return $this->createItemResponse($entity);
    }

    public function createItem(ServerRequestInterface $request)
    {
        $this->setEntityClasses([$this->adapter, $this->builder, $this->formatter, $this->validator]);
        $input = $this->extractInputFromRequest($request);

        $valid = $this->validator->validate($input);

        if ($valid) {
            $object = $this->builder->build($input);
            $this->adapter->persistEntity($object);
            return $this->createItemResponse($object);
        }

        $this->createError($input->getErrors()->toArray()[0], 400);

    }

    public function updateItem(ServerRequestInterface $request, $id)
    {
        $this->setEntityClasses([$this->adapter, $this->builder, $this->formatter, $this->validator]);
        $input = $this->extractInputFromRequest($request);

        $valid = $this->validator->validate($input);

        if ($valid) {
            $object = $this->adapter->getEntityFromParams($id, $input->getSentData());
            $this->builder->update($input, $object);
            $this->adapter->persistEntity($object);
            return $this->createItemResponse($object);
        }

        $this->createError($input->getErrors()->toArray()[0], 400);

    }

    public function deleteItem(ServerRequestInterface $request, $id)
    {
        $this->setEntityClasses([$this->adapter]);
        $entity = $this->adapter->getEntityFromParams($id, $this->extractInputFromRequest($request)->getSentData());
        if( ! $entity) {
            $this->createError('Entity not found', 404);
        }
        $this->adapter->removeEntity($entity);
        return new JsonResponse([], 204);
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }

    protected function createItemResponse($entity)
    {
        return new JsonResponse($this->formatter->itemize($entity));
    }

    protected function createListResponse(ServerRequestInterface $request)
    {
        $this->setEntityClasses([$this->adapter, $this->formatter]);
        $queryParams = $request->getQueryParams();
        $page = $queryParams['page'] ?? 1;
        $data = $this->formatter->paginate($this->adapter->getPageAdapterFromParams($this->extractInputFromRequest($request)->getSentData()), $page);
        return new JsonResponse($data);
    }

    protected function createError($msg = 'Not Found', int $code = 404)
    {
        throw new HttpErrorException($msg, $code);
    }

    protected function extractInputFromRequest(ServerRequestInterface $request)
    {
        $method = strtolower($request->getMethod());
        if (in_array($method, ['post'])) {
            $data = $request->getParsedBody();
        } else if(in_array($method, ['put', 'patch'])) {
            parse_str(file_get_contents('php://input'), $data);
        } else {
            $data = $request->getQueryParams();
        }

        return new InputBag($data, $request->getUploadedFiles());
    }

    protected function setEntityClasses($objects)
    {
        foreach ($objects as $object) {
            if (method_exists($object, 'setEntityClass')) {
                $object->setEntityClass($this->getEntityClass());
            }
        }
    }

}
