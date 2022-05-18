<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExampleController extends AbstractController
{
    #[Route(path: '/', name: 'app.example.index')]
    public function index(): Response
    {
        return $this->json(data: ['message' => sprintf('Hello %s', $this->getUser()->getUserIdentifier())]);
    }
}