<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Discount;
use AppBundle\Entity\OrderDiscount;
use AppBundle\Entity\Ordering;
use AppBundle\Entity\OrderItem;
use AppBundle\Form\OrderingType;
use AppBundle\Form\OrderItemType;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Exception;
use Symfony\Component\VarDumper\VarDumper;

class OrderingController extends BaseController
{

    /**
     * @Rest\Post("/ordering", name="newOrdering")
     * @Rest\Post("/public/ordering", name="public_newOrdering")
     * @Rest\View(serializerGroups={"ordering","ordering.orderItem","orderItem"})
     * @param Request $request
     * @return Ordering
     * @throws Exception
     */
    public function newOrdering(Request $request, $order = null)
    {
        $items = $order?$order['items']:$request->get('items');

        // stok kontrolü
        if (!$order) {
            $this->stockControl($items);
        }

        $ordering = new Ordering();

        $form = $this->createForm(OrderingType::class, $ordering);
        $form->submit($order?$order:$request->request->all());
        $form->handleRequest($request);

        if($form->isValid() && $form->isSubmitted()) {
            $routeName = $request->get('_route');
            if ($routeName === "newOrdering") {
                $ordering->setAccount($this->getAccount());
            }
            $orderingManager = $this->get('app.ordering_manager');

            $result = $orderingManager->create($ordering);
            if($result){
                $resultItem = $this->addOrderItem($ordering, $request, $items);
                if ($resultItem !== true) {
                    return $resultItem;
                }
                return $ordering;
            }else{
                return $this->createApiException('An error occurred.');
            }
        }
        else{
            return $this->createApiException('An error occurred.');
        }

    }

    /**
     * @Rest\Post("/ordering/multi", name="newMultiOrdering")
     * @Rest\Post("/public/ordering/multi", name="public_newMultiOrdering")
     * @Rest\View(serializerGroups={"ordering","ordering.orderItem","orderItem"})
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function newMultiOrdering(Request $request)
    {
        $orders = $request->request->all();
        $items = [];
        foreach ($orders as $order) {
            $items = array_merge($items,$order['items']);
        }

        $stockItemLists = [];
        foreach ($items as $item) {
            $id = $item['productId'];
            $quantity = $item['quantity'];
            if(@$stockItemLists[$id]) {
                $stockItemLists[$id]['quantity'] = $stockItemLists[$id]['quantity']+$quantity;
            } else {
                $stockItemLists[$id]['productId'] = $id;
                $stockItemLists[$id]['quantity'] = $quantity;
            }
        }
        $this->stockControl($stockItemLists);

        $registerOrder = [];
        foreach ($orders as $order) {
            $registerOrder[] = $this->newOrdering($request,$order);
        }

        return $registerOrder;

    }

    /**
     * @param Ordering $ordering
     * @param $items
     * @return boolean|mixed
     */
    private function addOrderItem(Ordering $ordering, Request $request, $items) {
        $orderItemManager = $this->get('app.order_item_manager');
        $productManager = $this->get('app.product_manager');

        foreach ($items as $item) {
            $orderItem = new OrderItem();

            $form = $this->createForm(OrderItemType::class, $orderItem);
            $form->submit($item);
            $form->handleRequest($request);

            if($form->isValid() && $form->isSubmitted()) {
                $orderItem->setOrder($ordering);
                $result = $orderItemManager->create($orderItem);
                if($result){
                    $productID = $item['productId'];
                    $product = $productManager->product($productID);
                    $product->setStock($product->getStock()-$orderItem->getQuantity());
                    $result = $productManager->update($product);
                    if ($result) {} else {
                        return $this->createApiException('Could not deduct from stock.');
                    }
                }else{
                    return $this->createApiException('An error occurred.');
                }
            }
            else{
                return $this->createApiException('An error occurred.');
            }
        }
        return true;
    }

    /**
     * @param $ordering
     * @throws NonUniqueResultException
     */
    private function deleteOrderItem($ordering,$deleteItem = true) {
        $orderItemManager = $this->get('app.order_item_manager');
        $productManager = $this->get('app.product_manager');

        if ($ordering->getOrderItem()) {
            $orderItems = $orderItemManager->orderItemByOrder($ordering);
            foreach ($orderItems as $item) {
                if ($item->getProductId()) {
                    $product = $productManager->product($item->getProductId()->getID());
                    $product->setStock($product->getStock()+$item->getQuantity());
                    $productManager->update($product);
                }
                if ($deleteItem) {
                    $orderItemManager->delete($item);
                }
            }
        }
    }

