<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="commit")
 */
class Commit
{
    /**
     * @var int|null
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
 private ?int $id;
    /**
     * @var string
     * @ORM\Column(type="text")
     */
 private string $message;
    /**
     * @var \DateTimeInterface
     *  @ORM\Column(type="datetime_immutable")
     */
 private \DateTimeInterface $publishAt;
    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     */
 private User $author;
    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="Post")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
 private Post $post;
    /**
     * @param string $content
     * @param User $author
     * @return Post
     */
    public static function create(string $message, User $author,Post $post):self
    {
        $commit = new self();
        $commit->message= $message;
        $commit->author = $author;
        $commit->post = $post;
        return $commit;
    }

    /**
     *
     */
    public function __construct()
    {
        $this->publishAt = new \DateTimeImmutable();
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
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
     * @return Post
     */
    public function getPost(): Post
    {
        return $this->post;
    }

    /**
     * @param Post $post
     */
    public function setPost(Post $post): void
    {
        $this->post = $post;
    }


}