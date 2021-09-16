<?php

namespace App\Maker;

use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;

class DecorateGenerator extends Generator
{
    public function __construct(
        private Generator $decorated,
        private string $makerPath,
    ) {
    }

    public function generateClass(string $className, string $templateName, array $variables = []): string
    {
        return $this->decorated->generateClass($className, $this->overrideTemplate($templateName), $variables);
    }

    public function generateFile(string $targetPath, string $templateName, array $variables = [])
    {
        $this->decorated->generateFile($targetPath, $this->overrideTemplate($templateName), $variables);
    }

    public function dumpFile(string $targetPath, string $contents)
    {
        $this->decorated->dumpFile($targetPath, $contents);
    }

    public function getFileContentsForPendingOperation(string $targetPath): string
    {
        return $this->decorated->getFileContentsForPendingOperation($targetPath);
    }

    public function createClassNameDetails(string $name, string $namespacePrefix, string $suffix = '', string $validationErrorMessage = ''): ClassNameDetails
    {
        return $this->decorated->createClassNameDetails($name, $namespacePrefix, $suffix, $validationErrorMessage);
    }

    public function getRootDirectory(): string
    {
        return $this->decorated->getRootDirectory();
    }

    public function hasPendingOperations(): bool
    {
        return $this->decorated->hasPendingOperations();
    }

    public function writeChanges()
    {
        $this->decorated->writeChanges();
    }

    public function getRootNamespace(): string
    {
        return $this->decorated->getRootNamespace();
    }

    public function generateController(string $controllerClassName, string $controllerTemplatePath, array $parameters = []): string
    {
        return $this->decorated->generateController($controllerClassName, $this->overrideTemplate($controllerTemplatePath), $parameters);
    }

    public function generateTemplate(string $targetPath, string $templateName, array $variables = [])
    {
        $this->decorated->generateTemplate($targetPath, $this->overrideTemplate($templateName), $variables);
    }

    private function overrideTemplate(string $templateName): string
    {
        if (!file_exists($templateName)) {
            if (file_exists($templatePath = $this->makerPath . DIRECTORY_SEPARATOR . $templateName)) {
                return $templatePath;
            }
        }

        return $templateName;
    }
}
