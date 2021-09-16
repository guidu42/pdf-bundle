<?php

namespace App\Command;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Exception\IOException;
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

        $output->writeln(['============',]);
        $filesystem = new Filesystem();
        $class = $entity = $input->getArgument('entityName');

        //CHECK SECURITY IF ENTITY EXIST
        if (!class_exists($class)) {
            $class = 'App\\Entity\\' . $class;
        }

        if (!class_exists($class)) {
            $output->writeln('Entity ' . $class . ' not found !!!!');
            return Command::FAILURE;
        }

        $ref = new \ReflectionClass($class);

        //GET ARRAY PROPERTY ENTITY SYNTHAXED
        $arrayPropertiesSynthaxed = [];
        $properties = $ref->getProperties();
        foreach ($properties as $property) {
            array_push($arrayPropertiesSynthaxed, ucfirst($property->getName()));
        }


        //GET ENTITYNAME SNAKE CASE
        $entityName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $entity));

        //CHECK IF CRUD TEMPLATE EXIST
        if (!$filesystem->exists('./templates/' . $entityName)) {
            $output->writeln('Original CRUD template not found !!!');
            return Command::FAILURE;
        }

        $output->writeln(['Original CRUD template found ok...', '============',]);

        //DEFINE NEEDED PATH VARIABLE (ORIGINAL CRUD, NEW CRUD, MODEL CRUD)
        $templateFilePath = './templates/' . $entityName;
        $templateFilePathTemp = $templateFilePath . '_temp';
        $modelPath = './modeles/crud/template';


//        GET THEAD AND TBODY FOR INDEX PAGE
        $arrayTheadTbodyIndex = $this->getTheadAndTbodyForIndex($templateFilePath, $entityName);

