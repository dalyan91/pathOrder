<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Account;
use AppBundle\Repository\AccountRepository;
use Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;


    /**
     * AccountManager constructor.
     * @param EntityManager $em
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(EntityManager $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        parent::__construct($em);

        $this->passwordEncoder = $passwordEncoder;

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
     * Load user by reset password token
     *
     * @param string $token
     * @return Account
     */
    public function loadAccountByPasswordToken($token)
    {
        return $this->repository->loadAccountByPasswordToken($token);
    }


    /**
     * @param Account $account
     * @param $token
     * @param $expireDate
     * @return bool|string
     */
    public function resetPasswordTokenUpdate(Account $account, $token, $expireDate)
    {
        $account->setResetPasswordToken($token);
        $account->setResetPasswordTokenExpire($expireDate);

        try {
            $this->em->flush();
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return true;
    }

    /**
     * @param Account $account
     * @param $password
     * @return bool|string
     */
    public function passwordUpdate(Account $account, $password)
    {
        $account->setPassword($this->passwordEncoder->encodePassword($account, $password));
        $account->setResetPasswordToken(null);
        $account->setResetPasswordTokenExpire(null);

        try {
            $this->em->flush();
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return true;
    }


    /**
     * Get account
     *
     * @param int $accountId
     * @return Account
     */
    public function forceFindAccount($accountId)
    {
        return $this->repository->forceFindAccountById($accountId);
    }


    /**
     * Create expert account
     *
     * @param Account $account
     * @return boolean|string
     */
    public function createAccount(Account $account)
    {
        $account->setPassword($this->passwordEncoder->encodePassword($account, $account->getPassword()));
        try {
            $this->em->persist($account);
            $this->em->flush();
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return true;
    }

    /**
     * @param Account $account
     * @return bool|string
     */
    public function deleteRawPassword(Account $account)
    {
        $account->setRawPassword(null);

        try {
            $this->em->persist($account);
            $this->em->flush();
        } catch (Exception $e) {
            return $e->getMessage();
        }
        return true;
    }

    /**
     * Update secretary
     *
     * @param Account $account
     * @param null $password
     * @return bool|string
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function update(Account $account, $password = null)
    {
        if (! empty($password)) {
            $account->setPassword($this->passwordEncoder->encodePassword($account, $account->getPassword()));
        }

        try {
            $this->em->flush();
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return true;
    }

    /**
     * Account Delete
     *
     * @param Account $account
     * @param null $type
     * @return bool|string
     */
    public function delete(Account $account, $type = null)
    {
        $account->setEmail(json_encode(['email' => $account->getEmail(), 'id' => $account->getId()]));

        try {
            if ($type == 'passive') {
                $account->setActive(false);

                $this->em->flush();
            } else {
                $this->em->flush();
                $this->em->remove($account);
            }

        } catch (Exception $e) {
            return $e->getMessage();
        }

        return true;
    }

    public function deleteForce(Account $account){
        try {
            $this->em->remove($account);
            $this->em->flush();
            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return true;
    }

    /**
     * User status update
     *
     * @param Account $account
     * @param string $type
     * @return boolean|string
     */
    public function status(Account $account, $type)
    {
        $account->setActive($type === 'active');

        try {
            $this->em->flush();
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return true;
    }


    /**
     * New Account
     *
     * @param Account $account
     * @return bool|string
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws Exception
     */
    public function newAccount(Account $account)
    {
        $account->setPassword($this->passwordEncoder->encodePassword($account, $account->getPassword()));
        if (!$account->getRoles()) $account->setRoles([Account::ROLE_USER]);
        try {
            $this->em->persist($account);
            $this->em->flush();
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return true;
    }


    /**
     * Update Account
     *
     * @param Account $account
     * @param $request
     * @param Account $accountOrigin
     * @return bool|string
     * @throws Exception
     */
    public function updateAccount(Account $account, $request, Account $accountOrigin)
    {
        if (array_key_exists('password', $request)) {
            $account->setPassword($this->passwordEncoder->encodePassword($account, $request['password']));
        }

        if (array_key_exists('email', $request)) {
            if ($request['email'] != $accountOrigin->getEmail()) {
                $account->setEmail($accountOrigin->getEmail());
            }
        }

        try {
            $this->em->flush();
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return true;
    }

    public function fetchAccounts($orderBy=null, $limit=null, $offset=0){
        $accounts = [];
        $count = $this->repository->getAccountCount();

        if ($count > 0) {
            $accounts = $this->repository->fetchAccounts($orderBy,$limit,$offset);
        }

        return [
            'count' => $count,
            'items' => $accounts
        ];
    }


    /**
     * @param $email
     * @return mixed
     */
    public function checkEmailExists($email)
    {
        return $this->repository->checkEmailExists($email);
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
