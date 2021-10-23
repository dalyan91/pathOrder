<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Account;
use AppBundle\Entity\Customer;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class LoadAccountData extends AbstractFixture implements OrderedFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $customerRepository = $manager->getRepository(Customer::class);
        $customers = $customerRepository->findAll();
        foreach ($customers as $customer) {
            for ($i = 1; $i <= 3; $i++) {
                $account = new Account();
                $account->setName('Name ' . $i);
                $account->setSurname('Surname ' . $i);
                $account->setEmail('account'.$customer->getId().$i);
                $account->setPassword($this->encoder->encodePassword($account, '12345'));
                $account->setRoles([Account::ROLE_USER]);
                $account->setCustomer($customer);
                $manager->persist($account);
            }
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}