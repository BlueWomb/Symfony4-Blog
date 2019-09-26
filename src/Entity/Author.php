<?php

namespace App\Entity;

use FOS\UserBundle\Model\User as BaseUser;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="author")
 * @ORM\Entity(repositoryClass="App\Repository\AuthorRepository")
 */
class Author extends BaseUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    protected $id;

    /**
     * @var string
     * 
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     * @var string
     * 
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     * 
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     * 
     * @ORM\Column(name="company", type="string", length=255, nullable=true)
     */
    private $company;

    /**
     * @var string
     * 
     * @ORM\Column(name="short_bio", type="string", length=500)
     */
    private $shortBio;

    /**
     * @var string
     * 
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @var string
     * 
     * @ORM\Column(name="facebook", type="string", length=255, nullable=true)
     */
    private $facebook;

    /**
     * @var string
     * 
     * @ORM\Column(name="twitter", type="string", length=255, nullable=true)
     */
    private $twitter;

    /**
     * @var string
     * 
     * @ORM\Column(name="github", type="string", length=255, nullable=true)
     */
    private $github;

    /**
     * @var string
     * 
     * @ORM\Column(name="linkedin", type="string", length=255, nullable=true)
     */
    private $linkedin;

    /**
     * @ORM\OneToMany(targetEntity="Post", mappedBy="author", orphanRemoval=true)
     */
    private $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getShortBio(): ?string
    {
        return $this->shortBio;
    }

    public function setShortBio(string $shortBio): self
    {
        $this->shortBio = $shortBio;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): self
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getGithub(): ?string
    {
        return $this->github;
    }

    public function setGithub(?string $github): self
    {
        $this->github = $github;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): self
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }
}
