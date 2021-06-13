<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Product;
use AppBundle\Repository\ProductRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Exception;

/**
 * Class ProductManager
 *
 * @package AppBundle\Service
 * @property ProductRepository $repository
 */
class ProductManager extends AbstractManager
{

    /**
     * @param $product
     * @return Product
     * @throws NonUniqueResultException
     */
    public function product($product)
    {
        return $this->repository->findProductById($product);
    }


    /**
     * @param Product $product
     * @return bool
     */
    public function create(Product $product){
        try {
            $this->em->persist($product);
            $this->em->flush();
        } catch (ORMException $e) {
            return $e->getMessage();
        }

        return true;
    }

    /**
     * @param Product $product
     * @return bool
     */
    public function update(Product $product){

        try {
            $this->em->persist($product);
            $this->em->flush();
        } catch (ORMException $e){
            return $e->getMessage();
        }

        return true;
    }

    /**
     * @param Product $product
     * @return bool
     */
    public function delete(Product $product){
        try {
            $this->em->remove($product);
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