    /**
     * @param $items
     * @return bool|void
     * @throws NonUniqueResultException
     */
    private function stockControl($items) {
        $productManager = $this->get('app.product_manager');

        foreach ($items as $item) {
            $productID = $item['productId'];
            $quantity = $item['quantity'];
            $product = $productManager->product($productID);
            if ($product) {
                if($quantity>$product->getStock()) {
                    return $this->createApiException('Not enough stock. ');
                }
            } else {
                return $this->createApiException('Product not found.');
            }
        }
        return true;
    }

    /**
     * @Rest\Get("/ordering/{ordering}",name="findOrderingById")
     * @Rest\Get("/public/ordering/{ordering}",name="public_findOrderingById")
     * @Rest\View(serializerGroups={"ordering","ordering.orderItem","orderItem"})
     * @param Request $request
     * @param Ordering $ordering
     * @return Ordering
     * @throws Exception
     */
    public function findOrderingById(Request $request,Ordering $ordering){
        return $ordering;
    }

    /**
     * @Rest\Get("/ordering/{ordering}/discount",name="findOrderingByIdDiscount")
     * @Rest\Get("/public/ordering/{ordering}/discount",name="public_findOrderingByIdDiscount")
     * @Rest\View(serializerGroups={"ordering","ordering.orderDiscount","orderDiscount","orderDiscount.discount","discount.code"})
     * @param Request $request
     * @param Ordering $ordering
     * @return Ordering|mixed
     * @throws Exception
     */
    public function findOrderingByIdDiscount(Request $request,Ordering $ordering){
        $this->deleteOrderDiscount($ordering);
        $this->addOrderDiscount($ordering);
        $orderingManager = $this->get('app.ordering_manager');
        return $orderingManager->orderingDiscounts($ordering->getId());
    }

    /**
     * @param Ordering $ordering
     * @return bool
     * @throws NonUniqueResultException
     */
    private function addOrderDiscount(Ordering $ordering) {
        $discountManager = $this->get('app.discount_manager');
        $orderDiscountManager = $this->get('app.order_discount_manager');
        $updateOrder = 0;
        $categoryList = [];
        foreach ($ordering->getOrderItem() as $item) {
            if ($item->getProductId()) {
                if ($item->getProductId()->getCategory()) {
                    $id = $item->getProductId()->getCategory()->getID();
                    if (@$categoryList[$id]) {
                        $categoryList[$id]['quantity'] += $item->getQuantity();
                    } else {
                        $categoryList[$id]['category'] = $id;
                        $categoryList[$id]['quantity'] = $item->getQuantity();
                        $categoryList[$id]['lowPricedProduct'] = $item;
                    }
                    if($categoryList[$id]['lowPricedProduct']->getUnitPrice()>$item->getUnitPrice()) {
                        $categoryList[$id]['lowPricedProduct'] = $item;
                    }
                }
            }
        }

        foreach ($categoryList as $category) {
            $dicount = $discountManager->caregoryUnit($category['category'], $category['quantity']);
            if ($dicount) {
                $discountUnit = floor($category['quantity']/$dicount->getOperationUnit());
                if ($dicount->getDiscountType()==Discount::DISCOUNT_TYPE_UNIT) {
                    $discountAmount = $category['lowPricedProduct']->getUnitPrice()*$dicount->getDiscount()*$discountUnit;
                } else {
                    $discountAmount = ($category['lowPricedProduct']->getUnitPrice()/100)*$dicount->getDiscount();
                }
                $total = $ordering->getTotal();
                if ($ordering->getDiscountedTotal()) {
                    $total = $ordering->getDiscountedTotal();
                }
                $subTotal = $total-$discountAmount;
                $orderDiscount = new OrderDiscount();
                $orderDiscount->setOrder($ordering);
                $orderDiscount->setDiscount($dicount);
                $orderDiscount->setDiscountAmount($discountAmount);
                $orderDiscount->setSubtotal($subTotal);
                $orderDiscountManager->create($orderDiscount);
                $ordering->setDiscountedTotal($subTotal);
                $ordering->setTotalDiscount($ordering->getTotalDiscount()+$discountAmount);
                $updateOrder = 1;
            }
        }
        $dicount = $discountManager->totalPrice($ordering);
        if ($dicount) {
            $discountAmount = ($total/100)*$dicount->getDiscount();
            $total = $ordering->getTotal();
            if ($ordering->getDiscountedTotal()) {
                $total = $ordering->getDiscountedTotal();
            }
            $subTotal = $total-$discountAmount;
            $orderDiscount = new OrderDiscount();
            $orderDiscount->setOrder($ordering);
            $orderDiscount->setDiscount($dicount);
            $orderDiscount->setDiscountAmount($discountAmount);
            $orderDiscount->setSubtotal($subTotal);
            $orderDiscountManager->create($orderDiscount);
            $ordering->setDiscountedTotal($subTotal);
            $ordering->setTotalDiscount($ordering->getTotalDiscount()+$discountAmount);
            $updateOrder = 1;
        }

        $orderingManager = $this->get('app.ordering_manager');
        if ($updateOrder) {
            $orderingManager->update($ordering);
        }
    }

