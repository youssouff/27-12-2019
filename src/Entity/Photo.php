<?php

namespace App\Entity;

use App\Security\ApiUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
 */
class Photo
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
    private $url;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="photo", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Evenement", inversedBy="photos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $evenement;

    /**
     * @ORM\Column(type="json", nullable=true)|null
     */
    private $users;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;

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
            $comment->setPhoto($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getPhoto() === $this) {
                $comment->setPhoto(null);
            }
        }

        return $this;
    }
    public function clearComments()
    {
            $this->getComments()->clear();
    }


    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(?ApiUser $author): self
    {
        $this->author = $author->getUsername();

        return $this;
    }

    public function getEvenement(): ?Evenement
    {
        return $this->evenement;
    }

    public function setEvenement(?Evenement $evenement): self
    {
        $this->evenement = $evenement;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getUsers()
    {
        return $this->users;
    }

    public function addUser(ApiUser $user): self
    {    
        if($this->users){
            if (!in_array($user->getUsername(), $this->users)) 
            { 
                $this->users[] = $user->getUsername();
            }
        }else{
            $this->users[] = $user->getUsername();
        }
        return $this;
    }

    public function removeUser(ApiUser $user): self
    {
        if (($key = array_search($user->getUsername(), $this->users)) !== false) {
            unset($this->users[$key]);
        }

        return $this;

    }
}
