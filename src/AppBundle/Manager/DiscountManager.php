<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Discount;
use AppBundle\Entity\Ordering;
use AppBundle\Entity\OrderItem;
use AppBundle\Repository\DiscountRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Exception;

/**
 * Class DiscountManager
 *
 * @package AppBundle\Service
 * @property DiscountRepository $repository
 */
class DiscountManager extends AbstractManager
{

    /**
     * @param $discount
     * @return Discount
     * @throws NonUniqueResultException
     */
    public function discount($discount)
    {
        return $this->repository->findDiscountById($discount);
    }


    /**
     * @param Discount $discount
     * @return bool
     */
    public function create(Discount $discount){
        try {
            $this->em->persist($discount);
            $this->em->flush();
        } catch (ORMException $e) {
            return $e->getMessage();
        }

        return true;
    }

    /**
     * @param Discount $discount
     * @return bool
     */
    public function update(Discount $discount){

        try {
            $this->em->persist($discount);
            $this->em->flush();
        } catch (ORMException $e){
            return $e->getMessage();
        }

        return true;
    }

    /**
     * @param Discount $discount
     * @return bool
     */
    public function delete(Discount $discount){
        try {
            $this->em->remove($discount);
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
    public function fetchDiscounts($orderBy=null, $limit=null, $offset=0){

        $discounts = [];
        $count = $this->repository->countDiscount();
        if($count>0)
        {
            $discounts = $this->repository->findDiscounts($orderBy,$limit,$offset);
        }
        return [
            'count' => $count,
            'items' => $discounts
        ];
    }

    /**
     * @param $categoryId
     * @param $quantity
     * @return Discount|null
     * @throws NonUniqueResultException
     */
    public function caregoryUnit($categoryId, $quantity) {
        return $this->repository->caregoryUnit($categoryId, $quantity);
    }

    /**
     * @param Ordering $order
     * @return Discount
     */
    public function totalPrice(Ordering $order) {
        return $this->repository->totalPrice($order);
    }

    /**
     * Entity name is required
     *
     * @return string
     */
    public function getEntityName()
    {
        return 'AppBundle:Discount';
    }
}