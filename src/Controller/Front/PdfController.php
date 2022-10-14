<?php

namespace App\Controller\Front;

use Drosalys\PdfBundle\Service\PdfGenerator;
use EntryPoint;
use League\Flysystem\FilesystemOperator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PdfController extends AbstractController
{
    public function __construct(
        private FilesystemOperator $pdfStorageFilesystem
    ){}

    #[Route('/pdf', name: 'pdf')]
    public function __invoke(PdfGenerator $pdfGenerator): Response{
        $pdfGenerator
//            ->setTemplate('pdf/pdf-test.html.twig')
            ->addStyleEntryPoint(new EntryPoint('pdf-style'))
            ->addStyleEntryPoint(new EntryPoint('pdf-style-2'))
            ->setFileName('test2')
            ->setPdfOptions(['printBackground' => true]);
//            ->setStorage($this->pdfStorageFilesystem);
        return new Response($pdfGenerator->renderOutput(),
            200, [
            'Content-Type' => 'application/pdf'
        ]);
    }
}
