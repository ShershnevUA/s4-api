<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMSAnnotation;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 * @JMSAnnotation\ExclusionPolicy("all")
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @JMSAnnotation\Expose
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @JMSAnnotation\Expose
     */
    private $body;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     * @JMSAnnotation\Expose
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Chanel", inversedBy="messages")
     */
    private $chanel;

    /**
     * Message constructor.
     * @param $body
     * @param $author
     */
    public function __construct( User $author)
    {
        $this->createdAt = new \DateTime();
        $this->author = $author;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return Message
     */
    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $createdAt
     * @return Message
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @param User|null $author
     * @return Message
     */
    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Chanel|null
     */
    public function getChanel(): ?Chanel
    {
        return $this->chanel;
    }

    /**
     * @param Chanel|null $chanel
     * @return Message
     */
    public function setChanel(?Chanel $chanel): self
    {
        $this->chanel = $chanel;

        return $this;
    }
}
