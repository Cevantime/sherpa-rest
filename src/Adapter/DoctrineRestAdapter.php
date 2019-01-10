<?php

namespace Sherpa\Rest\Adapter;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Repository\DefaultRepositoryFactory;
use Psr\Container\ContainerInterface;
use Sherpa\Rest\Utils\Bag;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Psr\Http\Message\ServerRequestInterface;
use Sherpa\Rest\Abstractions\DoctrineRestQueryBuilderInterface;
use Sherpa\Rest\Formatter\RestFormatterInterface;
use Sherpa\Rest\Service\RestFormatter;

/**
 * Description of DoctrineAdapter
 *
 * @author cevantime
 */
class DoctrineRestAdapter extends RestAdapter
{

    private $em;
    private $container;

    public function __construct(EntityManagerInterface $em, ContainerInterface $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function getEntityFromParams($id, Bag $params)
    {
        return $this->getItemQueryBuilder('t', $id, $params)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getPageAdapterFromParams(Bag $params)
    {
        $qb = $this->getListQueryBuilder('t', $params);
        return new DoctrineORMAdapter($qb);
    }

    protected function getRepository()
    {
        $metadata = $this->em->getClassMetadata($this->entityClass);
        if($metadata->customRepositoryClassName) {
            $repository = $this->container->get($metadata->customRepositoryClassName);
        } else {
            $repository = $this->em->getRepository($this->entityClass);
        }
        return $repository;
    }

    /**
     *
     * @param EntityManagerInterface $em
     * @param ServerRequestInterface $request
     * @return QueryBuilder
     */
    protected function getListQueryBuilder($alias, Bag $params)
    {
        $repository = $this->getRepository();

        if ($repository instanceof DoctrineRestQueryBuilderInterface) {
            $qb = $repository->createQueryBuilderFromArray($alias, $params);
        } else {
            $qb = $repository->createQueryBuilder($alias);
        }

        return $qb;
    }

    protected function getItemQueryBuilder($alias, $identifier, Bag $params)
    {
        $repository = $this->getRepository();

        if ($repository instanceof DoctrineRestQueryBuilderInterface) {
            $qb = $repository->createQueryBuilderFromIdentifier($alias, $identifier, $params);
        } else {
            $qb = $repository->createQueryBuilder($alias)
                ->andWhere($alias . '.id = :id')
                ->setParameter('id', $identifier);
        }

        return $qb;
    }

    public function persistEntity($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function removeEntity($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

}
