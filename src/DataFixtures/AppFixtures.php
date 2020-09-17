<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $book = new Book();
        $book->setName('Solaris - Stanisław Lem');
        $book->addCategory(new Category('science fiction'));
        $manager->persist($book);

        $book = new Book();
        $book->setName('The Grand Design - Stephen Hawking');
        $book->addCategory(new Category('popular science'));
        $manager->persist($book);

        // add more products

        $manager->flush();
    }
}
