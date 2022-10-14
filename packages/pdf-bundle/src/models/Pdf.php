<?php

namespace Drosalys\PdfBundle\Service;

use EntryPoint;
use PHPUnit\Util\Xml\Exception;
use Storage;

class Pdf
{
    private ?\Storage $storage = null;

    private string $fileName;

    private string $template;

    private array $templateVars = [];

    private array $pdfOptions = [];

    private array $styleEntryPoints = [];

    private array $styleFiles = [];

    public function __construct()
    {
        $this->template = '@DrosalysPdf/pdf_base.html.twig';
    }

    public function getOneStyleFile()
    {
        $contents = '';
        foreach ($this->getStyleFiles() as $file) {
            $contents .= file_get_contents($file);
        }

        return $contents;
    }

    public function getHtmlFileName(): string
    {
        return $this->getFileName() . '.html';
    }

    public function getPdfFileName(): string
    {
        return $this->getFileName() . '.pdf';
    }

    public function getStorage()
    {
        return $this->storage;
    }

    public function setStorage($storage): self
    {
        $this->storage = new Storage($storage);

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

    public function getStyleFiles(): array
    {
        return $this->styleFiles;
    }

    public function addStyleFile(string $file): self
    {
        if(!in_array($file, $this->styleFiles)) {
            $this->styleFiles[] = $file;
        }
        return $this;
    }

    public function setStyleFiles(array $styleFiles): self
    {
        $this->styleFiles = $styleFiles;

        return $this;
    }
}
