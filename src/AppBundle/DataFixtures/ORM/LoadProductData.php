<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Product;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadProductData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 20; $i++) {
            $price = $i*5;
            $product = new Product();
            $product->setName('Name ' . $i);
            $product->setPrice($price);
            $manager->persist($product);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}