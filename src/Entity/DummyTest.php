<?php

namespace App\Entity;

use App\Repository\DummyTestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DummyTestRepository::class)]
class DummyTest
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $truc;

    #[ORM\Column(type: 'string', length: 255)]
    private $trucMachinTest;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    public function getTruc()
    {
        return $this->truc;
    }


    public function setTruc($truc): void
    {
        $this->truc = $truc;
    }

    public function getTrucMachinTest()
    {
        return $this->trucMachinTest;
    }

    public function setTrucMachinTest($trucMachinTest): void
    {
        $this->trucMachinTest = $trucMachinTest;
    }


}
