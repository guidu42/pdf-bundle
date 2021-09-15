<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(
    name: 'make:drosalys-crud',
    description: 'Add a short description for your command',
)]
class MakeDrosalysCrudCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('entityName', InputArgument::REQUIRED, 'Entity name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filesystem = new Filesystem();


        $entityName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input->getArgument('entityName')));

        if (!$filesystem->exists('./templates/' . $entityName)) {
            return Command::FAILURE;
        }

        $templateFilePath = './templates/' . $entityName;
        $templateFilePathTemp = $templateFilePath . '_temp';
        $modelPath = './modeles/crud/template';

        $filesystem->mkdir($templateFilePathTemp);
        $filesystem->copy($modelPath . '/_delete_form.html.twig', $templateFilePathTemp . '/_delete_form.html.twig');
        $filesystem->copy($modelPath . '/_form.html.twig', $templateFilePathTemp . '/_form.html.twig');
        $filesystem->copy($modelPath . '/edit.html.twig', $templateFilePathTemp . '/edit.html.twig');
        $filesystem->copy($modelPath . '/index.html.twig', $templateFilePathTemp . '/index.html.twig');
        $filesystem->copy($modelPath . '/new.html.twig', $templateFilePathTemp . '/new.html.twig');
        $filesystem->copy($modelPath . '/show.html.twig', $templateFilePathTemp . '/show.html.twig');

        $arrayTemplates = [
            $templateFilePathTemp . '/_delete_form.html.twig',
            $templateFilePathTemp . '/_form.html.twig',
            $templateFilePathTemp . '/edit.html.twig',
            $templateFilePathTemp . '/index.html.twig',
            $templateFilePathTemp . '/new.html.twig',
            $templateFilePathTemp . '/show.html.twig'
        ];

        $string_to_replace = "__ENTITY.id__";
        $replace_with = $entityName . ".id";
        $this->replace_string_in_file($arrayTemplates, $string_to_replace, $replace_with);

        $string_to_replace = '__DELETE_FORM_TWIG_PATH__';
        $replace_with = $entityName . "/_delete_form.html.twig";
        $this->replace_string_in_file($arrayTemplates, $string_to_replace, $replace_with);

        $string_to_replace = '__ACTUAL_FORM_TWIG_PATH__';
        $replace_with = $entityName . "/_form.html.twig";
        $this->replace_string_in_file($arrayTemplates, $string_to_replace, $replace_with);

        $string_to_replace = '__DELETE_PATH__';
        $replace_with = $entityName . "_delete";
        $this->replace_string_in_file($arrayTemplates, $string_to_replace, $replace_with);

        $string_to_replace = "__PATH_INDEX__";
        $replace_with = $entityName . "_index";
        $this->replace_string_in_file($arrayTemplates, $string_to_replace, $replace_with);

        $string_to_replace = "__PATH_NEW__";
        $replace_with = $entityName . "_new";
        $this->replace_string_in_file($arrayTemplates, $string_to_replace, $replace_with);

        $string_to_replace = "__PATH_SHOW__";
        $replace_with = $entityName . "_show";
        $this->replace_string_in_file($arrayTemplates, $string_to_replace, $replace_with);

        $string_to_replace = "__PATH_EDIT__";
        $replace_with = $entityName . "_edit";
        $this->replace_string_in_file($arrayTemplates, $string_to_replace, $replace_with);

        $string_to_replace = "__ENTITY__";
        $replace_with = $entityName;
        $this->replace_string_in_file($arrayTemplates, $string_to_replace, $replace_with);

        $string_to_replace = "__PAGINATION_ENTITIES__";
        $replace_with = $entityName . 's';
        $this->replace_string_in_file($arrayTemplates, $string_to_replace, $replace_with);

        return Command::SUCCESS;
    }

    public function replace_string_in_file($filenameArray, $string_to_replace, $replace_with)
    {
        foreach ($filenameArray as $filename) {
            $content = file_get_contents($filename);
            $content_chunks = explode($string_to_replace, $content);
            $content = implode($replace_with, $content_chunks);
            file_put_contents($filename, $content);
        }
    }
}
