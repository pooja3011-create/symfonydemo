<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MicroPostRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class MicroPost
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=280)
     * @Assert\NotBlank()
     * @Assert\Length(min=10, minMessage="This not enough")
     */
    private $text;

    /**
     * @ORM\Column(type="datetime")
     */
    private $time;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts") 
     * @ORM\JoinColumn(nullable=false) 
     */
    /* nullable false means user id redired for add post (means must login)*/
    private $user;
    
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="postsLiked")
     * @ORM\JoinTable(name="post_likes",
     *     joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     */
    private $likedBy;
    
    public function __construct() 
    {
        $this->likedBy = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function setTime($time): self
    {
        $this->time = $time;

        return $this;
    }
    
    /**
     *@ORM\PrePersist() 
     */
    public function setTimeOnPersist() : void
    {
        $this->time = new \DateTime();
    }
    
    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }
    
    public function getLikedBy() 
    {
        return $this->likedBy;
    }
    
    public function like(User $user) 
    {
        if($this->likedBy->contains($user)){
            return;
        }
        $this->likedBy->add($user);
    }
}
