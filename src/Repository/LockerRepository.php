<?php

namespace App\Repository;

use App\Entity\Locker;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class LockerRepository extends AbstractRepository
{
    /** @var EntityRepository */
    private $lockerRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getRepository(): EntityRepository
    {
        if (null === $this->lockerRepository) {
            $this->lockerRepository = $this->em->getRepository(Locker::class);
        }
        return $this->lockerRepository;
    }

    public function find(int $lockerId): ?Locker
    {
        return $this->getRepository()->find($lockerId);
    }

    public function save(Locker $locker): void
    {
        $this->saveRelatedEntities($locker->getLocks());
        $this->saveEntity($locker);
    }

    public function remove(Locker $locker): void
    {
        $this->deleteEntity($locker);
    }
}
