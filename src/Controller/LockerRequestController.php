<?php

namespace App\Controller;

use App\Entity\Lock;
use App\Entity\Locker;
use App\Repository\CompanyRepository;
use App\Repository\LockerRepository;
use App\Repository\LockerRequestRepository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;

use Symfony\Component\HttpFoundation\Response;

class LockerRequestController extends AbstractApiController
{
    /** @var LockerRequestRepository */
    private $lockerRequestRepository;

    /** @var LockerRepository */
    private $lockerRepository;

    /** @var CompanyRepository */
    private $companyRepository;

    /** @var EntityManager */
    private $em;

    public function __construct(
        LockerRequestRepository $lockerRequestRepository,
        LockerRepository $lockerRepository,
        EntityManagerInterface $em
    ) {
        $this->lockerRequestRepository = $lockerRequestRepository;
        $this->lockerRepository = $lockerRepository;
        $this->em = $em;
    }

    /**
     * @Rest\Get("/locker_request/{lockerRequestId}")
     * @Rest\Options("/locker_request/{lockerRequestId}")
     */
    public function getLockerRequest(int $lockerRequestId): View
    {
        $lockerRequest = $this->lockerRequestRepository->find($lockerRequestId);
        return View::create($lockerRequest, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/locker_request/{lockerRequestId}")
     * @Rest\Options("/locker_request/{lockerRequestId}")
     */
    public function processLockerRequest(int $lockerRequestId): View
    {
        if (!($lockerRequest = $this->lockerRequestRepository->find($lockerRequestId))) {
            throw new EntityNotFoundException('Locker request with id ' . $lockerRequestId . ' does not exist');
        }
        $locker = new Locker();
        $locker->setAddress($lockerRequest->getAddress());
        $locker->setName($lockerRequest->getName());
        for ($i = 0; $i < $lockerRequest->getLockCount(); $i++) {
            $lock = new Lock();
            $locker->addLock($lock);
            $this->em->persist($lock);
        }
        $this->em->persist($locker);
        $company = $lockerRequest->getCompany();
        $company->addLocker($locker);
        $this->lockerRepository->save($locker);
        $this->em->flush($company);
        return View::create($locker, Response::HTTP_OK);
    }

    /**
     * @Rest\Delete("/locker_request/{lockerRequestId}")
     * @Rest\Options("/locker_request/{lockerRequestId}")
     */
    public function deleteLockerRequest(int $lockerRequestId)
    {
        if (!($lockerRequest = $this->lockerRequestRepository->find($lockerRequestId))) {
            throw new EntityNotFoundException('Locker request with id ' . $lockerRequestId . ' does not exist');
        }
        $this->lockerRequestRepository->delete($lockerRequest);
        return View::create([], Response::HTTP_NO_CONTENT);
    }
}
