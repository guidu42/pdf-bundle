<?php

namespace Drosalys\PdfBundle\Service;

use HeadlessChromium\BrowserFactory;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Twig\Cache\FilesystemCache;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class PdfGenerator
{
    private BrowserFactory $browserFactory;
    private $fs;

    public function __construct(
        private string $pdfTmpDir,
        private string $chromeBin,
        private ?string $defaultTemplatesDir,
        private ?string $defaultCacheDir,
        private FilesystemLoader $filesystemLoader,
        private Environment $twig,
    )
    {
        $this->browserFactory = new BrowserFactory($this->chromeBin);
        $this->fs = new Filesystem();
    }

    public function download(Pdf $pdf)
    {
        $response = new Response($this->renderOutput($pdf), 200, [
            'Content-Type' => 'application/pdf',
        ]);
        $response->headers->add(['Content-Type' => 'application/pdf']);
        $response->headers->add(['Content-Disposition' => $response->headers->makeDisposition('attachment', $pdf->getPdfFileName())]);
        return $response;
    }

    public function renderOutput(Pdf $pdf): string
    {
        $this->savePdf($pdf);
        return $this->getPdfContent($pdf);
    }

    public function savePdf(Pdf $pdf): void
    {
        if($pdf->getStorage() != null && !$pdf->getStorage()->fileExists($pdf->getPdfFileName())) {
            $pdf->getStorage()->write($pdf->getPdfFileName(), $this->getPdfContent($pdf));
        }
    }

    public function getPdfContent(Pdf $pdf): string
    {
        if($pdf->getStorage() && $pdf->getStorage()->fileExists($pdf->getPdfFileName())) {
            return $pdf->getStorage()->read($pdf->getPdfFileName());
        }

        return file_get_contents($this->createPdfFile($pdf)) ? : '';
    }

    private function setUpTwigEnvironment(Pdf $pdf): void
    {
        if($pdf->getTemplateDir()) {
            $this->filesystemLoader->addPath($pdf->getTemplateDir());
        }else if($this->defaultTemplatesDir) {
            $this->filesystemLoader->addPath($this->defaultTemplatesDir);
        }
        if($pdf->getCacheDir()) {
            $this->twig->setCache(
                new FilesystemCache($pdf->getCacheDir())
            );
        } else if($this->defaultCacheDir) {
            $this->twig->setCache(
                new FilesystemCache($this->defaultCacheDir)
            );
        }
    }

    public function createPdfFile(Pdf $pdf): string
    {
        $this->setUpTwigEnvironment($pdf);

        $pdfFile = $this->pdfTmpDir . '/' . $pdf->getPdfFileName();
        $htmlFile = $this->pdfTmpDir . '/' . $pdf->getHtmlFileName();

        $this->fs->dumpFile($htmlFile, $this->twig->render(
            $pdf->getTemplate(),
            array_merge(
                [
                    'styleEntriesPoint' => $pdf->getStyleEntryPoints(),
                    'styleFile' => $pdf->getOneStyleFile(),
                ],
                $pdf->getTemplateVars()
            )
        ));

        $browser = $this->browserFactory->createBrowser([
            'noSandbox' => true,
        ]);

        $page = $browser->createPage();
        $page->navigate('file://'.$htmlFile)->waitForNavigation();
        $page
            ->pdf($pdf->getPdfOptions())
            ->saveToFile($pdfFile)
        ;
        $browser->close();

        $this->fs->remove($htmlFile);

        return $pdfFile;
    }
}
