<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Category;
use AppBundle\Repository\CategoryRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Exception;

/**
 * Class CategoryManager
 *
 * @package AppBundle\Service
 * @property CategoryRepository $repository
 */
class CategoryManager extends AbstractManager
{

    /**
     * @param $category
     * @return Category
     * @throws NonUniqueResultException
     */
    public function category($category)
    {
        return $this->repository->findCategoryById($category);
    }


    /**
     * @param Category $category
     * @return bool
     */
    public function create(Category $category){
        try {
            $this->em->persist($category);
            $this->em->flush();
        } catch (ORMException $e) {
            return $e->getMessage();
        }

        return true;
    }

    /**
     * @param Category $category
     * @return bool
     */
    public function update(Category $category){

        try {
            $this->em->persist($category);
            $this->em->flush();
        } catch (ORMException $e){
            return $e->getMessage();
        }

        return true;
    }

    /**
     * @param Category $category
     * @return bool
     */
    public function delete(Category $category){
        try {
            $this->em->remove($category);
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
    public function fetchCategorys($orderBy=null, $limit=null, $offset=0){

        $categorys = [];
        $count = $this->repository->countCategory();
        if($count>0)
        {
            $categorys = $this->repository->findCategorys($orderBy,$limit,$offset);
        }
        return [
            'count' => $count,
            'items' => $categorys
        ];
    }

    /**
     * Entity name is required
     *
     * @return string
     */
    public function getEntityName()
    {
        return 'AppBundle:Category';
    }
}