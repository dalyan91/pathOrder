<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Account;
use AppBundle\Repository\AccountRepository;
use Doctrine\ORM\EntityManager;

/**
 * Class AccountManager
 *
 * @package AppBundle\Service
 * @property AccountRepository $repository
 */
class AccountManager extends AbstractManager
{


    /**
     * AccountManager constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct($em);

    }

    /**
     * Load user by session token in jwt
     *
     * @param string $token
     * @return Account
     */
    public function loadAccountBySessionToken($token)
    {
        return $this->repository->loadAccountBySessionToken($token);
    }

    /**
     * Load user by email for auth
     *
     * @param string $email
     * @return Account
     */
    public function loadAccountByEmail($email)
    {
        return $this->repository->loadAccountByEmail($email);
    }

    /**
     * Entity name is required
     *
     * @return string
     */
    public function getEntityName()
    {
        return 'AppBundle:Account';
    }
}
