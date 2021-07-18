<?php

namespace App\Controller\Healthcheck;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HealthcheckController extends AbstractController
{
    #[Route('/healthcheck', name: 'healthcheck')]
    public function index(): Response
    {
        return new Response('', Response::HTTP_OK);
    }
}
