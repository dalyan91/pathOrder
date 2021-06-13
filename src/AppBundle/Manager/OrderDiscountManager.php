<?php

namespace AppBundle\Manager;

use AppBundle\Entity\OrderDiscount;
use AppBundle\Entity\Ordering;
use AppBundle\Repository\OrderDiscountRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Exception;

/**
 * Class OrderDiscountManager
 *
 * @package AppBundle\Service
 * @property OrderDiscountRepository $repository
 */
class OrderDiscountManager extends AbstractManager
{

    /**
     * @param $orderDiscount
     * @return OrderDiscount
     * @throws NonUniqueResultException
     */
    public function orderDiscount($orderDiscount)
    {
        return $this->repository->findOrderDiscountById($orderDiscount);
    }

    /**
     * @param Ordering $ordering
     * @return OrderDiscount[]
     * @throws NonUniqueResultException
     */
    public function orderDiscountByOrder(Ordering $ordering)
    {
        return $this->repository->orderDiscountByOrder($ordering);
    }


    /**
     * @param OrderDiscount $orderDiscount
     * @return bool
     */
    public function create(OrderDiscount $orderDiscount){
        try {
            $this->em->persist($orderDiscount);
            $this->em->flush();
        } catch (ORMException $e) {
            return $e->getMessage();
        }

        return true;
    }

    /**
     * @param OrderDiscount $orderDiscount
     * @return bool
     */
    public function update(OrderDiscount $orderDiscount){

        try {
            $this->em->persist($orderDiscount);
            $this->em->flush();
        } catch (ORMException $e){
            return $e->getMessage();
        }

        return true;
    }

    /**
     * @param OrderDiscount $orderDiscount
     * @return bool
     */
    public function delete(OrderDiscount $orderDiscount){
        try {
            $this->em->remove($orderDiscount);
            $this->em->flush();
            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }

    }

    /**
     * @param null $orderBy
     * @param null $limit
     * @param int $offset
     * @param boolean|null $status
     * @return array
     * @throws NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function fetchOrderDiscounts($orderBy=null, $limit=null, $offset=0){

        $orderDiscounts = [];
        $count = $this->repository->countOrderDiscount();
        if($count>0)
        {
            $orderDiscounts = $this->repository->findOrderDiscounts($orderBy,$limit,$offset);
        }
        return [
            'count' => $count,
            'items' => $orderDiscounts
        ];
    }

    /**
     * Entity name is required
     *
     * @return string
     */
    public function getEntityName()
    {
        return 'AppBundle:OrderDiscount';
    }
}