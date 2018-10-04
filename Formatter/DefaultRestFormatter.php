<?php

namespace Sherpa\Rest\Formatter;

use Aura\Router\Generator;
use League\Fractal\Manager;
use League\Fractal\Pagination\PagerfantaPaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
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
class DefaultRestFormatter implements RestFormatterInterface
{

    private $request;
    private $routeGenerator;
    private $routePaginationFactory;
    private $transformer;
    private $entityClass;

    public function __construct(ServerRequestInterface $request, Generator $routeGenerator, TransformerAbstract $transformer)
    {
        $this->request = $request;
        $this->routeGenerator = $routeGenerator;
        $this->transformer = $transformer;
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
        $resource = new Collection($pager->getCurrentPageResults(), $this->transformer);
        $resource->setPaginator($paginator);
        $manager = $this->createManager();
        return $manager->createData($resource)->toArray();
    }

    public function createManager()
    {
        $manager = new Manager();
        $includes = $this->request->getQueryParams()['include'] ?? '';
        $excludes = $this->request->getQueryParams()['exclude'] ?? '';
        if($includes) $manager->parseIncludes($includes);
        if($excludes) $manager->parseExcludes($excludes);
        return $manager;
    }

    public function itemize($entity)
    {
        $item = new Item($entity, $this->transformer);
        return $this->createManager()->createData($item)->toArray();
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
                if($page > 1) {
                    $query['page'] = $page;
                } else {
                    unset($query['page']);
                }
                return $routeGenerator->generate($route->name, $attr) . '?' . ($query ? http_build_query($query) : '');
            };
        }
        return $this->routePaginationFactory;
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
