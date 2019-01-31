<?php

namespace App\Controller;

use App\Model\LockManager;

use App\Repository\LockRepository;
use App\Repository\UserRepository;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class IotController extends AbstractApiController
{
    /** @var LockRepository */
    private $lockRepository;

    /** @var UserRepository */
    private $userRepository;

    /** @var LockManager */
    private $lockManager;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        LockRepository $lockRepository,
        UserRepository $userRepository,
        LockManager $lockManager,
        LoggerInterface $logger
    ) {
        $this->lockRepository = $lockRepository;
        $this->userRepository = $userRepository;
        $this->lockManager = $lockManager;
        $this->logger = $logger;
    }

    /**
     * @Rest\Get("/random_lock/{status}")
     */
    public function getRandomLock(int $status): View
    {
        if (!$this->lockManager->isAllowedStatus($status)) {
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
     * @Rest\Post("/user/{userId}/lock/{lockId}/reserve")
     */
    public function reserveLock(int $userId, int $lockId): View
    {
        if (!($lock = $this->lockRepository->find($lockId))) {
            throw new BadRequestHttpException('Lock with id ' . $lockId . ' is not exist');
        }
        if (!($user = $this->userRepository->find($userId))) {
            throw new BadRequestHttpException('User with id ' . $userId . ' is not exist');
        }
        $this->lockManager->reserveLock($user, $lock);
        $this->logger->info('Lock with ' . $lockId . ' was reserved by user with id ' . $userId);
        return View::create($lock, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/user/{userId}/lock/{lockId}/unreserve")
     */
    public function unReserveLock(int $userId, int $lockId): View
    {
        if (!($lock = $this->lockRepository->find($lockId))) {
            throw new BadRequestHttpException('Lock with id ' . $lockId . ' is not exist');
        }
        if (!($user = $this->userRepository->find($userId))) {
            throw new BadRequestHttpException('User with id ' . $userId . ' is not exist');
        }
        $this->lockManager->unReserveLock($user, $lock);
        $this->logger->info('Lock with ' . $lockId . ' was unreserved by user with id ' . $userId);
        return View::create($lock, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/user/{userID}/unreserve_all")
     */
    public function unReserveAllUserLocks(int $userId): View
    {
        if (!($user = $this->userRepository->find($userId))) {
            throw new BadRequestHttpException('User with id ' . $userId . ' is not exist');
        }
        foreach ($user->getLocks() as $lock) {
            $this->lockManager->unReserveLock($user, $lock);
        }
        $this->logger->info('User with id ' . $userId . ' unreserve all locks');
        return View::create($user->getLocks(), Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/lock/{lockId}/open")
     */
    public function openLock(int $lockId): View
    {
        if (!($lock = $this->lockRepository->find($lockId))) {
            throw new BadRequestHttpException('Lock with id ' . $lockId . ' is not exist');
        }
        $this->lockManager->openLock($lock);
        $this->logger->info('Lock with id ' . $lockId . ' was opened');
        return View::create($lock, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/lock/{lockId}/close")
     */
    public function close(int $lockId): View
    {
        if (!($lock = $this->lockRepository->find($lockId))) {
            throw new BadRequestHttpException('Lock with id ' . $lockId . ' is not exist');
        }
        $this->lockManager->closeLock($lock);
        $this->logger->info('Lock with id ' . $lockId . ' was closed');
        return View::create($lock, Response::HTTP_OK);
    }
}
