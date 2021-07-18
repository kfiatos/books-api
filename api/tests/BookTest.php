<?php

namespace App\Tests;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookTest extends WebTestCase
{
    private EntityManager $em;

    private $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->em = static::bootKernel()->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testBooksControllerCreate(): void
    {
        $this->requestToCreateNewBook();

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Success', $this->client->getResponse()->getContent());
    }

    public function testBooksControllerCreateDuplicateReturnsDuplicateError(): void
    {
        $this->requestToCreateNewBook();

        $this->requestToCreateNewBook();

        $this->assertResponseStatusCodeSame(409);
        $this->assertStringContainsString('Error', $this->client->getResponse()->getContent());
    }

    public function testBooksControllerCreateDuplicateReturnsBadRequestWhenNotProperISBN(): void
    {
        $this->requestToCreateNewBookWithWrongISBN();

        $this->assertResponseStatusCodeSame(400);
        $this->assertStringContainsString('ISBN-10', $this->client->getResponse()->getContent());
        $this->assertStringContainsString('ISBN-13', $this->client->getResponse()->getContent());
    }

    public function testBooksControllerGetEmptyList(): void
    {
        $this->client->request(
            'GET',
            '/api/v1/books'
        );

        $this->assertResponseIsSuccessful();
        $this->assertSame('[]', $this->client->getResponse()->getContent());
    }

    public function testBooksControllerAtLeaseOneBook(): void
    {
        $this->requestToCreateNewBook();

        $this->client->request(
            'GET',
            '/api/v1/books'
        );

        $this->assertResponseIsSuccessful();

        $result = json_decode($this->client->getResponse()->getContent(), true)[0] ?? [];
        $this->assertArrayHasKey('title', $result);
    }

    public function testBooksControllerGetOneBook(): void
    {
        $this->requestToCreateNewBook();

        $this->client->request(
            'GET',
            '/api/v1/books/1'
        );

        $this->assertResponseIsSuccessful();

        $result = json_decode($this->client->getResponse()->getContent(), true) ?? [];
        $this->assertArrayHasKey('title', $result);
    }

    public function testBooksControllerReturnNotFoundWhenNoBookStored(): void
    {
        $this->client->request(
            'GET',
            '/api/v1/books/100'
        );

        $this->assertResponseStatusCodeSame(404);
    }

    protected function assertJsonResponse($response, $statusCode = 200)
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );
    }

    private function requestToCreateNewBook(): void
    {
        $this->client->request(
            'POST',
            '/api/v1/books/new',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"author": "Some Author","description": "some description lorem ipsum","isbn": "2-1234-5680-2","price": "20","release_date": "2021-01-01","title": "Very good programming book","status": 1,"cover_url": "https://xiegarnia.pl/wp-content/uploads/2014/10/isbn-978-0-7334-2609-4.jpg"}'
        );
    }

    private function requestToCreateNewBookWithWrongISBN(): void
    {
        $this->client->request(
            'POST',
            '/api/v1/books/new',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"author": "Some Author","description": "some description lorem ipsum","isbn": "2-1234-5680-21","price": "20","release_date": "2021-01-01","title": "Very good programming book","status": 1,"cover_url": "https://xiegarnia.pl/wp-content/uploads/2014/10/isbn-978-0-7334-2609-4.jpg"}'
        );
    }

    public function tearDown(): void
    {
        $this->em->getConnection()->executeQuery("TRUNCATE TABLE `book`;");
        parent::tearDown();
    }
}
