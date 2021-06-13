<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Discount;
use AppBundle\Form\DiscountType;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Exception;
use Symfony\Component\VarDumper\VarDumper;

class DiscountController extends BaseController
{

    /**
     * @Rest\Post("/discount", name="newDiscount")
     * @Rest\Post("/public/discount", name="public_newDiscount")
     * @Rest\View(serializerGroups={"discount"})
     * @param Request $request
     * @return Discount
     * @throws Exception
     */
    public function newDiscount(Request $request)
    {
        $discount = new Discount();

        $form = $this->createForm(DiscountType::class, $discount);
        $form->submit($request->request->all());
        $form->handleRequest($request);

        if($form->isValid() && $form->isSubmitted()) {
            $discountManager = $this->get('app.discount_manager');
            $result = $discountManager->create($discount);
            if($result){
                return $discount;
            }else{
                return $this->createApiException('An error occurred.');
            }
        }
        else{
            return $this->createApiException('An error occurred.');
        }

    }

    /**
     * @Rest\Get("/discount/{discount}",name="findDiscountById")
     * @Rest\Get("/public/discount/{discount}",name="public_findDiscountById")
     * @Rest\View(serializerGroups={"discount"})
     * @param Request $request
     * @param Discount $discount
     * @return Discount
     * @throws Exception
     */
    public function findDiscountById(Request $request,Discount $discount){
        return $discount;
    }

    /**
     * @Rest\Get("/discount",name="fetchDiscounts")
     * @Rest\Get("/public/discount",name="public_fetchDiscounts")
     * @Rest\View(serializerGroups={"discount"})
     * @param Request $request
     * @return Discount
     */
    public function fetchDiscounts(Request $request){
        $discountManager = $this->get('app.discount_manager');
        $orderBy = $this->calculateOrder($request, ['id']);
        return $discountManager->fetchDiscounts($orderBy, $request->get('limit'),$request->get('offset'));
    }

    /**
     * @Rest\Delete("/discount/{discount}",name="deleteDiscount")
     * @Rest\Delete("/public/discount/{discount}",name="public_deleteDiscount")
     * @Rest\View(serializerGroups={"discount"})
     * @param Discount $discount
     * @param Request $request
     * @return Discount
     * @throws Exception
     */
    public function deleteDiscount(Discount $discount, Request $request){
        $discountManager = $this->get('app.discount_manager');
        $result = $discountManager->delete($discount);
        if($result) {
            return $discount;
        }
        else{
            return $result;
        }
    }

    /**
     * @Rest\Put("/discount/{discount}",name="updateDiscount")
     * @Rest\Put("/public/discount/{discount}",name="public_updateDiscount")
     * @Rest\View(serializerGroups={"discount"})
     * @param Discount $discount
     * @param Request $request
     * @return Discount
     * @throws Exception
     */
    public function updateDiscount(Discount $discount, Request $request){
        $discountManager = $this->get('app.discount_manager');

        $form = $this->createForm(DiscountType::class,$discount);
        $form->submit($request->request->all());

        if($form->isValid() && $form->isSubmitted()) {
            $result = $discountManager->update($discount);
            if($result){
                return $discount;
            }else{
                return $this->createApiException('An error occurred.');
            }
        }
        else{
            return $this->createApiException('An error occurred.');
        }
    }
}