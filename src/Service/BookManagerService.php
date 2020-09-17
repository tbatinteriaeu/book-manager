<?php


namespace App\Service;


use App\Entity\Book;
use App\Repository\BookRepositoryInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityNotFoundException;
use http\Exception\InvalidArgumentException;

class BookManagerService implements BookManagerServiceInterface
{
    /**
     * @var BookRepositoryInterface
     */
    private $bookRepository;

    public function __construct(bookRepositoryInterface $bookRepository){
        $this->bookRepository = $bookRepository;
    }


    /**
     * @return Collection|null
     */
    public function getAll(): array
    {
        return $this->bookRepository->findAll();
    }

    /**
     * @param Book $book
     */
    public function save(Book $book): void
    {
        $this->bookRepository->save($book);
    }

    /**
     * @param Book $book
     */
    public function update(Book $book): void
    {
        $subject = $this->bookRepository->findById($book->getId());

        if ($subject == null) {
            throw new EntityNotFoundException(sprintf('Invalid book id: %s provided! ', $book->getId()));
        }

        $subject->setName($book->getName());
        $subject->setCategories($book->getCategories());
        $this->bookRepository->save($book);
    }

    /**
     * @param int $bookId
     */
    public function delete(int $bookId): void
    {
        $book = $this->bookRepository->findById($bookId);
        if ($book == null) {
            throw new EntityNotFoundException(sprintf('Invalid book id: %s provided! ', $bookId));
        }
        if ($book) {
            $this->bookRepository->delete($book);
        }
    }
}