//        GET TBODY FOR SHOW PAGE
        $stringTbodyShow = $this->getTbodyForShow($templateFilePath, $entityName);

        //ARRAY TRANS KEY
        $arrayTransKey = [];


        //CREATE TEMP CRUD FILES
        $filesystem->mkdir($templateFilePathTemp);
        $filesystem->copy($modelPath . '/_delete_form.html.twig', $templateFilePathTemp . '/_delete_form.html.twig');
        $filesystem->copy($modelPath . '/_form.html.twig', $templateFilePathTemp . '/_form.html.twig');
        $filesystem->copy($modelPath . '/edit.html.twig', $templateFilePathTemp . '/edit.html.twig');
        $filesystem->copy($modelPath . '/index.html.twig', $templateFilePathTemp . '/index.html.twig');
        $filesystem->copy($modelPath . '/new.html.twig', $templateFilePathTemp . '/new.html.twig');
        $filesystem->copy($modelPath . '/show.html.twig', $templateFilePathTemp . '/show.html.twig');

        $output->writeln(['Temp CRUD template created ok...', '============']);

        //STOCK TEMP FILES NAMES IN AN ARRAY
        $arrayTemplates = [
            $templateFilePathTemp . '/_delete_form.html.twig',
            $templateFilePathTemp . '/_form.html.twig',
            $templateFilePathTemp . '/edit.html.twig',
            $templateFilePathTemp . '/index.html.twig',
            $templateFilePathTemp . '/new.html.twig',
            $templateFilePathTemp . '/show.html.twig'
        ];

        //REPLACE GLOBAL VARIABLES ON TEMPLATES
        $arrayStringToReplaces = [
            '__ENTITY.id__' => $entityName . ".id",
            '__DELETE_FORM_TWIG_PATH__' => $entityName . "/_delete_form.html.twig",
            '__ACTUAL_FORM_TWIG_PATH__' => $entityName . "/_form.html.twig",
            '__DELETE_PATH__' => $entityName . "_delete",
            "__PATH_INDEX__" => $entityName . "_index",
            "__PATH_NEW__" => $entityName . "_new",
            "__PATH_SHOW__" => $entityName . "_show",
            "__PATH_EDIT__" => $entityName . "_edit",
            "__ENTITY__" => $entityName,
            "__PAGINATION_ENTITIES__" => $entityName . 's'
        ];
        foreach ($arrayStringToReplaces as $key => $arrayStringToReplace) {
            $this->replace_string_in_file($arrayTemplates, $key, $arrayStringToReplace);
        }


        //REPLACE GLOBAL VARIABLES ON TEMPLATES BUT GET TRANS KEY NEEDED
        $arrayStringToReplaceWithKeys = [
            "__TITLE_INDEX_TRANS__" => 'index',
            "__TITLE_EDIT_TRANS__" => 'edit',
            "__TITLE_NEW_TRANS__" => 'new',
            "__TITLE_SHOW_TRANS__" => 'show'
        ];
        foreach ($arrayStringToReplaceWithKeys as $key => $arrayStringToReplaceWithKey) {
            $keyTrans = "\"page.admin.crud." . $entityName . "." . $arrayStringToReplaceWithKey . ".title.label\"";
            $replace_with = $keyTrans . "|trans";
            array_push($arrayTransKey, $keyTrans);
            $this->replace_string_in_file($arrayTemplates, $key, $replace_with);
        }


        //REPLACE THEAD ON INDEX
        $string_to_replace = "__ENTITY_PROPERTY_INDEX__";
        $replace_with = $arrayTheadTbodyIndex[0];
        $this->replace_string_in_file([$templateFilePathTemp . '/index.html.twig'], $string_to_replace, $replace_with);

        //REPLACE TBODY ON INDEX
        $string_to_replace = "__ENTITY_VALUE_INDEX__";
        $replace_with = $arrayTheadTbodyIndex[1];
        $this->replace_string_in_file([$templateFilePathTemp . '/index.html.twig'], $string_to_replace, $replace_with);

        //REPLACE TBODY ON SHOW
        $string_to_replace = "__TBODY_SHOW__";
        $replace_with = $stringTbodyShow;
        $this->replace_string_in_file([$templateFilePathTemp . '/show.html.twig'], $string_to_replace, $replace_with);

        //REPLACE ALL ATTRIBUTE ENTITY FROM INDEX AND SHOW BY TRANS KEY
        foreach ($arrayPropertiesSynthaxed as $propertySynthaxed) {
            $string_to_replace = '<th>' . $propertySynthaxed . '</th>';
            $key = "\"entity." . $entityName . "." . strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $propertySynthaxed)) . ".label\"";
            array_push($arrayTransKey, $key);
            $replace_with = "<th>{{ " . $key . "|trans }}</th>";
            $this->replace_string_in_file([$templateFilePathTemp . '/index.html.twig', $templateFilePathTemp . '/show.html.twig',], $string_to_replace, $replace_with);
        }

        $output->writeln(['Replace needed variables on temp CRUD template ok...', '============',]);

        //REMOVE ORIGINAL CRUD DIRECTORY
        $filesystem->remove([$templateFilePath]);

        $output->writeln(['DELETE Original CRUD ok...', '============',]);


        //RENAME TEMP CRUD DIRECTORY
        if (is_dir($templateFilePathTemp)) {
            $filesystem->rename($templateFilePathTemp, $templateFilePath, true);
        }

        //SHOW USER ALL FILES CREATED + ALL KEY NEEDED + REMINDER TO DO PAGINATION IN CONTROLLER:INDEX
        $output->writeln(['RENAME temporary CRUD ok...', '============',]);
        $output->writeln(['============',]);
        $output->writeln(['============',]);
        $output->writeln(['Your new Crud template are ready !! You can find them in :']);
        $output->writeln('template/' . $entityName . '/_delete_form.html.twig');
        $output->writeln('template/' . $entityName . '/_form.html.twig');
        $output->writeln('template/' . $entityName . '/edit.html.twig');
        $output->writeln('template/' . $entityName . '/index.html.twig');
        $output->writeln('template/' . $entityName . '/new.html.twig');
        $output->writeln(['template/' . $entityName . '/show.html.twig', '============',]);
        $output->writeln(['============',]);
        $output->writeln(['============',]);
        $output->writeln(['All the key trans needed :']);

        foreach ($arrayTransKey as $transKey) {
            $output->writeln([$transKey]);
        }
        $output->writeln(['============',]);
        $output->writeln(['============',]);
        $output->writeln(['============',]);
        $output->writeln(['Dont forget to change in your Controller:Index the findAll() by a pagination', '============',]);
        $output->writeln(['============',]);
        $output->writeln(['============',]);
        $output->writeln(['============',]);


        return Command::SUCCESS;
    }

    public function getTheadAndTbodyForIndex($templateFilePath, $entityName)
    {
        $array = [];
        $stringStandardCrudIndex = file_get_contents($templateFilePath . '/index.html.twig');

        //GET THE INDEX THEAD WITHOUT ACTIONS FOR INDEX INTO $stringTheadThStandardCrudIndex variable
        $stringTheadThStandardCrudIndex = $this->get_string_between($stringStandardCrudIndex, '<thead>', '</thead>');
        $stringTheadThStandardCrudIndex = $this->get_string_between($stringTheadThStandardCrudIndex, '<tr>', '</tr>');
        $stringTheadThStandardCrudIndexExploded = explode('</th>', $stringTheadThStandardCrudIndex);

        $i = 1;
        $stringTheadThStandardCrudIndex = '';
        foreach ($stringTheadThStandardCrudIndexExploded as $str) {
            if ($i === (count($stringTheadThStandardCrudIndexExploded) - 1)) {
                break;
            }
            $stringTheadThStandardCrudIndex .= $str . '</th>';

            $i++;
        }

        array_push($array, $stringTheadThStandardCrudIndex);


        //GET THE INDEX TBODY WITHOUT ACTIONS FOR INDEX INTO $stringTBodyTdStandardCrudIndex variable
        $stringTBodyTdStandardCrudIndex = $this->get_string_between($stringStandardCrudIndex, $entityName . 's %}', '{% else %}');
        $stringTBodyTdStandardCrudIndex = $this->get_string_between($stringTBodyTdStandardCrudIndex, '<tr>', '</tr>');
        $stringTBodyTdStandardCrudIndexExploded = explode('</td>', $stringTBodyTdStandardCrudIndex);

        $i = 1;
        $stringTBodyTdStandardCrudIndex = '';
        foreach ($stringTBodyTdStandardCrudIndexExploded as $str) {
            if ($i === (count($stringTBodyTdStandardCrudIndexExploded) - 1)) {
                break;
            }
            $stringTBodyTdStandardCrudIndex .= $str . '</td>';

            $i++;
        }

        array_push($array, $stringTBodyTdStandardCrudIndex);

        return $array;
    }

    public function getTbodyForShow($templateFilePath, $entityName)
    {
        $stringStandardCrudShow = file_get_contents($templateFilePath . '/show.html.twig');
        return $this->get_string_between($stringStandardCrudShow, '<tbody>', '</tbody>');

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

    public function get_string_between($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
}
