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
 * @ORM\HasLifecycleCallbacks()
 */
class Company implements UserInterface
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
     * @ORM\OneToMany(targetEntity="App\Entity\LockerRequest", mappedBy="company")
     * @Serializer\Exclude()
     */
    private $lockerRequests;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\SerializedName("created_at")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\SerializedName("updated_at")
     */
    private $updatedAt;

    public function __construct() {
        $this->lockers = new ArrayCollection();
        $this->lockerRequests = new  ArrayCollection();
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
        return $this->password;
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

    /** @return Collection|LockerRequest[] */
    public function getLockerRequests(): Collection
    {
        return $this->lockerRequests;
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
        $this->password = password_hash($password, PASSWORD_BCRYPT);
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

    public function addLockerRequest(LockerRequest $lockerRequest): void
    {
        if (!$this->lockerRequests->contains($lockerRequest)) {
            $this->lockerRequests[] = $lockerRequest;
            $lockerRequest->setCompany($this);
        }
    }

    public function removeLocker(Locker $locker): void
    {
        if ($this->lockers->contains($locker)) {
            $this->lockers->removeElement($locker);
            $locker->setCompany(null);
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
