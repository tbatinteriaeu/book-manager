<?php


namespace App\Service;


use App\Entity\Book;
use App\Repository\BookRepositoryInterface;
use Doctrine\Common\Collections\Collection;

interface BookManagerServiceInterface
{

    /**
     * @return array
     */
    public function getAll() : array;

    /**
     * @param Book $book
     */
    public function save(Book $book) : void;

    /**
     * @param Book $book
     */
    public function update(Book $book) : void;

    /**
     * @param int $id
     */
    public function delete(int $id) : void;
}