<?php


namespace App\Repository;

use App\Entity\Book;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

interface BookRepositoryInterface
{
    /**
     * @param int $bookId
     * @return Book
     */
    public function findById(int $bookId): ?Book;

    /**
     * @return array
     */
    public function findAll():  array;

    /**
     * @param Book $book
     */
    public function save(Book $book): void;

    /**
     * @param Book $book
     */
    public function delete(Book $book): void;

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface;

}
