<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class BookDto
{
    public function __construct(
        #[Assert\Length(min:1), Assert\NotNull]
        public string $title,
        #[Assert\Type('float'), Assert\NotNull]
        public float $price,
        #[Assert\Length(min:1), Assert\NotNull]
        public string $author,
        #[Assert\Date, Assert\NotNull]
        public string $releaseDate,
        #[Assert\Range(min:0, max:1), Assert\NotNull]
        public int $status,
        #[Assert\Isbn]
        public ?string $isbn = null,
        #[Assert\Url, ]
        public ?string $coverUrl = null,
        public ?string $description = null,

    ) {
    }
}
