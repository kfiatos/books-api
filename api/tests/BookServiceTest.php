<?php

namespace App\Tests;

use App\Dto\BookDto;
use App\Entity\Book;
use App\Service\BookService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BookServiceTest extends KernelTestCase
{
    private EntityManager $em;

    private BookService $bookService;

    public function setUp(): void
    {
        parent::setUp();
        $kernel = static::bootKernel();

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->bookService = $kernel->getContainer()->get(BookService::class);
    }

    public function testCreateNewBook(): void
    {
        $book = $this->createBookInDb();

        $bookFromDb = $this->bookService->findById(1);

        $this->assertSame($bookFromDb, $book);
    }

    public function testGetOneBookFromDatabase(): void
    {
        $book = $this->createBookInDb();

        $bookFromDb = $this->bookService->findById(1);

        $this->assertSame($bookFromDb, $book);
    }

    public function testGetManyBooksFromDatabase(): void
    {
        $this->createBookInDb();

        $booksFromDb = $this->bookService->getAll();

        $this->assertNotEmpty($booksFromDb);
    }

    public function testGetBookByIsbnFromDatabase(): void
    {
        $book = $this->createBookInDb();

        $booksFromDb = $this->bookService->findByIsbn($book->getIsbn());

        $this->assertNotEmpty($booksFromDb);
    }

    public function tearDown(): void
    {
        $this->em->getConnection()->executeQuery("TRUNCATE TABLE `book`;");
        parent::tearDown();
    }

    private function getNewBookDto(): BookDto
    {
        return new BookDto(
            'some title',
            20.00,
            'Famous Author',
            '2021-01-01',
            1,
            '2-1234-5680-2'
        );
    }

    private function createBookInDb(): Book
    {
        $newBookDto = $this->getNewBookDto();
        $book = Book::createFromBookDto($newBookDto);

        $this->bookService->storeBook($book);

        return $book;
    }


}
