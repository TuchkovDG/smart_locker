<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Locker;
use App\Repository\CompanyRepository;

use Doctrine\ORM\EntityNotFoundException;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanyController extends AbstractApiController
{
    /** @var CompanyRepository */
    private $companyRepository;

    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    /**
     * @Rest\Post("/company")
     */
    public function createCompany(Request $request): View
    {
        $company = new Company();
        $this->handleRequest($company, $request);
        $this->companyRepository->save($company);
        return View::create($company, Response::HTTP_CREATED);
    }

    /**
     * @Rest\Get("/company/{companyId}")
     */
    public function getCompany(int $companyId): View
    {
        $company = $this->companyRepository->find($companyId);
        return View::create($company, Response::HTTP_OK);
    }

    /**
     * @Rest\Patch("/company/{companyId}")
     */
    public function updateCompany(int $companyId, Request $request): View
    {
        if (!($company = $this->companyRepository->find($companyId))) {
            throw new EntityNotFoundException('Company with id ' . $companyId . ' does not exist');
        }
        $this->handleRequest($company, $request);
        $this->companyRepository->save($company);
        return View::create($company, Response::HTTP_OK);
    }

    /**
     * @Rest\Delete("/company/{companyId}")
     */
    public function deleteCompany(int $companyId)
    {
        if (!($company = $this->companyRepository->find($companyId))) {
            throw new EntityNotFoundException('Company with id ' . $companyId . ' does not exist');
        }
        $this->companyRepository->delete($company);
        return View::create([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @Rest\Patch("/company/{companyId}/locker")
     */
    public function addLocker(int $companyId, Request $request): View
    {
        if (!($company = $this->companyRepository->find($companyId))) {
            throw new EntityNotFoundException('Company with id ' . $companyId . ' does not exist');
        }
        $locker = new Locker();
        $this->handleRequest($locker, $request);
        $company->addLocker($locker);
        $this->companyRepository->save($company);
        return View::create($company, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/company/{companyId}/lockers")
     */
    public function getCompanyLockers(int $companyId)
    {
        if (!($company = $this->companyRepository->find($companyId))) {
            throw new EntityNotFoundException('Company with id ' . $companyId . ' does not exist');
        }
        $lockers = $company->getLockers();
        //todo max depth
        return View::create($lockers, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/company/login")
     */
    public function login(Request $request): void
    {
    }
}
