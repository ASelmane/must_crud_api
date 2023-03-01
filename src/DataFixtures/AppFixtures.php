<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $brands = ["Apple", "Samsung", "Ice Tea", "Coca Cola", "Granola", "Oreo"];
        foreach ($brands as $brd) {
            $brand = new Brand();
            $brand->setName($brd);
            $manager->persist($brand);
        }

        $categories = ["Electronique","Smartphone", "Tablette","Alimentaire","Boisson", "Biscuit"];
        foreach ($categories as $cat) {
            $category = new Category();
            $category->setName($cat);
            $manager->persist($category);
        }

        $manager->flush();

        $product = new Product();
        $product->setName("iPhone 14");
        $product->setBrand($manager->getRepository(Brand::class)->findByName("Apple")[0]);
        $product->setDescription("Denier modèle d'iPhone");
        $product->setUrl("https://www.apple.com/fr/iphone/");
        $product->setActive(true);
        $product->addCategory($manager->getRepository(Category::class)->findByName("Electronique")[0]);
        $product->addCategory($manager->getRepository(Category::class)->findByName("Smartphone")[0]);
        $manager->persist($product);

        $product = new Product();
        $product->setName("Samsung Galaxy 14");
        $product->setBrand($manager->getRepository(Brand::class)->findByName("Samsung")[0]);
        $product->setDescription("Denier modèle de Samsung");
        $product->setUrl("https://www.samsung.com/fr/");
        $product->setActive(true);
        $product->addCategory($manager->getRepository(Category::class)->findByName("Electronique")[0]);
        $product->addCategory($manager->getRepository(Category::class)->findByName("Smartphone")[0]);
        $manager->persist($product);

        $manager->flush();
    }
}
