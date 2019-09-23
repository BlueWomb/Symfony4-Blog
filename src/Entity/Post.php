<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\ORM\Event\PreUpdateEventArgs;

/**
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Post
{
    /**
     * @var int
     * 
     * @ORM\Id()
     * 
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var string
     * 
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="preview", type="string", length=255)
     */
    private $preview;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=2000)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    private $body;

    /**
     * @ORM\ManyToOne(targetEntity="Author", inversedBy="posts")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="posts")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="posts")
     */
    private $tags;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetimetz")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getPreview(): ?string
    {
        return $this->preview;
    }

    public function setPreview(string $preview): self
    {
        $this->preview = $preview;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        if (!$this->getCreatedAt())
            $this->setCreatedAt(new \DateTime());

        if (!$this->getUpdatedAt())
            $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $this->setUpdatedAt(new \DateTime());
    }
}
