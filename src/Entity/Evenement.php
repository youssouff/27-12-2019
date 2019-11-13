<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EvenementRepository")
 * @Vich\Uploadable()
 */
class Evenement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="property_image", fileNameProperty="filename")
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Recursion", inversedBy="evenements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recursion;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Photo", mappedBy="evenement", orphanRemoval=true)
     * @ORM\JoinColumn(nullable=true)
     */
    private $photos;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="evenements")
     */
    private $participant;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->participant = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getRecursion(): ?Recursion
    {
        return $this->recursion;
    }

    public function setRecursion(?Recursion $recursion): self
    {
        $this->recursion = $recursion;

        return $this;
    }

    /**
     * @return Collection|Photo[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }
    public function clearPhotos()
    {
            $this->getPhotos()->clear();
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setEvenement($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->contains($photo)) {
            $this->photos->removeElement($photo);
            // set the owning side to null (unless already changed)
            if ($photo->getEvenement() === $this) {
                $photo->setEvenement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getParticipants(): Collection
    {
        return $this->participant;
    }
    public function clearParticipants()
    {
            $this->getParticipant()->clear();
    }
    public function addParticipant(User $participant): self
    {
        if (!$this->participant->contains($participant)) {
            $this->participant[] = $participant;
        }

        return $this;
    }

    public function removeParticipant(User $participant): self
    {
        if ($this->participant->contains($participant)) {
            $this->participant->removeElement($participant);
        }

        return $this;
    }
    /**
     * @param null|string
     */
    public function getFilename(): ?String
    {
        return $this->filename;
    }
    /**
     * @param null|string $filename
     * @return Property
     */
    public function setFilename(?string $filename)
    {
        $this->filename = $filename;
        return $this;
    }
    /**
     * @param null|File
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }
    /**
     * @param null|File $imageFile
     * @return Property
     */
    public function setImageFile(?File $imageFile)
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            $this->updated_at = new \DateTime('now');
        }
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    
}
