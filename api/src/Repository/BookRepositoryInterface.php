<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Common\Collections\Collection;

interface BookRepositoryInterface
{
    public function findAll();

    public function save(Book $book): void;
}