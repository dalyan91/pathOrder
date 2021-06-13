<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Exception;

class CategoryController extends BaseController
{

    /**
     * @Rest\Post("/category", name="newCategory")
     * @Rest\Post("/public/category", name="public_newCategory")
     * @Rest\View(serializerGroups={"category"})
     * @param Request $request
     * @return Category
     * @throws Exception
     */
    public function newCategory(Request $request)
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->submit($request->request->all());
        $form->handleRequest($request);

        if($form->isValid() && $form->isSubmitted()) {

            $categoryManager = $this->get('app.category_manager');

            $result = $categoryManager->create($category);
            if($result){
                return $category;
            }else{
                return $this->createApiException('An error occurred.');
            }
        }
        else{
            return $this->createApiException('An error occurred.');
        }

    }

    /**
     * @Rest\Get("/category/{category}",name="findCategoryById")
     * @Rest\Get("/public/category/{category}",name="public_findCategoryById")
     * @Rest\View(serializerGroups={"category"})
     * @param Request $request
     * @param Category $category
     * @return Category
     * @throws Exception
     */
    public function findCategoryById(Request $request,Category $category){
        return $category;
    }

    /**
     * @Rest\Get("/category",name="fetchCategorys")
     * @Rest\Get("/public/category",name="public_fetchCategorys")
     * @Rest\View(serializerGroups={"category"})
     * @param Request $request
     * @return Category
     */
    public function fetchCategorys(Request $request){
        $categoryManager = $this->get('app.category_manager');
        $orderBy = $this->calculateOrder($request, ['id']);
        return $categoryManager->fetchCategorys($orderBy, $request->get('limit'),$request->get('offset'));
    }

    /**
     * @Rest\Delete("/category/{category}",name="deleteCategory")
     * @Rest\Delete("/public/category/{category}",name="public_deleteCategory")
     * @Rest\View(serializerGroups={"category"})
     * @param Category $category
     * @param Request $request
     * @return Category
     * @throws Exception
     */
    public function deleteCategory(Category $category, Request $request){
        $categoryManager = $this->get('app.category_manager');
        $result = $categoryManager->delete($category);
        if($result) {
            return $category;
        }
        else{
            return $result;
        }
    }

    /**
     * @Rest\Put("/category/{category}",name="updateCategory")
     * @Rest\Put("/public/category/{category}",name="public_updateCategory")
     * @Rest\View(serializerGroups={"category"})
     * @param Category $category
     * @param Request $request
     * @return Category
     * @throws Exception
     */
    public function updateCategory(Category $category, Request $request){
        $categoryManager = $this->get('app.category_manager');

        $form = $this->createForm(CategoryType::class,$category);
        $form->submit($request->request->all());

        if($form->isValid() && $form->isSubmitted()) {
            $result = $categoryManager->update($category);
            if($result){
                return $category;
            }else{
                return $this->createApiException('An error occurred.');
            }
        }
        else{
            return $this->createApiException('An error occurred.');
        }
    }
}