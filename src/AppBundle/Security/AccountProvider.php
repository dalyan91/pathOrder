<?php

namespace AppBundle\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use AppBundle\Manager\AccountManager;
use AppBundle\Entity\Account;

class AccountProvider implements UserProviderInterface
{
    /**
     * @var AccountManager
     */
    private $accountManager;

    /**
     * UserProvider constructor.
     * @param AccountManager $accountManager
     */
    public function __construct(AccountManager $accountManager)
    {
        $this->accountManager = $accountManager;
    }

    /**
     * Require for interface
     *
     * @param string $value
     * @return Account
     */
    public function loadUserByUsername($value)
    {

        $account = $this->accountManager->loadAccountByEmail($value);

        if ($account) {
            return $account;
        }

        throw new UsernameNotFoundException('Account does not exist.');
    }

    /**
     * Login with session token in jwt
     *
     * @param string $token
     * @return Account
     */
    public function loadUserBySessionToken($token)
    {
        $account = $this->accountManager->loadAccountBySessionToken($token);

        if ($account) {
            return $account;
        }

        throw new UsernameNotFoundException('Account does not exist.');
    }

    /**
     * Require for interface
     *
     * @param UserInterface $account
     * @return Account
     */
    public function refreshUser(UserInterface $account)
    {
        if (! $account instanceof Account) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($account))
            );
        }

        return $this->loadUserByUsername($account->getEmail());
    }

    /**
     * Require for interface
     *
     * @param string $class
     * @return bool
     */
    public function supportsClass($class)
    {
        return Account::class === $class;
    }
}