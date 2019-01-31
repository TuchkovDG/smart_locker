<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class LockerRequest
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
     * @Assert\Length(max="255")
     */
    private $address;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    private $name;

    /**
     * @var Company
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="lockerRequests")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Exclude()
     */
    private $company;

    /**
     * @var string
     * @ORM\Column(type="integer")
     * @Assert\NotNull()
     * @Serializer\SerializedName("lock_count")
     */
    private $lockCount;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\SerializedName("created_at")
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getLockCount(): ?string
    {
        return $this->lockCount;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setCompany(?Company $company): void
    {
        $this->company = $company;
    }

    public function setLockCount(string $lockCount): void
    {
        $this->lockCount = $lockCount;
    }

    /**
     * @ORM\PrePersist()
     */
    public function onSave(): void
    {
        $this->createdAt = new \DateTime();
    }
}
