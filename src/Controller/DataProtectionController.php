<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DataProtectionController extends AbstractController
{
    #[Route('/protection-des-donnees', name: 'app_data_protection')]
    public function index(): Response
    {
        return $this->render('data_protection/index.html.twig', [
            'controller_name' => 'DataProtectionController',
        ]);
    }
}
