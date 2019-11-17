<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


class Upload
{
    
    private $id;

    
    private $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
