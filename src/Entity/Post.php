<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Carbon\Carbon;

/**
 * @ApiResource(
 *  collectionOperations={"get", "post"},
 *  itemOperations={
 *      "get", "put", "delete"
 *  },
 *  normalizationContext={"groups"={"post:read"}},
 *  denormalizationContext={"groups"={"post:write"}}
 * )
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     * @Groups({"post:write"})
     */
    private $id;

    /**
     * @Groups({"post:write"})
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @Groups({"post:write"})
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @Groups({"post:write"})
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $content;

    /**
     * @Groups({"post:write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tag;

    /**
     * @Groups({"post:write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @Groups({"post:write", "post:read"})
     * @ORM\ManyToOne(targetEntity=PtiUser::class, inversedBy="posts")
     */
    private $pti_user;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
    }
    
    /**
     * @Groups({"post:read"})
     */
    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    /**
     * @Groups({"post:read"})
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }
    
    /**
     * @Groups({"post:read"})
     * @return string|null
     */
    public function getCreatedAtAgo(): ?string
    {
        return Carbon::instance($this->getCreatedAt())->diffForHumans();
    }

    /**
     * @Groups({"post:read"})
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @Groups({"post:read"})
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @Groups({"post:read"})
     */
    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(?string $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * @Groups({"post:read"})
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPtiUser(): ?PtiUser
    {
        return $this->pti_user;
    }

    public function setPtiUser(?PtiUser $pti_user): self
    {
        $this->pti_user = $pti_user;

        return $this;
    }
    
}
