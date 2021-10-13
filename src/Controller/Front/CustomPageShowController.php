<?php

namespace App\Controller\Front;

use App\Entity\CustomPage;
use App\Enum\CustomPageEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomPageShowController extends AbstractController
{
    #[Route('/page/{accessLink}', name: 'custom_page_front_show')]
    public function index(CustomPage $customPage): Response
    {
        if ($customPage->getStatus() !== CustomPageEnum::PAGE_ENABLE) {
            return $this->redirectToRoute('home');
        }
        return $this->render('front/page/custom_page_front_show/index.html.twig', [
            'customPage' => $customPage
        ]);
    }
}
