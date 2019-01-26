<?php

namespace App\Repository;

use App\Entity\User;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class UserRepository
{
    /** @var EntityManager */
    private $em;

    /** @var EntityRepository */
    private $userRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getRepository(): EntityRepository
    {
        if (null === $this->userRepository) {
            $this->userRepository = $this->em->getRepository(User::class);
        }
        return $this->userRepository;
    }

    public function find(int $id): ?User
    {
        return $this->getRepository()->find($id);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(User $user): void
    {
        if (!$user->getId()) {
            $this->em->persist($user);
        }
        $this->em->flush($user);
    }

    public function delete(User $user): void
    {
        $this->em->remove($user);
        $this->em->flush($user);
    }
}
