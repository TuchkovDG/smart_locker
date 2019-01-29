<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class Locker
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="lockers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $company;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Lock", mappedBy="locker")
     */
    private $locks;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->locks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @return Collection|Lock[]
     */
    public function getLocks(): Collection
    {
        return $this->locks;
    }


    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function setCompany(?Company $company): void
    {
        $this->company = $company;
    }

    public function addLock(Lock $lock): void
    {
        if (!$this->locks->contains($lock)) {
            $this->locks[] = $lock;
            $lock->setLocker($this);
        }
    }

    public function removeLock(Lock $lock): void
    {
        if ($this->locks->contains($lock)) {
            $this->locks->removeElement($lock);
            if ($lock->getLocker() === $this) {
                $lock->setLocker(null);
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
}
