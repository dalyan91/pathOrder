<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Product;
use AppBundle\Repository\ProductRepository;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class ProductManager
 *
 * @package AppBundle\Service
 * @property ProductRepository $repository
 */
class ProductManager extends AbstractManager
{

    /**
     * @param null $orderBy
     * @param null $limit
     * @param int $offset
     * @return array
     * @throws NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function fetchProducts($orderBy=null, $limit=null, $offset=0){

        $products = [];
        $count = $this->repository->countProduct();
        if($count>0)
        {
            $products = $this->repository->findProducts($orderBy,$limit,$offset);
        }
        return [
            'count' => $count,
            'items' => $products
        ];
    }

    /**
     * Entity name is required
     *
     * @return string
     */
    public function getEntityName()
    {
        return 'AppBundle:Product';
    }
}