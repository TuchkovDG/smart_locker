<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table("`lock`")
 */
class Lock
{
    private const RESERVED_STATUS = '1';
    private const FREE_STATUS = '0';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var integer
     * @ORM\Column(type="smallint")
     * @Assert\NotBlank()
     * @Assert\Choice({0, 1})
     */
    private $status = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="locks")
     */
    private $user;

    /**
     * @var Locker
     * @ORM\ManyToOne(targetEntity="App\Entity\Locker", inversedBy="locks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $locker;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $reservedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function isReserved(): bool
    {
        return $this->status === self::RESERVED_STATUS;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getAddress(): string
    {
        return $this->locker->getAddress();
    }

    public function getReservedAt(): ?\DateTimeInterface
    {
        return $this->reservedAt;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function setLocker(?Locker $locker): void
    {
        $this->locker = $locker;
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
}
