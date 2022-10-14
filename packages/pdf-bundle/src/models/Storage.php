<?php

use League\Flysystem\Filesystem as Flysystem;
use Symfony\Component\Finder\Finder;

class Storage
{
    private string|Flysystem $storage;
    private Finder $finder;

    public function __construct(
        Flysystem|string $storage,
        ){
        $this->finder = new Finder();
        $this->storage = $storage;
    }

    private function getDir()
    {
        if(!is_dir($this->storage)) {
            mkdir($this->storage);
        }
    }

    public function fileExists(string $fileName): bool
    {
        if(is_string($this->storage)) {
            $this->getDir();
            $this->finder->in($this->storage);
            $this->finder->files()->name($fileName);

            return $this->finder->hasResults();
        }

        return $this->storage->fileExists($fileName);
    }

    public function read(string $fileName)
    {
        if(is_string($this->storage)) {
            return file_get_contents($this->storage . '/' . $fileName);
        }

        return $this->storage->read($fileName);
    }

    public function write(string $fileName, $content)
    {
        if(is_string($this->storage)) {
            file_put_contents($this->storage . '/' . $fileName,  $content);
            return;
        }

        $this->storage->write($fileName, $content);
    }
}
