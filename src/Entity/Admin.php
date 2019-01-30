<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as Serializer;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class Admin implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)]
     * @Assert\NotBlank()
     * @Serializer\Exclude()
     */
    private $password;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Locker", mappedBy="company")
     * @Serializer\Exclude()
     */
    private $lockers;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function __construct() {
        $this->lockers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /** @return Collection|Locker[] */
    public function getLockers(): Collection
    {
        return $this->lockers;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function addLocker(Locker $locker): void
    {
        if (!$this->lockers->contains($locker)) {
            $this->lockers[] = $locker;
            $locker->setCompany($this);
        }
    }

    public function removeLocker(Locker $locker): void
    {
        if ($this->lockers->contains($locker)) {
            $this->lockers->removeElement($locker);
            if ($locker->getCompany() === $this) {
                $locker->setCompany(null);
            }
        }
    }

    /**
     * @ORM\PrePersist()
     */
    public function onSave(): void
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * @ORM\PreFlush()
     */
    public function onUpdate(): void
    {
        $this->updatedAt = new \DateTime();
    }

    public function getUsername(): ?string
    {
        return $this->email;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return ['ROLE_COMPANY'];
    }

    public function eraseCredentials(): void
    {
    }
}
