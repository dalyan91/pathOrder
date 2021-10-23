<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use AppBundle\Form\OrderType;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Exception;

class ProductController extends BaseController
{
    /**
     * @Rest\Get("/products",name="fetchProducts")
     * @Rest\View(serializerGroups={"product"})
     * @param Request $request
     * @return Order
     */
    public function fetchProducts(Request $request){
        $productManager = $this->get('app.product_manager');
        $orderBy = $this->calculateOrder($request, ['id']);
        return $productManager->fetchProducts($orderBy, $request->get('limit'),$request->get('offset'));
    }
}