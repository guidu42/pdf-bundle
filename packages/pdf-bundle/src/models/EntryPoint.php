<?php

class EntryPoint
{
    private $name;

    private $packageName;

    private $entrypointName;

    private $attributes;

    public function __construct(string $name, ?string $packageName = null, string $entrypointName = '_default', array $attributes = []){
        $this->name = $name;
        $this->packageName = $packageName;
        $this->entrypointName = $entrypointName;
        $this->attributes = $attributes;
    }
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPackageName()
    {
        return $this->packageName;
    }

    /**
     * @param mixed $packageName
     */
    public function setPackageName($packageName): self
    {
        $this->packageName = $packageName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEntrypointName()
    {
        return $this->entrypointName;
    }

    /**
     * @param mixed $entrypointName
     */
    public function setEntrypointName($entrypointName): self
    {
        $this->entrypointName = $entrypointName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param mixed $attributes
     */
    public function setAttributes($attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }
}
