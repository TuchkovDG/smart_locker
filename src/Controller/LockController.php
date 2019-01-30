<?php

namespace App\Controller;

use App\Repository\LockRepository;

use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;

class LockController extends AbstractApiController
{
    /** @var LockRepository */
    private $lockRepository;

    public function __construct(LockRepository $lockRepository)
    {
        $this->lockRepository = $lockRepository;
    }

    /**
     * @Rest\Get("/lock/{lockID}")
     */
    public function getLock(int $lockId): View
    {
        $lock = $this->lockRepository->find($lockId);
        return View::create($lock, Response::HTTP_OK);
    }

    /**
     * @Rest\Patch("/lock/{lockID}")
     */
    public function updateLock(int $lockId, Request $request): View
    {
        if (!($lock = $this->lockRepository->find($lockId))) {
            throw new EntityNotFoundException('Lock with id ' . $lockId . ' does not exist');
        }
        $this->handleRequest($lock, $request);
        $this->lockRepository->save($lock);
        return View::create($lock, Response::HTTP_OK);
    }


    /**
     * @Rest\Delete("/lock/{lockID}")
     */
    public function deleteLock(int $lockId): View
    {
        if (!($lock = $this->lockRepository->find($lockId))) {
            throw new EntityNotFoundException('Lock with id ' . $lockId . ' does not exist');
        }
        $this->lockRepository->delete($lock);
        return View::create([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @Rest\Get("/locks")
     */
    public function getLocks(Request $request): View
    {
        $limit = $request->get('limit') ?: 1000;
        $offset = $request->get('offset') ?: 0;

        $locks = $this->lockRepository->getRepository()->createQueryBuilder('lock')
            ->orderBy('lock.id')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
        return View::create($locks, Response::HTTP_OK);
    }
}
