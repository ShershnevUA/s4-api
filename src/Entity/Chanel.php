<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation as JMSAnnotation;
/**
 * @ORM\Entity(repositoryClass="App\Repository\ChanelRepository")
 * @UniqueEntity(fields="title", message="Chanel name already taken")
 */
class Chanel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @JMSAnnotation\Expose
     * @JMSAnnotation\Groups("list_channels")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @JMSAnnotation\Expose
     * @JMSAnnotation\Groups("list_channels")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @JMSAnnotation\Expose
     * @JMSAnnotation\Groups("single_channel")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="channels")
     * @ORM\JoinColumn(nullable=false)
     * @JMSAnnotation\Groups("single_channel")
     * @JMSAnnotation\Expose
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="chanel", cascade={"persist"})
     */
    private $messages;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="memberInChannels")
     */
    private $members;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="boolean")
     * @JMSAnnotation\Groups("single_channel")
     */
    private $private;

    /**
     * Chanel constructor.
     */
    public function __construct()
    {
        $this->active = true;
        $this->messages = new ArrayCollection();
        $this->members = new ArrayCollection();
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
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Chanel
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Chanel
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getOwner(): ?User
    {
        return $this->owner;
    }

    /**
     * @param User|null $owner
     * @return Chanel
     */
    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    /**
     * @param Message $message
     * @return Chanel
     */
    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setChanel($this);
        }

        return $this;
    }

    /**
     * @param Message $message
     * @return Chanel
     */
    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getChanel() === $this) {
                $message->setChanel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getNembers(): Collection
    {
        return $this->members;
    }

    /**
     * @param User $member
     * @return Chanel
     */
    public function addMember(User $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
        }

        return $this;
    }

    /**
     * @param User $member
     * @return Chanel
     */
    public function removeMember(User $member): self
    {
        if ($this->members->contains($member)) {
            $this->members->removeElement($member);
        }

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getActive(): ?bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return Chanel
     */
    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getPrivate(): ?bool
    {
        return $this->private;
    }

    /**
     * @param bool $private
     * @return Chanel
     */
    public function setPrivate(bool $private): self
    {
        $this->private = $private;

        return $this;
    }

    public function inChanel(User $user){
        $members = $this->getNembers();
        return $members->contains($user);
    }
}
