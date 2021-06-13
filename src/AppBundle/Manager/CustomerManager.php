<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Customer;
use AppBundle\Repository\CustomerRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Exception;

/**
 * Class CustomerManager
 *
 * @package AppBundle\Service
 * @property CustomerRepository $repository
 */
class CustomerManager extends AbstractManager
{

    /**
     * @param $customer
     * @return Customer
     * @throws NonUniqueResultException
     */
    public function customer($customer)
    {
        return $this->repository->findCustomerById($customer);
    }


    /**
     * @param Customer $customer
     * @return bool
     */
    public function create(Customer $customer){
        try {
            $this->em->persist($customer);
            $this->em->flush();
        } catch (ORMException $e) {
            return $e->getMessage();
        }

        return true;
    }

    /**
     * @param Customer $customer
     * @return bool
     */
    public function update(Customer $customer){

        try {
            $this->em->persist($customer);
            $this->em->flush();
        } catch (ORMException $e){
            return $e->getMessage();
        }

        return true;
    }

    /**
     * @param Customer $customer
     * @return bool
     */
    public function delete(Customer $customer){
        try {
            $this->em->remove($customer);
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
    public function fetchCustomers($orderBy=null, $limit=null, $offset=0){

        $customers = [];
        $count = $this->repository->countCustomer();
        if($count>0)
        {
            $customers = $this->repository->findCustomers($orderBy,$limit,$offset);
        }
        return [
            'count' => $count,
            'items' => $customers
        ];
    }

    /**
     * Entity name is required
     *
     * @return string
     */
    public function getEntityName()
    {
        return 'AppBundle:Customer';
    }
}