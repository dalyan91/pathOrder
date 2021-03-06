<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Account;
use AppBundle\Helper\Traits\RepositoryOrdeByTrait;
use AppBundle\Helper\Traits\RepositoryPaginationTrait;

/**
 * AccountRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AccountRepository extends \Doctrine\ORM\EntityRepository
{
    use RepositoryOrdeByTrait;
    use RepositoryPaginationTrait;

    /**
     * Find account by session token.
     *
     * @param $token
     * @return Account
     */
    public function loadAccountBySessionToken($token)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('account')
            ->from('AppBundle:Account', 'account')
            ->where('account.sessionToken = :token')
            ->andWhere('account.active = 1')
            ->setParameter('token', $token)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Find account by email.
     *
     * @param $email
     * @return Account
     */
    public function loadAccountByEmail($email)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('account')
            ->from('AppBundle:Account', 'account')
            ->where('account.email = :email')
            ->andWhere('account.active = 1')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
