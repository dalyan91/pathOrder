<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Customer;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCustomerData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        for ($i = 1; $i <= 3; $i++) {
            $customer = new Customer();
            $customer->setTitle('Customer ' . $i);
            $manager->persist($customer);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}