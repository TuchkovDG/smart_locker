<?php

namespace App\Repository;

use App\Entity\LockerRequest;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class LockerRequestRepository extends AbstractRepository
{
    /** @var EntityRepository */
    private $lockerRequestRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getRepository(): EntityRepository
    {
        if (null === $this->lockerRequestRepository) {
            $this->lockerRequestRepository = $this->em->getRepository(LockerRequest::class);
        }
        return $this->lockerRequestRepository;
    }

    public function find(int $lockerRequestId): ?LockerRequest
    {
        return $this->getRepository()->find($lockerRequestId);
    }
}
