<?php

namespace Sherpa\Rest\Controller;

use Doctrine\ORM\EntityManagerInterface;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use Middlewares\HttpErrorException;
use Psr\Http\Message\ServerRequestInterface;
use Sherpa\Rest\Adapter\RestDbAdapter;
use Sherpa\Rest\Adapter\RestDbAdapterInterface;
use Sherpa\Rest\Builder\RestBuilderInterface;
use Sherpa\Rest\Exception\TransformerNotFoundException;
use Sherpa\Rest\Formatter\RestFormatterInterface;
use Sherpa\Rest\Service\RestFormatter;
use Sherpa\Rest\Utils\ClassNameResolver;
use Sherpa\Rest\Validator\RestValidatorInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Description of RestController
 *
 * @author cevantime
 */
class RestController
{
    private $entityClass;

    public function __construct(string $entityName)
    {
        $this->entityClass = $entityName;
    }

    public function getList(ServerRequestInterface $request, RestDbAdapterInterface $adapter, RestFormatterInterface $pagination)
    {
        return $this->createListResponse($request, $adapter, $pagination);
    }

    public function getItem(RestDbAdapterInterface $adapter, RestFormatterInterface $formatter, ServerRequestInterface $request, $id)
    {
        $this->setEntityClasses([$adapter, $formatter]);
        $entity = $adapter->getEntityFromParams($id, $request->getQueryParams());
        if (!$entity) {
            $this->createError();
        }
        return $this->createItemResponse($entity, $formatter);
    }

    public function createItem(
        ServerRequestInterface $request,
        RestFormatterInterface $formatter,
        RestValidatorInterface $validator,
        RestBuilderInterface $builder,
        RestDbAdapterInterface $adapter
    )
    {
        $this->setEntityClasses([$adapter, $builder, $formatter, $validator]);
        $data = $this->extractDataFromRequest($request);

        $valid = $validator->validate($data);

        if ($valid) {
            $object = $builder->build($data);
            $adapter->persistEntity($object);
            return $this->createItemResponse($object, $formatter);
        }

        $this->createError('Invalid entity', 400);

    }

    public function deleteItem(RestDbAdapterInterface $adapter, ServerRequestInterface $request, $id)
    {
        $this->setEntityClasses([$adapter]);
        $entity = $adapter->getEntityFromParams($id, $request->getQueryParams());
        $adapter->removeEntity($entity);
        return new JsonResponse([], 204);
    }

    protected function getEntityClass()
    {
        return $this->entityClass;
    }

    protected function createItemResponse($entity, RestFormatterInterface $formatter)
    {
        return new JsonResponse($formatter->itemize($entity));
    }

    protected function createListResponse(ServerRequestInterface $request, RestDbAdapterInterface $adapter, RestFormatterInterface $formatter)
    {
        $this->setEntityClasses([$adapter, $formatter]);
        $queryParams = $request->getQueryParams();
        $page = $queryParams['page'] ?? 1;
        $data = $formatter->paginate($adapter->getPageAdapterFromParams($queryParams), $page);
        return new JsonResponse($data);
    }

    protected function createError(string $msg = 'Not Found', int $code = 404)
    {
        throw new HttpErrorException($msg, $code);
    }

    protected function extractDataFromRequest(ServerRequestInterface $request)
    {
        if (in_array(strtolower($request->getMethod()), ['post', 'put', 'patch'])) {
            $data = $request->getParsedBody();
        } else {
            $data = $request->getQueryParams();
        }
        return $data;
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
