<?php

namespace Drosalys\PdfBundle\Service;

use EntryPoint;
use HeadlessChromium\BrowserFactory;
use League\Flysystem\Filesystem as Flysystem;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;

class PdfGenerator
{
    private ?Flysystem $storage = null;

    private string $fileName;

    private string $template;

    private array $templateVars = [];

    private array $pdfOptions = [];

    private array $styleEntryPoints = [];

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
        $this->template = '@DrosalysPdf/pdf_base.html.twig';
    }

    public function renderOutput(): string
    {
        $htmlFile = $this->pdfTmpDir . '/' . $this->getHtmlFileName();
        $pdfFile = $this->pdfTmpDir . '/' . $this->getPdfFileName();

        if($this->getStorage() !== null && $this->getStorage()->fileExists($this->getPdfFileName())) {
            return $this->getStorage()->read($this->getPdfFileName());
        }

        if ($this->debug || !$this->fs->exists($pdfFile)) {
            $this->fs->dumpFile($htmlFile, $this->twig->render(
                $this->template,
                array_merge(
                    ['styleEntriesPoint' => $this->getStyleEntryPoints()],
                    $this->getTemplateVars()
                )
            ));

            $browser = $this->browserFactory->createBrowser([
                'noSandbox' => true,
            ]);

            $page = $browser->createPage();
            $page->navigate('file://'.$htmlFile)->waitForNavigation();
            $page
                ->pdf($this->pdfOptions)
                ->saveToFile($pdfFile)
            ;
            $browser->close();

            $this->fs->remove($htmlFile);
        }

        $pdfContent = file_get_contents($pdfFile) ? : '';

        if($this->getStorage() != null && !$this->getStorage()->fileExists($this->getPdfFileName())) {
            $this->getStorage()->write($this->getPdfFileName(), $pdfContent);
        }

        $this->fs->remove($pdfFile);

        return $pdfContent;
    }

    private function getHtmlFileName(): string
    {
        return $this->getFileName() . '.html';
    }

    private function getPdfFileName(): string
    {
        return $this->getFileName() . '.pdf';
    }

    public function getStorage()
    {
        return $this->storage;
    }

    public function setStorage($storage): self
    {
        $this->storage = $storage;

        return $this;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function setTemplate(string $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function getTemplateVars(): array
    {
        return $this->templateVars;
    }

    public function setTemplateVars(array $templateVars): self
    {
        $this->templateVars = $templateVars;

        return $this;
    }

    public function getPdfOptions(): array
    {
        return $this->pdfOptions;
    }

    public function setPdfOptions(array $pdfOptions): self
    {
        $this->pdfOptions = $pdfOptions;

        return $this;
    }

    public function getStyleEntryPoints(): array
    {
        return $this->styleEntryPoints;
    }

    public function addStyleEntryPoint(EntryPoint $entryPoint): self
    {
        if(!in_array($entryPoint, $this->styleEntryPoints)) {
            $this->styleEntryPoints[] = $entryPoint;
        }
        return $this;
    }

    public function setStyleEntryPoints(array $styleEntryPoints): self
    {
        $this->styleEntryPoints = $styleEntryPoints;

        return $this;
    }
}
