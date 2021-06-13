<?php

namespace AppBundle\Repository;

use AppBundle\Entity\OrderDiscount;
use AppBundle\Entity\Ordering;
use AppBundle\Helper\Traits\RepositoryOrdeByTrait;
use AppBundle\Helper\Traits\RepositoryPaginationTrait;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

/**
 * OrderDiscountRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrderDiscountRepository  extends EntityRepository
{
    use RepositoryPaginationTrait;
    use RepositoryOrdeByTrait;

    /**
     * @param array|null $orderBy
     * @param null $limit
     * @param int $offset
     * @return mixed
     */
    public function findOrderDiscounts(array $orderBy = null, $limit = null, $offset = 0)
    {
        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('od')
            ->from('AppBundle:OrderDiscount', 'od')
        ;
        $this->initOrderBy($query, $orderBy);
        $this->initPagination($query, $limit, $offset);
        return $query->getQuery()->getResult();

    }

    /**
     * @param $id
     * @return OrderDiscount
     * @throws NonUniqueResultException
     */
    public function findOrderDiscountById($id)
    {
        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('od')
            ->from('AppBundle:OrderDiscount', 'od')
            ->where('od.id=:id')
            ->setParameter('id',$id)
        ;
        return $query->getQuery()->getOneOrNullResult();
    }

    /**
     * @param Ordering $ordering
     * @return OrderDiscount[]
     * @throws NonUniqueResultException
     */
    public function orderDiscountByOrder(Ordering $ordering)
    {
        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('od')
            ->from('AppBundle:OrderDiscount', 'od')
            ->join('od.order','o')
            ->where('o.id=:id')
            ->setParameter('id',$ordering->getId())
        ;
        return $query->getQuery()->getResult();
    }

    /**
     * @return int
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countOrderDiscount(){
        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('COUNT(od.id)')
            ->from('AppBundle:OrderDiscount', 'od')
        ;

        return $query->getQuery()->getSingleScalarResult();
    }
}
