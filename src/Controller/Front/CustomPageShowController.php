<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomPageShowController extends AbstractController
{
    #[Route('/custom/page/show', name: 'custom_page_show')]
    public function index(): Response
    {
        return $this->render('custom_page_show/index.html.twig', [
            'controller_name' => 'CustomPageShowController',
        ]);
    }
}
