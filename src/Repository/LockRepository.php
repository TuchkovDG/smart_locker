<?php

namespace App\Repository;

use App\Entity\Lock;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class LockRepository extends AbstractRepository
{
    /** @var EntityRepository */
    private $lockRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getRepository(): EntityRepository
    {
        if (null === $this->lockRepository) {
            $this->lockRepository = $this->em->getRepository(Lock::class);
        }
        return $this->lockRepository;
    }

    public function find(int $lockId): ?Lock
    {
        return $this->getRepository()->find($lockId);
    }

    public function save(Lock $lock): void
    {
        $this->saveEntity($lock);
    }

    public function delete(Lock $lock): void
    {
        $this->deleteEntity($lock);
    }
}
