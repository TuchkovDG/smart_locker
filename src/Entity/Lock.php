<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\SerializedName;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table("`lock`")
 */
class Lock
{
    public const RESERVED_STATUS = 1;
    public const FREE_STATUS = 0;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var integer
     * @ORM\Column(type="smallint")
     * @Assert\NotNull()
     * @Assert\Choice({0, 1})
     */
    private $status = 0;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     * @Assert\NotNull()
     * @Assert\Choice({true, false})
     * @SerializedName("is_open")
     */
    private $isOpen = false;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="locks")
     * @Serializer\Exclude()
     */
    private $user;

    /**
     * @var Locker
     * @ORM\ManyToOne(targetEntity="App\Entity\Locker", inversedBy="locks")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Exclude()
     */
    private $locker;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @SerializedName("reserved_at")
     */
    private $reservedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @SerializedName("opened_at")
     */
    private $openedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function isOpen(): ?bool
    {
        return $this->isOpen;
    }

    public function getReservedAt(): ?\DateTimeInterface
    {
        return $this->reservedAt;
    }

    public function getOpenedAt(): ?\DateTimeInterface
    {
        return $this->openedAt;
    }

    /**
     * @Serializer\VirtualProperty()
     */
    public function getAddress(): string
    {
        return $this->locker->getAddress();
    }

    public function isReserved(): bool
    {
        return $this->status === self::RESERVED_STATUS;
    }

    public function reserve(): void
    {
        $this->status = self::RESERVED_STATUS;
        $this->reservedAt = new \DateTime();
    }

    public function unReserve(): void
    {
        $this->status = self::FREE_STATUS;
        $this->reservedAt = null;
    }

    public function open(): void
    {
        $this->isOpen = true;
        $this->openedAt = new \DateTime();
    }

    public function close(): void
    {
        $this->isOpen = false;
        $this->openedAt = null;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function setLocker(?Locker $locker): void
    {
        $this->locker = $locker;
    }
}
