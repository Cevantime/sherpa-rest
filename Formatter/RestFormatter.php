<?php

namespace Sherpa\Rest\Formatter;

use Aura\Router\Generator;
use League\Fractal\Manager;
use League\Fractal\Pagination\PagerfantaPaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Pagerfanta\Adapter\AdapterInterface;
use Pagerfanta\Pagerfanta;
use Psr\Http\Message\ServerRequestInterface;
use Sherpa\Rest\Exception\TransformerNotFoundException;
use Sherpa\Rest\Utils\ClassNameResolver;

/**
 * Description of Pagination
 *
 * @author cevantime
 */
class RestFormatter implements RestFormatterInterface
{

    private $request;
    private $routeGenerator;
    private $routePaginationFactory;
    private $transformer;
    private $entityClass;

    public function __construct(ServerRequestInterface $request, Generator $routeGenerator)
    {
        $this->request = $request;
        $this->routeGenerator = $routeGenerator;
    }

    /**
     * 
     * @param type $queryBuilder
     * @param type $transformer
     * @return Collection
     */
    public function paginate(AdapterInterface $adapter, int $page = 1)
    {
        $pager = (new Pagerfanta($adapter))->setCurrentPage((int) $page);
        $paginator = new PagerfantaPaginatorAdapter($pager, $this->getRoutePaginationFactory());
        $resource = new Collection($pager->getCurrentPageResults(), $this->getTransformer());
        $resource->setPaginator($paginator);
        $manager = new Manager();
        return $manager->createData($resource)->toArray();
    }

    public function itemize($entity)
    {
        $item = new Item($entity, $this->getTransformer());
        return (new Manager())->createData($item)->toArray();
    }

    private function getRoutePaginationFactory()
    {
        if (null === $this->routePaginationFactory) {
            $request = $this->request;
            $routeGenerator = $this->routeGenerator;
            $this->routePaginationFactory = function(int $page) use ($request, $routeGenerator) {
                $route = $request->getAttribute('_route');
                $attr = $route->attributes;
                unset($attr['_route']);
                unset($attr['_transformer']);
                $query = $request->getQueryParams();
                $query['page'] = $page;
                return $routeGenerator->generate($route->name, $attr) . ($page > 1 ? '?' . http_build_query($query) : '');
            };
        }
        return $this->routePaginationFactory;
    }

    protected function getTransformer()
    {
        if (null === $this->transformer && null === ($this->transformer = $this->request->getAttribute('_transformer'))) {
            $shortEntityName = ClassNameResolver::getShortClassName($this->entityClass);
            $transformerClassName = 'App\\Transformer\\' . $shortEntityName . 'Transformer';
            if (!class_exists($transformerClassName)) {
                throw new TransformerNotFoundException($shortEntityName);
            }
            $this->transformer = new $transformerClassName();
        }

        return $this->transformer;
    }
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
    }

}
