<?php

namespace Sherpa\Rest\Adapter;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Psr\Http\Message\ServerRequestInterface;
use Sherpa\Rest\Abstractions\QueryBuilderFromArrayInterface;
use Sherpa\Rest\Formatter\RestFormatterInterface;
use Sherpa\Rest\Service\RestFormatter;

/**
 * Description of DoctrineAdapter
 *
 * @author cevantime
 */
class DoctrineAdapter extends RestDbAdapter
{
    
    private $em;
    private $paginator;
    
    public function __construct(EntityManagerInterface $em, RestFormatterInterface $paginator)
    {
        $this->em = $em;
        $this->paginator = $paginator;
    }
    
    public function getEntityFromParams($id, array $params = [])
    {
        return $this->getDefaultQueryBuilder('t', $params)
            ->andWhere('t.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getPageAdapterFromParams(array $params = [])
    {
        $qb = $this->getDefaultQueryBuilder('t', $params);
        return new DoctrineORMAdapter($qb);
    }

    /**
     * 
     * @param EntityManagerInterface $em
     * @param ServerRequestInterface $request
     * @return QueryBuilder
     */
    protected function getDefaultQueryBuilder($alias, array $params)
    {
        $repository = $this->em->getRepository($this->entityClass);
        
        if($repository instanceof QueryBuilderFromArrayInterface) {
            $qb = $repository->createQueryBuilderFromArray($alias, $params);
        } else {
            $qb = $repository->createQueryBuilder($alias);
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
