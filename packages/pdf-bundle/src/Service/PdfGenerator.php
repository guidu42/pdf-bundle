<?php

namespace Drosalys\PdfBundle\Service;

use HeadlessChromium\BrowserFactory;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Symfony\Component\Asset\Packages;

class PdfGenerator
{
    private bool $savePdf;
    private int $format;
    private array $styles;
    private array $scripts;
    private BrowserFactory $browserFactory;

    public function __construct(
        private string $pdfTmpDir,
        private string $chromeBin,
        private string $saveDirectory,
        private string $projectDir,
        private Environment $twig,
        private Filesystem $fs,
        private bool $debug = false,
        $savePdf = false,
    )
    {
        $this->browserFactory = new BrowserFactory($this->chromeBin);
        $this->savePdf = false;
    }

    public function renderOutput(string $template, $webPackEntryPoint = null, array $vars = [], array $pdfOptions = []): string
    {
        $file = $this->pdfTmpDir.'/'.Uuid::uuid4()->toString();

        $htmlFile = $file.'.html';
        $pdfFile = $file.'.pdf';

        if ($this->debug || !$this->fs->exists($pdfFile)) {
//            $this->fs->dumpFile($htmlFile, $this->twig->render($template, $vars));
//            dd($this->packages->getUrl('build/' . $style));
            $this->fs->dumpFile($htmlFile, $this->twig->render('@DrosalysPdf/pdf_base.html.twig', [
                'styleEntryPoint' => $webPackEntryPoint
            ]));

            $browser = $this->browserFactory->createBrowser([
                'noSandbox' => true,
            ]);

            $page = $browser->createPage();
            $page->navigate('file://'.$htmlFile)->waitForNavigation();
            $page
                ->pdf($pdfOptions)
                ->saveToFile($pdfFile)
            ;
            $browser->close();


//            dump($pdfFile);
//            dd(file_get_contents($htmlFile));
            $this->fs->remove($htmlFile);
        }

        $pdfContent = file_get_contents($pdfFile) ? : '';

//        dump($this->pdfTmpDir);
//        dd($pdfFile);
        $this->fs->remove($pdfFile);

        return $pdfContent;
    }

    public function savePdf(): void
    {

    }

    public function getStyles(): array
    {
        return $this->styles;
    }

    public function setStyles(array $styles): void
    {
        $this->styles = $styles;
    }

    public function getScripts(): array
    {
        return $this->scripts;
    }

    public function setScripts(array $scripts): void
    {
        $this->scripts = $scripts;
    }

}