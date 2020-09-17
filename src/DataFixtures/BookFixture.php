<?php


namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class BookFixture extends Fixture
{
    public function load(\Doctrine\Persistence\ObjectManager $manager)
    {
        $book = new Book();
        $book->setName('Solaris - Stanisław Lem');
        $book->addCategory(new Category('science fiction'));
        $manager->persist($book);

        $book = new Book();
        $book->setName('The Grand Design - Stephen Hawking');
        $book->addCategory(new Category('popular science'));
        $manager->persist($book);

        $manager->flush();
    }
}