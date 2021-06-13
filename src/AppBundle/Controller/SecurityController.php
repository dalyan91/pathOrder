<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Account;

class SecurityController extends BaseController
{
    /**
     * Account Login
     *
     * @Rest\Post("login", name="login")
     * @Rest\View(serializerGroups={"account"})
     *
     * @param Request $request
     * @return mixed
     */
    public function postLoginAction(Request $request)
    {
        $error = $this->get('security.authentication_utils')->getLastAuthenticationError();

        if ($error) {
            return $error->getMessage();
        }
        /** @var Account $account */
        $account = $this->getUser();

        $token = $this->generateToken($account);

        return ['token' => $token, 'account' => $account];
    }

    /**
     * @param Account $account
     * @return string
     */
    private function generateToken(Account $account)
    {
        if (! $account->getSessionToken()) {
            $account->setSessionToken(uniqid($account->getId(), true));
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->get('jwt_encoder')->encode([
            'token' => $account->getSessionToken()
        ]);
    }

}