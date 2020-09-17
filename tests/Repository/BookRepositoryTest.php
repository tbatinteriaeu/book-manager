<?php


namespace App\Tests\Repository;


use App\Entity\Book;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Repository\BookRepository;

class BookRepositoryTest extends KernelTestCase
{
    private $handler;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->handler = new BookRepository($entityManager);
    }

    /**
     * @covers BookRepository::findById
     */
    public function testFindById()
    {
        $collection = $this->handler->findAll();
        $first = reset($collection);
        $id = $first->getId();
        $found = $this->handler->findById($id);
        $this->assertInstanceOf(Book::class, $found);
        $this->assertTrue($found->getId() === $id);
    }

    /**
     * @covers BookRepository::findAll
     */
    public function testFindAll()
    {
        $this->handler->findAll();
        $this->assertTrue( count($this->handler->findAll()) == 2);
    }

    /**
     * @covers BookRepository::save
     */
    public function testSave()
    {
        $initCount = count($this->handler->findAll());
        $book = new Book();
        $book->setName('Popular comedy')->addCategory(new Category('comedy'));
        $this->handler->save($book);

        $this->assertTrue(count($this->handler->findAll()) === ($initCount + 1));
    }

    /**
     * @covers BookRepository::delete
     */
    public function testDelete()
    {
        $collection = $this->handler->findAll();
        $first = reset($collection);
        $id = $first->getId();
        $this->assertInstanceOf(Book::class, $this->handler->findById($id));
        $this->handler->delete($first);

        $this->handler->findById($id);
        $this->assertNull($this->handler->findById($id));
    }
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->handler->getEntityManager()->close();
        $this->handler = null;
    }
}