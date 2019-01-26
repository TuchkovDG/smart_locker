<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractApiController
{
    /** @var UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Rest\Post("/user")
     */
    public function createUser(Request $request): View
    {
        $user = new User();
        $this->handleRequest($user, $request);
        $this->userRepository->save($user);
        return View::create($user, Response::HTTP_CREATED);
    }

    /**
     * @Rest\Get("/user/{userId}")
     */
    public function getUser(int $userId): View
    {
        $user = $this->userRepository->find($userId);
        return View::create($user, Response::HTTP_OK);
    }

    /**
     * @Rest\Delete("/user/{userId}")
     */
    public function deleteUser(int $userId): View
    {
        if (!($user = $this->userRepository->find($userId))) {
            throw new EntityNotFoundException('User with id ' . $userId . ' does not exist');
        }
        $this->userRepository->delete($user);
        return View::create([], Response::HTTP_NO_CONTENT);
    }
}