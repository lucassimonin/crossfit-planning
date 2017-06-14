<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Product;

class LoadProductData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $product = new Product();
        $product->setName('Shirt');
        $product->setDescription('Blue shirt');
        $product->setPrice(9.99);

        $manager->persist($product);
        $manager->flush();

        $product = new Product();
        $product->setName('Shirt');
        $product->setDescription('Green shirt');
        $product->setPrice(9.43);

        $manager->persist($product);
        $manager->flush();

        $product = new Product();
        $product->setName('Shirt');
        $product->setDescription('Black shirt');
        $product->setPrice(9.5);

        $manager->persist($product);
        $manager->flush();

        $product = new Product();
        $product->setName('short');
        $product->setDescription('Green short');
        $product->setPrice(19.99);

        $manager->persist($product);
        $manager->flush();

        $product = new Product();
        $product->setName('Cap');
        $product->setDescription('Black cap');
        $product->setPrice(5.5);

        $manager->persist($product);
        $manager->flush();
    }
}
{

}
