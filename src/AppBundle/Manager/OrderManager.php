<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Customer;
use AppBundle\Entity\Order;
use AppBundle\Repository\OrderRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;

/**
 * Class OrderManager
 *
 * @package AppBundle\Service
 * @property OrderRepository $repository
 */
class OrderManager extends AbstractManager
{

    /**
     * @param $order
     * @return Order
     * @throws NonUniqueResultException
     */
    public function Order($order)
    {
        return $this->repository->findOrderById($order);
    }


    /**
     * @param Order $order
     * @return bool
     */
    public function create(Order $order){
        try {
            $this->em->persist($order);
            $this->em->flush();
        } catch (ORMException $e) {
            return $e->getMessage();
        }

        return true;
    }

    /**
     * @param Order $order
     * @return bool
     */
    public function update(Order $order){

        try {
            $this->em->persist($order);
            $this->em->flush();
        } catch (ORMException $e){
            return $e->getMessage();
        }

        return true;
    }

    /**
     * @param Customer $customer
     * @param null $orderBy
     * @param null $limit
     * @param int $offset
     * @return array
     * @throws NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function fetchOrders(Customer $customer, $orderBy=null, $limit=null, $offset=0){

        $orders = [];
        $count = $this->repository->countOrder($customer);
        if($count>0)
        {
            $orders = $this->repository->findOrders($customer, $orderBy,$limit,$offset);
        }
        return [
            'count' => $count,
            'items' => $orders
        ];
    }

    /**
     * Entity name is required
     *
     * @return string
     */
    public function getEntityName()
    {
        return 'AppBundle:Order';
    }
}