<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use AppBundle\Form\OrderType;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Exception;

class OrderController extends BaseController
{

    /**
     * @Rest\Post("/order", name="newOrder")
     * @Rest\View(serializerGroups={"order"})
     * @param Request $request
     * @return Order|void
     * @throws Exception
     */
    public function newOrder(Request $request)
    {

        $order = new Order();

        $form = $this->createForm(OrderType::class, $order);
        $form->submit($request->request->all());
        $form->handleRequest($request);

        if($form->isValid() && $form->isSubmitted()) {
            $order->setAccount($this->getAccount());
            $order->setCustomer($this->getAccount()->getCustomer());
            $order->setTotal($order->getProduct()->getPrice()*$order->getQuantity());

            $orderManager = $this->get('app.order_manager');

            $result = $orderManager->create($order);
            if($result){
                return $order;
            }else{
                return $this->createApiException('An error occurred.',304);
            }
        }
        else{
            return $this->createApiException('Bad Request',400);
        }

    }

    /**
     * @Rest\Get("/order/{order}",name="findOrderById")
     * @Rest\View(serializerGroups={"order"})
     * @param Request $request
     * @param Order $order
     * @return Order|void
     * @throws Exception
     */
    public function findOrderById(Request $request,Order $order){
        if ($this->getAccount()->getCustomer()->getId()!=$order->getCustomer()->getId())
            return $this->createApiException('Not Found',404);
        return $order;
    }

    /**
     * @Rest\Get("/orders",name="fetchOrders")
     * @Rest\View(serializerGroups={"order"})
     * @param Request $request
     * @return Order
     */
    public function fetchOrders(Request $request){
        $orderManager = $this->get('app.order_manager');
        $orderBy = $this->calculateOrder($request, ['id']);
        return $orderManager->fetchOrders($this->getAccount()->getCustomer(), $orderBy, $request->get('limit'),$request->get('offset'));
    }

    /**
     * @Rest\Put("/order/{order}",name="updateOrder")
     * @Rest\View(serializerGroups={"order"})
     * @param Order $order
     * @param Request $request
     * @return Order|void
     * @throws Exception
     */
    public function updateOrder(Order $order, Request $request){
        if ($this->getAccount()->getCustomer()->getId()!=$order->getCustomer()->getId())
            return $this->createApiException('Not Found',404);
        if ($order->getShippingDate()< new \DateTime())
            return $this->createApiException('Precondition Failed',412);

        $orderManager = $this->get('app.order_manager');

        $form = $this->createForm(OrderType::class,$order);
        $form->submit($request->request->all());

        if($form->isValid() && $form->isSubmitted()) {
            $order->setTotal($order->getProduct()->getPrice()*$order->getQuantity());
            $result = $orderManager->update($order);
            if($result){
                return $order;
            }else{
                return $this->createApiException('An error occurred.',304);
            }
        }
        else{
            return $this->createApiException('Bad Request',400);
        }
    }
}