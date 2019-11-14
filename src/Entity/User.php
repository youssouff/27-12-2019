<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User 
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Photo", mappedBy="author", orphanRemoval=true)
     */
    private $photos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="author", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Photo", mappedBy="users")
     */
    private $likedphotos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderHistory", mappedBy="author", orphanRemoval=true)
     */
    private $orderHistories;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Evenement", mappedBy="participant")
     */
    private $evenements;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->likedphotos = new ArrayCollection();
        $this->orderHistories = new ArrayCollection();
        $this->evenements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getCenter(): ?Center
    {
        return $this->center;
    }

    public function setCenter(?Center $center): self
    {
        $this->center = $center;

        return $this;
    }

    /**
     * @return Collection|Photo[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setAuthor($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->contains($photo)) {
            $this->photos->removeElement($photo);
            // set the owning side to null (unless already changed)
            if ($photo->getAuthor() === $this) {
                $photo->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Photo[]
     */
    public function getLikedphotos(): Collection
    {
        return $this->likedphotos;
    }

    public function addLikedphoto(Photo $likedphoto): self
    {
        if (!$this->likedphotos->contains($likedphoto)) {
            $this->likedphotos[] = $likedphoto;
            $likedphoto->addUser($this);
        }

        return $this;
    }

    public function removeLikedphoto(Photo $likedphoto): self
    {
        if ($this->likedphotos->contains($likedphoto)) {
            $this->likedphotos->removeElement($likedphoto);
            $likedphoto->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|OrderHistory[]
     */
    public function getOrderHistories(): Collection
    {
        return $this->orderHistories;
    }

    public function addOrderHistory(OrderHistory $orderHistory): self
    {
        if (!$this->orderHistories->contains($orderHistory)) {
            $this->orderHistories[] = $orderHistory;
            $orderHistory->setAuthor($this);
        }

        return $this;
    }

    public function removeOrderHistory(OrderHistory $orderHistory): self
    {
        if ($this->orderHistories->contains($orderHistory)) {
            $this->orderHistories->removeElement($orderHistory);
            // set the owning side to null (unless already changed)
            if ($orderHistory->getAuthor() === $this) {
                $orderHistory->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Evenement[]
     */
    public function getEvenements(): Collection
    {
        return $this->evenements;
    }

    public function addEvenement(Evenement $evenement): self
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements[] = $evenement;
            $evenement->addParticipant($this);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): self
    {
        if ($this->evenements->contains($evenement)) {
            $this->evenements->removeElement($evenement);
            $evenement->removeParticipant($this);
        }

        return $this;
    }

}
