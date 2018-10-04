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

    public function getList(ServerRequestInterface $request, RestAdapterInterface $adapter, RestFormatterInterface $pagination)
    {
        return $this->createListResponse($request, $adapter, $pagination);
    }

    public function getItem(RestAdapterInterface $adapter, RestFormatterInterface $formatter, ServerRequestInterface $request, $id)
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
        RestAdapterInterface $adapter
    )
    {
        $this->setEntityClasses([$adapter, $builder, $formatter, $validator]);
        $data = $this->extractDataFromRequest($request);

        $errors = [];
        $valid = $validator->validate($data, $errors);

        if ($valid) {
            $object = $builder->build($data, $request->getHeaderLine('Accept-Language'));
            $adapter->persistEntity($object);
            return $this->createItemResponse($object, $formatter);
        }

        $this->createError(['errors' => $errors], 400);

    }

    public function updateItem(
        ServerRequestInterface $request,
        RestFormatterInterface $formatter,
        RestValidatorInterface $validator,
        RestBuilderInterface $builder,
        RestAdapterInterface $adapter,
        $id
    )
    {
        $this->setEntityClasses([$adapter, $builder, $formatter, $validator]);
        $data = $this->extractDataFromRequest($request);
        $errors = [];

        $valid = $validator->validate($data, $errors);

        if ($valid) {
            $object = $adapter->getEntityFromParams($id, $data);
            $builder->update($data, $object, $request->getHeaderLine('Accept-Language'));
            $adapter->persistEntity($object);
            return $this->createItemResponse($object, $formatter);
        }

        $this->createError(['errors' => $errors], 400);

    }

    public function deleteItem(RestAdapterInterface $adapter, ServerRequestInterface $request, $id)
    {
        $this->setEntityClasses([$adapter]);
        $entity = $adapter->getEntityFromParams($id, $request->getQueryParams());
        if( ! $entity) {
            $this->createError('Entity not found', 404);
        }
        $adapter->removeEntity($entity);
        return new JsonResponse([], 204);
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }

    protected function createItemResponse($entity, RestFormatterInterface $formatter)
    {
        return new JsonResponse($formatter->itemize($entity));
    }

    protected function createListResponse(ServerRequestInterface $request, RestAdapterInterface $adapter, RestFormatterInterface $formatter)
    {
        $this->setEntityClasses([$adapter, $formatter]);
        $queryParams = $request->getQueryParams();
        $page = $queryParams['page'] ?? 1;
        $data = $formatter->paginate($adapter->getPageAdapterFromParams($queryParams), $page);
        return new JsonResponse($data);
    }

    protected function createError($msg = 'Not Found', int $code = 404)
    {
        throw new HttpErrorException($msg, $code);
    }

    protected function extractDataFromRequest(ServerRequestInterface $request)
    {
        $method = strtolower($request->getMethod());
        if (in_array($method, ['post'])) {
            $data = $request->getParsedBody();
        } else if(in_array($method, ['put', 'patch'])) {
            parse_str(file_get_contents('php://input'), $data);
        }  else {
            $data = $request->getQueryParams();
        }
        $data['_uploaded_files'] = $request->getUploadedFiles();
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
