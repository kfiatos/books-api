<?php
namespace App\Service;

use App\Entity\Book;
use App\Repository\BookRepositoryInterface;

class BookService
{
    public function __construct(
        private BookRepositoryInterface $bookRepository
    ) {}

    public function storeBook(Book $book): void
    {
        $this->bookRepository->save($book);
    }

    public function findById(int $id): ?Book
    {
        return $this->bookRepository->find($id);
    }

    public function getAll(): array
    {
        return $this->bookRepository->findAll();
    }

    public function findByIsbn(string $isbn): ?Book
    {
        return $this->bookRepository->findOneBy(['isbn' => $isbn]);
    }

}