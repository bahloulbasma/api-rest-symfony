<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Class Post
 * @package App\Entity
 * @ORM\Entity
 */
class Post
{
    /**
     * @var int|null
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get"})
     */
    private ?int $id;
    /**
     * @var string
     * @ORM\Column(type="text")
     * @Groups({"get"})
     * @Assert\NotBlank
     * @Assert\Length(min=10)
     */
    private string $content;
    /**
     * @var \DateTimeInterface
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"get"})
     */
    private \DateTimeInterface $publishAt;
    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     */
    private User $author;
    /**
     * @var User[]|Collection
     * @ORM\ManyToMany(targetEntity="User")
     * @ORM\JoinTable(name="post_like")
     */
    private Collection $likedby;

    /**
     * @param string $content
     * @param User $author
     * @return Post
     */
    public static function create(string $content, User $author):self
    {
        $post = new self();
        $post->content= $content;
        $post->author = $author;
        return $post;
    }


    public function __construct()
    {
        $this->publishAt = new \DateTimeImmutable();
        $this->likedby = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getPublishAt(): \DateTimeInterface
    {
        return $this->publishAt;
    }

    /**
     * @param \DateTimeInterface $publishAt
     */
    public function setPublishAt(\DateTimeInterface $publishAt): void
    {
        $this->publishAt = $publishAt;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param User $author
     */
    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }

    /**
     * @return Collection
     */
    public function getLikedby(): Collection
    {
        return $this->likedby;
    }

    /**
     * @param User $user
     * @return void
     */
    public function likeBy(User $user): void
    {
        if ($this->likedby->contains($user)) {
            return;
        }
        $this->likedby->add($user);
    }

    public function disLikeBy(User $user): void
    {
        if (!$this->likedby->contains($user)) {
            return;
        }
        $this->likedby->removeElement($user);
    }


}