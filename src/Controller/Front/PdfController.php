<?php

namespace App\Controller\Front;

use Drosalys\PdfBundle\Service\PdfGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PdfController extends AbstractController
{
    #[Route('/pdf', name: 'pdf')]
    public function __invoke(PdfGenerator $pdfGenerator): Response{
        return new Response($pdfGenerator->renderOutput('pdf/pdf-test.html.twig', 'pdf-style'),200, [
            'Content-Type' => 'application/pdf'
        ]);
    }
}