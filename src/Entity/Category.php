<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Goodies", mappedBy="category")
     */
    private $goodies;

    public function __construct()
    {
        $this->goodies = new ArrayCollection();
    }

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

    /**
     * @return Collection|Goodies[]
     */
    public function getGoodies(): Collection
    {
        return $this->goodies;
    }

    public function addGoody(Goodies $goody): self
    {
        if (!$this->goodies->contains($goody)) {
            $this->goodies[] = $goody;
            $goody->setCategory($this);
        }

        return $this;
    }

    public function removeGoody(Goodies $goody): self
    {
        if ($this->goodies->contains($goody)) {
            $this->goodies->removeElement($goody);
            // set the owning side to null (unless already changed)
            if ($goody->getCategory() === $this) {
                $goody->setCategory(null);
            }
        }

        return $this;
    }
}
