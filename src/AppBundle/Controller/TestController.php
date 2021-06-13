<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;

class TestController extends BaseController
{
    /**
     * @Rest\Get("/public/test", name="test_post")
     * @Rest\Get("/test", name="security_test_post")
     * @Rest\View(serializerGroups={})
     *
     * @return mixed
     */
    public function indexAction()
    {
        return ['test'=>"test"];
    }
}
