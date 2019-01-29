<?php

namespace App\Repository;

use App\Entity\User;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class UserRepository extends AbstractRepository
{
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

    public function findByUid(string $uid): ?User
    {
        return $this->getRepository()->findOneBy(['uid' => $uid]);
    }

    public function save(User $user): void
    {
        $this->saveEntity($user);
    }

    public function delete(User $user): void
    {
        $this->deleteEntity($user);
    }
}
