<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Ordering;
use AppBundle\Entity\OrderItem;
use AppBundle\Repository\OrderItemRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Exception;

/**
 * Class OrderItemManager
 *
 * @package AppBundle\Service
 * @property OrderItemRepository $repository
 */
class OrderItemManager extends AbstractManager
{

    /**
     * @param $orderItem
     * @return OrderItem
     * @throws NonUniqueResultException
     */
    public function orderItem($orderItem)
    {
        return $this->repository->findOrderItemById($orderItem);
    }

    /**
     * @param Ordering $ordering
     * @return OrderItem[]
     * @throws NonUniqueResultException
     */
    public function orderItemByOrder(Ordering $ordering)
    {
        return $this->repository->orderItemByOrder($ordering);
    }


    /**
     * @param OrderItem $orderItem
     * @return bool
     */
    public function create(OrderItem $orderItem){
        try {
            $this->em->persist($orderItem);
            $this->em->flush();
        } catch (ORMException $e) {
            return $e->getMessage();
        }

        return true;
    }

    /**
     * @param OrderItem $orderItem
     * @return bool
     */
    public function update(OrderItem $orderItem){

        try {
            $this->em->persist($orderItem);
            $this->em->flush();
        } catch (ORMException $e){
            return $e->getMessage();
        }

        return true;
    }

    /**
     * @param OrderItem $orderItem
     * @return bool
     */
    public function delete(OrderItem $orderItem){
        try {
            $this->em->remove($orderItem);
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
    public function fetchOrderItems($orderBy=null, $limit=null, $offset=0){

        $orderItems = [];
        $count = $this->repository->countOrderItem();
        if($count>0)
        {
            $orderItems = $this->repository->findOrderItems($orderBy,$limit,$offset);
        }
        return [
            'count' => $count,
            'items' => $orderItems
        ];
    }

    /**
     * Entity name is required
     *
     * @return string
     */
    public function getEntityName()
    {
        return 'AppBundle:OrderItem';
    }
}