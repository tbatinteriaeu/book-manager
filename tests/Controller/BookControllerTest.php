<?php


namespace App\Tests\Controller;

use App\Controller\BookController;
use App\Tests\Controller\DataFixtures\BookFixture;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
class BookControllerTest extends WebTestCase
{
    /** @var EntityManager $em */
    private $em;

    private $apiUrl = '/api/v1/books/';

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        $this->client = null;

        parent::tearDown();
        (new SchemaTool($this->em))->dropDatabase();
    }


    /**
     * @covers BookController::deleteBookAction
     */
    public function testDeleteBookNotFoundAction()
    {
        $this->reloadDataFixtures();
        $client = static::createClient();

        $client->request('DELETE', '/api/v1/books/delete/999999', [], [], ['CONTENT_TYPE' => 'application/json']);

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    /**
     * @covers BookController::deleteBookAction
     */
    public function testDeleteBookFoundAction()
    {
        $this->reloadDataFixtures();
        $client = static::createClient();

        $client->request('GET', $this->apiUrl . 'get');
        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $responseDecoded = json_decode($response->getContent(), true);
        $countInit = count($responseDecoded);
        $client->request('DELETE', sprintf('/api/v1/books/delete/%s', $responseDecoded[0]['id']), [], [], ['CONTENT_TYPE' => 'application/json']);
        $this->assertEquals(204, $client->getResponse()->getStatusCode());
        $client->request('GET', $this->apiUrl . 'get');
        $this->assertTrue(count(json_decode($client->getResponse()->getContent(), true)) < $countInit);
    }

    /**
     * @covers BookController::getBooksAction
     */
    public function testGetBooksAction()
    {
        $this->reloadDataFixtures();
        $client = static::createClient();

        $client->request('GET', $this->apiUrl . 'get');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $responseDecoded = json_decode($response->getContent(), true);
        $this->assertNotEmpty($responseDecoded);
        $this->assertArrayHasKey('id', $responseDecoded[0]);
        $this->assertArrayHasKey('name',$responseDecoded[0]);
        $this->assertArrayHasKey('categories',$responseDecoded[0]);
    }

    /**
     * @covers BookController::postBookAction
     */
    public function testCreateBook()
    {
        $this->reloadDataFixtures();
        $client = static::createClient();

        $client->request('POST', $this->apiUrl . 'create', ['headers' => ['Accept' => 'application/json',],], [], [], json_encode([
            'name' => 'Comedy book',
            'categories' => [['name' => 'comedy']]
        ]));

        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    /**
     * @covers BookController::postBookAction
     */
    public function testCreateNotValidBook()
    {
        $this->reloadDataFixtures();
        $client = static::createClient();

        $client->request('POST', $this->apiUrl . 'create', ['headers' => ['Accept' => 'application/json',],], [], [], json_encode([
            'categories' => [['name' => 'comedy']]
        ]));

        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    /**
     * @covers BookController::putBookAction
     */
    public function testUpdateNotFoundBook()
    {
        $this->reloadDataFixtures();
        $client = static::createClient();

        $client->request('PUT', $this->apiUrl . 'update', ['headers' => ['Accept' => 'application/json',],], [], [], json_encode([
            'name' => 'Comedy book',
            'categories' => [['name' => 'comedy']]
        ]));

        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }


    /**
     * @covers BookController::putBookAction
     */
    public function testUpdateBook()
    {
        $this->reloadDataFixtures();
        $client = static::createClient();

        $client->request('GET', $this->apiUrl . 'get');
        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $responseDecoded = json_decode($response->getContent(), true);

        $client->request('PUT', $this->apiUrl . 'update', ['headers' => ['Accept' => 'application/json',],], [], [], json_encode([
            'id' => $responseDecoded[0]['id'],
            'name' => 'Comedy book',
            'categories' => [['name' => 'comedy']]
        ]));

        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
    }

    private function reloadDataFixtures(): void
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $this->em = $kernel->getContainer()->get('doctrine')->getManager();

        $loader = new Loader();
        foreach (self::getFixtures() as $fixture) {
            $loader->addFixture($fixture);
        }

        $purger = new ORMPurger();
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);
        $executor = new ORMExecutor($this->em, $purger);
        $executor->execute($loader->getFixtures());
    }

    private static function getFixtures(): iterable
    {
        return [
            new \App\DataFixtures\BookFixture(),
        ];
    }

}