    /**
     * @param Ordering $ordering
     */
    private function deleteOrderDiscount(Ordering $ordering) {
        if ($ordering->getOrderDiscount()) {
            $orderDiscountManager = $this->get('app.order_discount_manager');
            $orderDiscounts = $orderDiscountManager->orderDiscountByOrder($ordering);
            foreach ($orderDiscounts as $orderDiscount){
                $orderDiscountManager->delete($orderDiscount);
            }
        }
        if ($ordering->getDiscountedTotal() or $ordering->getTotalDiscount()) {
            $orderingManager = $this->get('app.ordering_manager');
            $ordering->setDiscountedTotal(null);
            $ordering->setTotalDiscount(null);
            $orderingManager->update($ordering);
        }
    }

    /**
     * @Rest\Get("/ordering",name="fetchOrderings")
     * @Rest\Get("/public/ordering",name="public_fetchOrderings")
     * @Rest\View(serializerGroups={"ordering","ordering.orderItem","orderItem"})
     * @param Request $request
     * @return Ordering
     */
    public function fetchOrderings(Request $request){
        $orderingManager = $this->get('app.ordering_manager');
        $orderBy = $this->calculateOrder($request, ['id']);
        return $orderingManager->fetchOrderings($orderBy, $request->get('limit'),$request->get('offset'));
    }

    /**
     * @Rest\Delete("/ordering/{ordering}",name="deleteOrdering")
     * @Rest\Delete("/public/ordering/{ordering}",name="public_deleteOrdering")
     * @Rest\View(serializerGroups={"ordering"})
     * @param Ordering $ordering
     * @param Request $request
     * @return Ordering
     * @throws Exception
     */
    public function deleteOrdering(Ordering $ordering, Request $request){
        $orderingManager = $this->get('app.ordering_manager');
        $this->deleteOrderItem($ordering,false);
        $result = $orderingManager->delete($ordering);
        if($result) {
            return $ordering;
        }
        else{
            return $result;
        }
    }

    /**
     * @Rest\Put("/ordering/{ordering}",name="updateOrdering")
     * @Rest\Put("/public/ordering/{ordering}",name="public_updateOrdering")
     * @Rest\View(serializerGroups={"ordering","ordering.orderItem","orderItem"})
     * @param Ordering $ordering
     * @param Request $request
     * @return Ordering
     * @throws Exception
     */
    public function updateOrdering(Ordering $ordering, Request $request){
        $orderingManager = $this->get('app.ordering_manager');

        $form = $this->createForm(OrderingType::class,$ordering);
        $form->submit($request->request->all());

        if($form->isValid() && $form->isSubmitted()) {
            $result = $orderingManager->update($ordering);
            if($result){
                $items = $request->get('items');
                $this->deleteOrderItem($ordering);
                $this->stockControl($items);
                $resultItem = $this->addOrderItem($ordering, $request, $items);
                if ($resultItem !== true) {
                    return $resultItem;
                }
                return $ordering;
            }else{
                return $this->createApiException('An error occurred.');
            }
        }
        else{
            return $this->createApiException('An error occurred.');
        }
    }
}