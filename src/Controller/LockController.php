<?php

namespace App\Controller;

use App\Entity\Lock;
use App\Repository\LockRepository;
use Doctrine\ORM\EntityNotFoundException;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LockController extends AbstractApiController
{
    /** @var LockRepository */
    private $lockRepository;

    public function __construct(LockRepository $lockRepository)
    {
        $this->lockRepository = $lockRepository;
    }

    public function getLock(int $lockId): View
    {
        $lock = $this->lockRepository->find($lockId);
        return View::create($lock, Response::HTTP_OK);
    }

    public function updateLock(int $lockId, Request $request): View
    {
        if (!($lock = $this->lockRepository->find($lockId))) {
            throw new EntityNotFoundException('Lock with id ' . $lockId . ' does not exist');
        }
        $this->handleRequest($lock, $request);
        $this->lockRepository->save($lock);
        return View::create($lock, Response::HTTP_OK);
    }

    public function deleteLock(int $lockId): View
    {
        if (!($lock = $this->lockRepository->find($lockId))) {
            throw new EntityNotFoundException('Lock with id ' . $lockId . ' does not exist');
        }
        $this->lockRepository->delete($lock);
        return View::create([], Response::HTTP_NO_CONTENT);
    }

}
