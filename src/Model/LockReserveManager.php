<?php

namespace App\Model;

use App\Entity\Lock;
use App\Entity\User;

class LockReserveManager
{
    private const MAX_RESERVED_LOCK_COUNT = 5;

    public function reserveLock(User $user, Lock $lock): void
    {
        if ($user->getLocks()->count() === self::MAX_RESERVED_LOCK_COUNT) {
            throw new \LogicException('');
        }
        $user->addLock($lock);
    }
}
