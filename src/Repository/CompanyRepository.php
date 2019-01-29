<?php

namespace App\Repository;

use App\Entity\Company;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class CompanyRepository extends AbstractRepository
{
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

    public function save(Company $company): void
    {
        $this->saveRelatedEntities($company->getLockers());
        $this->saveEntity($company);
    }

    public function delete(Company $company): void
    {
        $this->deleteEntity($company);
    }
}
