<?php

namespace Sherpa\Rest\Adapter;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
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
class DefaultRestAdapter extends RestAdapter
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getEntityFromParams($id, array $params = [])
    {
        return $this->getItemQueryBuilder('t', $id, $params)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getPageAdapterFromParams(array $params = [])
    {
        $qb = $this->getListQueryBuilder('t', $params);
        return new DoctrineORMAdapter($qb);
    }

    /**
     *
     * @param EntityManagerInterface $em
     * @param ServerRequestInterface $request
     * @return QueryBuilder
     */
    protected function getListQueryBuilder($alias, array $params)
    {
        $repository = $this->em->getRepository($this->entityClass);

        if ($repository instanceof DoctrineRestQueryBuilderInterface) {
            $qb = $repository->createQueryBuilderFromArray($alias, $params);
        } else {
            $qb = $repository->createQueryBuilder($alias);
        }

        return $qb;
    }

    protected function getItemQueryBuilder($alias, $identifier, array $params = [])
    {
        $repository = $this->em->getRepository($this->entityClass);

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
