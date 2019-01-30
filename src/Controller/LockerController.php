<?php

namespace App\Controller;

use App\Entity\Lock;
use App\Repository\LockerRepository;

use Doctrine\ORM\EntityNotFoundException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;

class LockerController extends AbstractApiController
{
    /** @var LockerRepository */
    private $lockerRepository;

    public function __construct(LockerRepository $lockerRepository)
    {
        $this->lockerRepository = $lockerRepository;
    }

    /**
     * @Rest\Patch("/locker/{lockerId}")
     * @Rest\Options("/locker/{lockerId}")
     */
    public function updateLocker(int $lockerId, Request $request)
    {
        if (!($locker = $this->lockerRepository->find($lockerId))) {
            throw new EntityNotFoundException('Locker with id ' . $lockerId . ' does not exist');
        }
        $this->handleRequest($locker, $request);
        $this->lockerRepository->save($locker);
        return View::create($locker, Response::HTTP_OK);
    }

    /**
     * @Rest\Delete("/locker/{lockerId}")
     * @Rest\Options("/locker/{lockerId}")
     */
    public function deleteLocker(int $lockerId)
    {
        if (!($locker = $this->lockerRepository->find($lockerId))) {
            throw new EntityNotFoundException('Locker with id ' . $lockerId . ' does not exist');
        }
        $this->lockerRepository->remove($locker);
        return View::create([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @Rest\Patch("/locker/{lockerId}/lock")
     * @Rest\Options("/locker/{lockerId}/lock")
     */
    public function addLockerLock(int $lockerId, Request $request)
    {
        if (!($locker = $this->lockerRepository->find($lockerId))) {
            throw new EntityNotFoundException('Locker with id ' . $lockerId . ' does not exist');
        }
        $lock = new Lock();
        $this->handleRequest($lock, $request);
        $locker->addLock($lock);
        $this->lockerRepository->save($locker);
        return View::create($locker, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/locker/{lockerId}/lock")
     * @Rest\Options("/locker/{lockerId}/lock")
     */
    public function getLockerLocks(int $lockerId)
    {
        if (!($locker = $this->lockerRepository->find($lockerId))) {
            throw new EntityNotFoundException('Locker with id ' . $lockerId . ' does not exist');
        }
        $locks = $locker->getLocks();
        return View::create($locks, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/lockers")
     * @Rest\Options("/lockers")
     */
    public function getLockers(Request $request)
    {
        $limit = $request->get('limit') ?: 1000;
        $offset = $request->get('offset') ?: 0;

        $lockers = $this->lockerRepository->getRepository()->createQueryBuilder('locker')
            ->orderBy('locker.id')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
        return View::create($lockers, Response::HTTP_OK);
    }
}
