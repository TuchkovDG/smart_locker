<?php

namespace App\Model;

use App\Entity\Lock;
use App\Entity\User;
use App\Repository\LockerRepository;
use App\Repository\LockRepository;
use App\Repository\UserRepository;
use FOS\RestBundle\View\View;

class LockManager
{
    private const MAX_RESERVED_LOCK_COUNT = 5;

    /** @var array */
    private $allowedStatus = [
        Lock::FREE_STATUS,
        Lock::RESERVED_STATUS,
    ];

    /** @var UserRepository */
    private $userRepository;

    /** @var LockerRepository */
    private $lockerRepository;

    /** @var LockRepository */
    private $lockRepository;

    public function __construct(
        UserRepository $userRepository,
        LockerRepository $lockerRepository,
        LockRepository $lockRepository
    ) {
        $this->userRepository = $userRepository;
        $this->lockerRepository = $lockerRepository;
        $this->lockRepository = $lockRepository;
    }

    public function isAllowedStatus(int $status): bool
    {
        return in_array($status, $this->allowedStatus, true);
    }

    public function reserveLock(User $user, Lock $lock): void
    {
        if ($user->getLocks()->count() === self::MAX_RESERVED_LOCK_COUNT) {
            throw new \LogicException('Can\'t reserve lock. Limit of ' .
                self::MAX_RESERVED_LOCK_COUNT . ' is reached');
        }
        if ($lock->isReserved()) {
            throw new \LogicException('Lock is already reserved');
        }
        $lock->reserve();
        $user->addLock($lock);
        $this->userRepository->save($user);
    }

    public function unReserveLock(User $user, Lock $lock): void
    {
        if (!$lock->isReserved()) {
            throw new \LogicException('Lock is already unreserved');
        }
        $lock->unReserve();
        $this->lockRepository->save($lock);
        $user->removeLock($lock);
        $this->userRepository->save($user);
    }

    public function openLock(Lock $lock): void
    {
        if ($lock->isOpen()) {
            throw new \LogicException('Lock is already opened');
        }
        $lock->open();
        $this->lockRepository->save($lock);
    }

    public function closeLock(Lock $lock): void
    {
        if (!$lock->isOpen()) {
            throw new \LogicException('Lock is already closed');
        }
        $lock->close();
        $this->lockRepository->save($lock);
    }
}
