<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ResourcesController extends AbstractController
{
    #[Route('/resources', name: 'app_resources')]
    public function index(): Response
    {
        return $this->render('resources/index.html.twig', [
            'controller_name' => 'ResourcesController',
        ]);
    }
}
