<?php

namespace App\Controller;

use App\Model\LockReserveManager;

use App\Repository\LockRepository;
use App\Repository\UserRepository;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;

use http\Exception\InvalidArgumentException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class IotController extends AbstractApiController
{
    /** @var LockRepository */
    private $lockRepository;

    /** @var UserRepository */
    private $userRepository;

    /** @var LockReserveManager */
    private $lockReserveManager;

    public function __construct(
        LockRepository $lockRepository,
        UserRepository $userRepository,
        LockReserveManager $lockReserveManager
    ) {
        $this->lockRepository = $lockRepository;
        $this->userRepository = $userRepository;
        $this->lockReserveManager = $lockReserveManager;
    }

    /**
     * @Rest\Get("/random_lock/{status}")
     */
    public function getRandomLock(int $status): View
    {
        if (!$this->lockReserveManager->isAllowedStatus($status)) {
            throw new BadRequestHttpException('Not allowed status with code: ' . $status);
        }
        $randomLock = null;
        if ($locks = $this->lockRepository->findAllByStatus($status)) {
            shuffle($locks);
            $randomLock = current($locks);
        }
        return View::create($randomLock, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/lock/reserve")
     */
    public function reserveLock(Request $request): View
    {
        if (!($lockId = $request->get('lock_id'))) {
            throw new InvalidArgumentException('lock_id is required');
        }
        if (!($userId = $request->get('user_id'))) {
            throw new InvalidArgumentException('user_id is required');
        }
        if (!($lock = $this->lockRepository->find($lockId))) {
            throw new InvalidArgumentException('Lock with id ' . $lockId . ' is not exist');
        }
        if (!($user = $this->userRepository->find($lockId))) {
            throw new InvalidArgumentException('User with id ' . $userId . ' is not exist');
        }
        $this->lockReserveManager->reserveLock($user, $lock);
        return View::create($lock, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/lock/unreserve")
     */
    public function unReserveLock(Request $request): View
    {
        if (!($lockId = $request->get('lock_id'))) {
            throw new InvalidArgumentException('lock_id is required');
        }
        if (!($userId = $request->get('user_id'))) {
            throw new InvalidArgumentException('user_id is required');
        }
        if (!($lock = $this->lockRepository->find($lockId))) {
            throw new InvalidArgumentException('Lock with id ' . $lockId . ' is not exist');
        }
        if (!($user = $this->userRepository->find($lockId))) {
            throw new InvalidArgumentException('User with id ' . $userId . ' is not exist');
        }
        $this->lockReserveManager->unReserveLock($user, $lock);
        return View::create($lock, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/user/unreserve_all")
     */
    public function unReserveAllUserLocks(Request $request): View
    {
    }
}
