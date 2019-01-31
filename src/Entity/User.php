<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\ORM\Mapping\Column;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class User
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
     * @Assert\NotBlank
     * @Assert\Length(max="255")
     */
    private $uid;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Lock", mappedBy="user")
     * @Serializer\Exclude()
     */
    private $locks;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\SerializedName("created_at")
     */
    private $createdAt;

    public function __construct()
    {
        $this->locks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUid(): ?string
    {
        return $this->uid;
    }

    /** @return Collection|Lock[] */
    public function getLocks(): Collection
    {
        return $this->locks;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setUid(string $uid): void
    {
        $this->uid = $uid;
    }

    public function addLock(Lock $lock): void
    {
        if (!$this->locks->contains($lock)) {
            $this->locks[] = $lock;
            $lock->setUser($this);
        }
    }

    public function removeLock(Lock $lock): void
    {
        if ($this->locks->contains($lock)) {
            $this->locks->removeElement($lock);
            $lock->setUser(null);
        }
    }

    /**
     * @ORM\PrePersist()
     */
    public function onSave(): void
    {
        $this->createdAt = new \DateTime();
    }
}
