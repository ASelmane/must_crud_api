<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\Category;
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
    }
}
