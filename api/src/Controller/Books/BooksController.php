<?php

namespace App\Controller\Books;

use App\Controller\AbstractController;
use App\Dto\BookDto;
use App\Entity\Book;
use App\Service\BookService;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use OpenApi\Annotations as OA;

class BooksController extends AbstractController
{

    /**
     * @OA\Info(title="Bookstore API", version="1")
     */
    public function __construct(public BookService $bookService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/books",
     *     @OA\Response(response="200", description="Return all the books stored in database")
     * )
     */
    #[Route('/api/v1/books', name: 'books_list', methods: ['GET'])]
    public function list(): View
    {
        $books = $this->bookService->getAll();
        return $this->view($books, Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     summary="Get book by ID",
     *     operationId="getBook",
     *     path="/api/v1/books/{id}",
     *     @OA\Parameter(
     *          parameter="id",
     *          in="path",
     *          name="id",
     *          description="ID of the book",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="author", type="string"),
     *              @OA\Property(property="title", type="string"),
     *              @OA\Property(property="release_date", type="string"),
     *              @OA\Property(property="price", type="string"),
     *              @OA\Property(property="description", type="string"),
     *              @OA\Property(property="isbn", type="string"),
     *              @OA\Property(property="cover_url", type="string"),
     *              @OA\Property(property="status", type="integer")
     *          )
     *       ),
     *     @OA\Response(response="404", description="When not book found"),
     * )
     */
    #[Route('/api/v1/books/{id}', name: 'book_get', methods: ['GET'])]
    public function get($id): View
    {
        $book = $this->bookService->findById($id);

        if (empty($book)) {
            return $this->view([], Response::HTTP_NOT_FOUND);
        }
        return $this->view($book, Response::HTTP_OK);
    }

    /**
     * @OA\Post (
     *     path="/api/v1/books/new",
     *     summary="Create new book",
     *     operationId="addBook",
     *     path="/api/v1/books/new",
     *     @OA\Parameter(
     *          name="body",
     *          in="path",
     *          required=true,
     *          @OA\JsonContent(
     *               type="object",
     *               @OA\Property(property="author", type="string"),
     *               @OA\Property(property="title", type="string"),
     *               @OA\Property(property="release_date", type="string"),
     *               @OA\Property(property="price", type="string"),
     *               @OA\Property(property="description", type="string"),
     *               @OA\Property(property="isbn", type="string"),
     *               @OA\Property(property="cover_url", type="string"),
     *               @OA\Property(property="status", type="integer")
     *          ),
     *     ),
     *     @OA\Response(response="200", description="Book successfully added to database"),
     *     @OA\Response(response="400", description="Bad request when validation fails"),
     *     @OA\Response(response="409", description="Book with given ISBN already stored")
     * )
     */
    #[Route('/api/v1/books/new', name: 'books_create', methods: ['POST'])]
    #[ParamConverter('bookDto', converter: 'fos_rest.request_body')]
    public function create(
        BookDto $bookDto,
        ConstraintViolationListInterface $validationErrors
    ): View
    {
        if (count($validationErrors) > 0) {
            return $this->view(["errors" => $validationErrors], Response::HTTP_BAD_REQUEST);
        }

        $book = Book::createFromBookDto($bookDto);

        $bookExists = $this->bookService->findByIsbn($book->getIsbn());

        if ($bookExists) {
            return $this->view(sprintf('Error. Object with ISBN %s already exists', $book->getIsbn()), Response::HTTP_CONFLICT);
        }

        $this->bookService->storeBook($book);

        return $this->view(sprintf('Success. Created object with id: %d', $book->getId()), Response::HTTP_OK);
    }
}
