<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Discount;
use AppBundle\Entity\Ordering;
use AppBundle\Helper\Traits\RepositoryOrdeByTrait;
use AppBundle\Helper\Traits\RepositoryPaginationTrait;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

/**
 * DiscountRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DiscountRepository  extends EntityRepository
{
    use RepositoryPaginationTrait;
    use RepositoryOrdeByTrait;

    /**
     * @param array|null $orderBy
     * @param null $limit
     * @param int $offset
     * @return mixed
     */
    public function findDiscounts(array $orderBy = null, $limit = null, $offset = 0)
    {
        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('d')
            ->from('AppBundle:Discount', 'd')
        ;
        $this->initOrderBy($query, $orderBy);
        $this->initPagination($query, $limit, $offset);
        return $query->getQuery()->getResult();

    }

    /**
     * @param $id
     * @return Discount
     * @throws NonUniqueResultException
     */
    public function findDiscountById($id)
    {
        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('d')
            ->from('AppBundle:Discount', 'd')
            ->where('d.id=:id')
            ->setParameter('id',$id)
        ;
        return $query->getQuery()->getOneOrNullResult();
    }

    /**
     * @return int
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countDiscount(){
        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('COUNT(d.id)')
            ->from('AppBundle:Discount', 'd')
        ;

        return $query->getQuery()->getSingleScalarResult();
    }

    /**
     * @param $categoryId
     * @param $quantity
     * @return Discount|null
     * @throws NonUniqueResultException
     */
    public function caregoryUnit($categoryId, $quantity){
        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('d')
            ->from('AppBundle:Discount', 'd')
            ->join('d.category','c')
            ->where('c.id=:categoryId')
            ->andWhere('d.operationUnit<=:quantity')
            ->andWhere('d.operationType=:type')
            ->setParameter('categoryId',$categoryId)
            ->setParameter('quantity',$quantity)
            ->setParameter('type',Discount::OPERATION_TYPE_UNIT)
            ->orderBy('d.operationUnit','DESC')
        ;
        return $query->getQuery()->getOneOrNullResult();
    }

    /**
     * @param $categoryId
     * @param $quantity
     * @return Discount|null
     * @throws NonUniqueResultException
     */
    public function totalPrice(Ordering $order){
        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('d')
            ->from('AppBundle:Discount', 'd')
            ->join('d.category','c')
            ->where('d.operationUnit<=:total')
            ->andWhere('d.operationType=:type')
            ->setParameter('total',$order->getTotal())
            ->setParameter('type',Discount::OPERATION_TYPE_TOTAL)
            ->orderBy('d.operationUnit','DESC')
        ;
        return $query->getQuery()->getOneOrNullResult();
    }
}
