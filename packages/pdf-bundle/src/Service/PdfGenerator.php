<?php

namespace Drosalys\PdfBundle\Service;

use HeadlessChromium\BrowserFactory;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;

class PdfGenerator
{
    private BrowserFactory $browserFactory;

    public function __construct(
        private string $pdfTmpDir,
        private string $chromeBin,
        private Environment $twig,
        private Filesystem $fs,
        private bool $debug = false,
    )
    {
        $this->browserFactory = new BrowserFactory($this->chromeBin);
    }

    public function renderOutput(string $template, $webPackEntryPoint = null, array $vars = [], array $pdfOptions = []): string
    {
        $file = $this->pdfTmpDir.'/'.Uuid::uuid4()->toString();

        $htmlFile = $file.'.html';
        $pdfFile = $file.'.pdf';

        if ($this->debug || !$this->fs->exists($pdfFile)) {
            $this->fs->dumpFile($htmlFile, $this->twig->render(
                $template,
                array_merge(['styleEntryPoint' => $webPackEntryPoint], $vars)
            ));

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

            $this->fs->remove($htmlFile);
        }

        $pdfContent = file_get_contents($pdfFile) ? : '';

        $this->fs->remove($pdfFile);

        return $pdfContent;
    }
}
