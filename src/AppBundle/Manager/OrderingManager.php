<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Ordering;
use AppBundle\Repository\OrderingRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Exception;

/**
 * Class OrderingManager
 *
 * @package AppBundle\Service
 * @property OrderingRepository $repository
 */
class OrderingManager extends AbstractManager
{

    /**
     * @param $ordering
     * @return Ordering
     * @throws NonUniqueResultException
     */
    public function ordering($ordering)
    {
        return $this->repository->findOrderingById($ordering);
    }
    /**
     * @param $ordering
     * @return Ordering
     * @throws NonUniqueResultException
     */
    public function orderingDiscounts($ordering)
    {
        return $this->repository->orderingDiscounts($ordering);
    }


    /**
     * @param Ordering $ordering
     * @return bool
     */
    public function create(Ordering $ordering){
        try {
            $this->em->persist($ordering);
            $this->em->flush();
        } catch (ORMException $e) {
            return $e->getMessage();
        }

        return true;
    }

    /**
     * @param Ordering $ordering
     * @return bool
     */
    public function update(Ordering $ordering){

        try {
            $this->em->persist($ordering);
            $this->em->flush();
        } catch (ORMException $e){
            return $e->getMessage();
        }

        return true;
    }

    /**
     * @param Ordering $ordering
     * @return bool
     */
    public function delete(Ordering $ordering){
        try {
            $this->em->remove($ordering);
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
    public function fetchOrderings($orderBy=null, $limit=null, $offset=0){

        $orderings = [];
        $count = $this->repository->countOrdering();
        if($count>0)
        {
            $orderings = $this->repository->findOrderings($orderBy,$limit,$offset);
        }
        return [
            'count' => $count,
            'items' => $orderings
        ];
    }

    /**
     * Entity name is required
     *
     * @return string
     */
    public function getEntityName()
    {
        return 'AppBundle:Ordering';
    }
}