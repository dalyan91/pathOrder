<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Customer;
use AppBundle\Form\CustomerType;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Exception;

class CustomerController extends BaseController
{

    /**
     * @Rest\Post("/customer", name="newCustomer")
     * @Rest\Post("/public/customer", name="public_newCustomer")
     * @Rest\View(serializerGroups={"customer"})
     * @param Request $request
     * @return Customer
     * @throws Exception
     */
    public function newCustomer(Request $request)
    {
        $customer = new Customer();

        $form = $this->createForm(CustomerType::class, $customer);
        $form->submit($request->request->all());
        $form->handleRequest($request);

        if($form->isValid() && $form->isSubmitted()) {

            $customerManager = $this->get('app.customer_manager');

            $result = $customerManager->create($customer);
            if($result){
                return $customer;
            }else{
                return $this->createApiException('An error occurred.');
            }
        }
        else{
            return $this->createApiException('An error occurred.');
        }

    }

    /**
     * @Rest\Get("/customer/{customer}",name="findCustomerById")
     * @Rest\Get("/public/customer/{customer}",name="public_findCustomerById")
     * @Rest\View(serializerGroups={"customer"})
     * @param Request $request
     * @param Customer $customer
     * @return Customer
     * @throws Exception
     */
    public function findCustomerById(Request $request,Customer $customer){
        return $customer;
    }

    /**
     * @Rest\Get("/customer",name="fetchCustomers")
     * @Rest\Get("/public/customer",name="public_fetchCustomers")
     * @Rest\View(serializerGroups={"customer"})
     * @param Request $request
     * @return Customer
     */
    public function fetchCustomers(Request $request){
        $customerManager = $this->get('app.customer_manager');
        $orderBy = $this->calculateOrder($request, ['id']);
        return $customerManager->fetchCustomers($orderBy, $request->get('limit'),$request->get('offset'));
    }

    /**
     * @Rest\Delete("/customer/{customer}",name="deleteCustomer")
     * @Rest\Delete("/public/customer/{customer}",name="public_deleteCustomer")
     * @Rest\View(serializerGroups={"customer"})
     * @param Customer $customer
     * @param Request $request
     * @return Customer
     * @throws Exception
     */
    public function deleteCustomer(Customer $customer, Request $request){
        $customerManager = $this->get('app.customer_manager');
        $result = $customerManager->delete($customer);
        if($result) {
            return $customer;
        }
        else{
            return $result;
        }
    }

    /**
     * @Rest\Put("/customer/{customer}",name="updateCustomer")
     * @Rest\Put("/public/customer/{customer}",name="public_updateCustomer")
     * @Rest\View(serializerGroups={"customer"})
     * @param Customer $customer
     * @param Request $request
     * @return Customer
     * @throws Exception
     */
    public function updateCustomer(Customer $customer, Request $request){
        $customerManager = $this->get('app.customer_manager');

        $form = $this->createForm(CustomerType::class,$customer);
        $form->submit($request->request->all());

        if($form->isValid() && $form->isSubmitted()) {
            $result = $customerManager->update($customer);
            if($result){
                return $customer;
            }else{
                return $this->createApiException('An error occurred.');
            }
        }
        else{
            return $this->createApiException('An error occurred.');
        }
    }
}