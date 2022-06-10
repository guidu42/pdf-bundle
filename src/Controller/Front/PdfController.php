<?php

namespace App\Controller\Front;

use Drosalys\Bundle\PdfBundle\Service\PdfGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PdfController
{
    #[Route('/pdf', name: 'pdf')]
    public function __invoke(PdfGenerator $pdfGenerator): Response{
        return new Response($pdfGenerator->renderOutput('pdf/pdf-test.html.twig'),200);
    }
}