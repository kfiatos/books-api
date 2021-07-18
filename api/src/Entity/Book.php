<?php

namespace App\Entity;

use App\Dto\BookDto;
use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Request;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
class Book
{
    public const STATUS =
        [
            0 => 'not available',
            1 => 'available'
        ];


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    /**
     * @ORM\Column(type="date")
     */
    private $releaseDate;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $isbn;

    /**
     * @ORM\Column(type="float", scale=2)
     */
    private $price;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $coverUrl;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(?string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCoverUrl(): ?string
    {
        return $this->coverUrl;
    }

    public function setCoverUrl(?string $coverUrl): self
    {
        $this->coverUrl = $coverUrl;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        if (!isset(self::STATUS[$status])) {
            throw new \Exception(sprintf('Status %d not supported', $status));
        }
        $this->status = $status;

        return $this;
    }

    public static function createFromBookDto(BookDto $bookDto)
    {
        $book = new self();
        $book->setAuthor($bookDto->author);
        $book->setCoverUrl($bookDto->coverUrl ?? null);
        $book->setDescription($bookDto->description);
        $book->setIsbn($bookDto->isbn);
        $book->setPrice($bookDto->price);
        $book->setReleaseDate(new \DateTime($bookDto->releaseDate));
        $book->setTitle($bookDto->title);
        $book->setStatus($bookDto->status);

        return $book;
    }
}
