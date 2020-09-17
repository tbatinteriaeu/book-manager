<?php
namespace App\Controller;
use App\Entity\Book;
use App\Form\BookType;
use App\Service\BookManagerServiceInterface;
use Doctrine\ORM\EntityNotFoundException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\Movie;
use App\Form\MovieType;
/**
 * Book controller.
 * @Rest\Route("/api/v1", name="api_v1")
 */
class BookController extends AbstractFOSRestController
{

    /**
     * @var BookManagerServiceInterface
     */
    protected $bookManagerService;

    public function __construct(BookManagerServiceInterface $bookManagerService)
    {
        $this->bookManagerService = $bookManagerService;
    }

    /**
     * Lists all books.
     * @Rest\Get("/books/get")
     *
     * @return Response
     */
    public function getBooksAction()
    {
        $books = $this->bookManagerService->getAll();

        return $this->handleView($this->view($books, Response::HTTP_OK));
    }

    /**
     * Create Book.
     * @Rest\Post("/books/create")
     *
     * @return Response
     */
    public function postBookAction(Request $request)
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->bookManagerService->save($book);

            return Response::create(null, Response::HTTP_CREATED);
        }

        return $this->handleView($this->view($form->getErrors(), Response::HTTP_BAD_REQUEST));
    }

    /**
     * Update Book.
     * @Rest\Put("/books/update")
     *
     * @return Response
     */
    public function putBookAction(Request $request)
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book, ['edit_mode' => true]);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->bookManagerService->update($book);
            } catch (EntityNotFoundException $e) {
                return $this->json($this->view(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST));
            }

            return Response::create(null, Response::HTTP_NO_CONTENT);
        }

        return $this->handleView($this->view($form->getErrors(), Response::HTTP_BAD_REQUEST));
    }

    /**
     * Delete Book.
     * @Rest\Delete("/books/delete/{id}")
     * @param Request $request
     * @return Response
     */
    public function deleteBookAction(Request $request)
    {
        try {
            $this->bookManagerService->delete($request->get('id'));
            $response = Response::create(null, Response::HTTP_NO_CONTENT);
        } catch (EntityNotFoundException $e) {
            $response = $this->handleView($this->view(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST));

        } catch (\Throwable $e) {
            $response = $this->handleView($this->view(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR));
        }

        return $response;
    }


}