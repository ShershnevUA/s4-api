<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation as JMSAnnotation;


/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 * @JMSAnnotation\ExclusionPolicy("all")
 */
class User implements UserInterface, Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @JMSAnnotation\Expose
     * @JMSAnnotation\Groups("users_list")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128, unique=true)
     * @Assert\NotBlank()
     * @JMSAnnotation\Expose
     * @JMSAnnotation\Groups("users_list")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @var
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Email()
     * @Assert\NotBlank()
     * @JMSAnnotation\Expose
     * @JMSAnnotation\Groups("single_user")
     */
    private $email;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="author", orphanRemoval=true)
     */
    private $messages;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Chanel", mappedBy="owner", orphanRemoval=true)
     */
    private $channels;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Chanel", mappedBy="members")
     */
    private $memberInChannels;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->isActive = true;
        $this->roles = ['ROLE_USER'];
        $this->messages = new ArrayCollection();
        $this->channels = new ArrayCollection();
        $this->memberInChannels = new ArrayCollection();
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
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     * @return User
     */
    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }


    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));

    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return array (Role|string)[] The user roles
     */
    public function getRoles()
    {
       return $this->roles;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
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
     * @return User
     */
    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setAuthor($this);
        }

        return $this;
    }

    /**
     * @param Message $message
     * @return User
     */
    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getAuthor() === $this) {
                $message->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Chanel[]
     */
    public function getChanels(): Collection
    {
        return $this->channels;
    }

    /**
     * @param Chanel $chanel
     * @return User
     */
    public function addChanel(Chanel $chanel): self
    {
        if (!$this->channels->contains($chanel)) {
            $this->channels[] = $chanel;
            $chanel->setOwner($this);
        }

        return $this;
    }

    /**
     * @param Chanel $chanel
     * @return User
     */
    public function removeChanel(Chanel $chanel): self
    {
        if ($this->channels->contains($chanel)) {
            $this->channels->removeElement($chanel);
            // set the owning side to null (unless already changed)
            if ($chanel->getOwner() === $this) {
                $chanel->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Chanel[]
     */
    public function getMemberInChanels(): Collection
    {
        return $this->memberInChannels;
    }

    /**
     * @param Chanel $memberInChanel
     * @return User
     */
    public function addMemberInChanel(Chanel $memberInChanel): self
    {
        if (!$this->memberInChannels->contains($memberInChanel)) {
            $this->memberInChannels[] = $memberInChanel;
            $memberInChanel->addMember($this);
        }

        return $this;
    }

    /**
     * @param Chanel $memberInChanel
     * @return User
     */
    public function removeMemberInChanel(Chanel $memberInChanel): self
    {
        if ($this->memberInChannels->contains($memberInChanel)) {
            $this->memberInChannels->removeElement($memberInChanel);
            $memberInChanel->removeMember($this);
        }

        return $this;
    }
}
