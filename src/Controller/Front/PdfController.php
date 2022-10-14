<?php

namespace App\Controller\Front;

use Drosalys\PdfBundle\Service\Pdf;
use Drosalys\PdfBundle\Service\PdfGenerator;
use EntryPoint;
use League\Flysystem\FilesystemOperator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class PdfController extends AbstractController
{
    public function __construct(
        private FilesystemOperator $pdfStorageFilesystem,
        private string $pdfSaveDir,
        private KernelInterface $kernel
    ){}

    #[Route('/pdf', name: 'pdf')]
    public function __invoke(PdfGenerator $pdfGenerator): Response{
        $pdf = (new Pdf())
            ->setTemplate('pdf/pdf-test.html.twig')
            ->addStyleEntryPoint(new EntryPoint('pdf-style'))
            ->addStyleEntryPoint(new EntryPoint('pdf-style-2'))
            ->addStyleFile($this->kernel->getProjectDir() . '/assets/pdf/' . 'test.css')
            ->addStyleFile($this->kernel->getProjectDir() . '/assets/pdf/' . 'test2.css')
            ->setFileName('test2')
            ->setPdfOptions(['printBackground' => true])
//            ->setStorage($this->pdfStorageFilesystem)
//            ->setStorage($this->pdfSaveDir)
        ;

        return new Response($pdfGenerator->renderOutput($pdf),
            200, [
            'Content-Type' => 'application/pdf'
        ]);

        return $pdfGenerator->download($pdf);
    }
}
