<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Form\ProductType;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Exception;

class ProductController extends BaseController
{

    /**
     * @Rest\Post("/product", name="newProduct")
     * @Rest\Post("/public/product", name="public_newProduct")
     * @Rest\View(serializerGroups={"product"})
     * @param Request $request
     * @return Product
     * @throws Exception
     */
    public function newProduct(Request $request)
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
        $form->submit($request->request->all());
        $form->handleRequest($request);

        if($form->isValid() && $form->isSubmitted()) {

            $productManager = $this->get('app.product_manager');

            $result = $productManager->create($product);
            if($result){
                return $product;
            }else{
                return $this->createApiException('An error occurred.');
            }
        }
        else{
            return $this->createApiException('An error occurred.');
        }

    }

    /**
     * @Rest\Get("/product/{product}",name="findProductById")
     * @Rest\Get("/public/product/{product}",name="public_findProductById")
     * @Rest\View(serializerGroups={"product"})
     * @param Request $request
     * @param Product $product
     * @return Product
     * @throws Exception
     */
    public function findProductById(Request $request,Product $product){
        return $product;
    }

    /**
     * @Rest\Get("/product",name="fetchProducts")
     * @Rest\Get("/public/product",name="public_fetchProducts")
     * @Rest\View(serializerGroups={"product"})
     * @param Request $request
     * @return Product
     */
    public function fetchProducts(Request $request){
        $productManager = $this->get('app.product_manager');
        $orderBy = $this->calculateOrder($request, ['id']);
        return $productManager->fetchProducts($orderBy, $request->get('limit'),$request->get('offset'));
    }

    /**
     * @Rest\Delete("/product/{product}",name="deleteProduct")
     * @Rest\Delete("/public/product/{product}",name="public_deleteProduct")
     * @Rest\View(serializerGroups={"product"})
     * @param Product $product
     * @param Request $request
     * @return Product
     * @throws Exception
     */
    public function deleteProduct(Product $product, Request $request){
        $productManager = $this->get('app.product_manager');
        $result = $productManager->delete($product);
        if($result) {
            return $product;
        }
        else{
            return $result;
        }
    }

    /**
     * @Rest\Put("/product/{product}",name="updateProduct")
     * @Rest\Put("/public/product/{product}",name="public_updateProduct")
     * @Rest\View(serializerGroups={"product"})
     * @param Product $product
     * @param Request $request
     * @return Product
     * @throws Exception
     */
    public function updateProduct(Product $product, Request $request){
        $productManager = $this->get('app.product_manager');

        $form = $this->createForm(ProductType::class,$product);
        $form->submit($request->request->all());

        if($form->isValid() && $form->isSubmitted()) {
            $result = $productManager->update($product);
            if($result){
                return $product;
            }else{
                return $this->createApiException('An error occurred.');
            }
        }
        else{
            return $this->createApiException('An error occurred.');
        }
    }
}