<?php

namespace AppBundle\Helper\Traits;

use AppBundle\Entity\Account;

trait GenerateTokenTrait
{

    /**
     * Generate Token
     *
     * @param $account
     * @return string
     */
    protected function generateToken(Account $account)
    {
        if (! $account->getSessionToken()) {
            $account->setSessionToken(uniqid($account->getId(), true));
            $this->getDoctrine()->getManager()->flush();
        }

        $token = $this->get('jwt_encoder')->encode([
            'token' => $account->getSessionToken()
        ]);

        return $token;
    }

}