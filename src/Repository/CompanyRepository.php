<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use http\Exception\RuntimeException;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class CompanyRepository
{
    /** @var EntityManager */
    private $em;

    /** @var EntityRepository */
    private $companyRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getRepository(): EntityRepository
    {
        if (null === $this->companyRepository) {
            $this->companyRepository = $this->em->getRepository(Company::class);
        }
        return $this->companyRepository;
    }

    public function find(int $companyId): ?Company
    {
        return $this->getRepository()->find($companyId);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Company $company): void
    {
        if (!$company->getId()) {
            $this->em->persist($company);
        }
        $this->saveRelatedEntities($this->getRelatedEntities($company)); //todo add transaction
        $this->em->flush($company);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Company $company): void
    {
        $this->em->remove($company);
        $this->em->flush($company);
    }

    private function getRelatedEntities(Company $company): array
    {
        return [$company->getLockers()];
    }

    private function saveRelatedEntities(iterable $relatedEntities): void
    {
        foreach ($relatedEntities as $entity) {
            if ($entity instanceof Collection) {
                $this->saveRelatedEntities($entity);
            } else {
                if (!$entity->getId()) {
                    $this->em->persist($entity);
                }
                if ($this->em->getUnitOfWork()->isEntityScheduled($entity)) {
                    $this->em->flush($entity);
                }
            }
        }
    }
}
