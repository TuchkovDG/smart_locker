<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\Annotations as Rest;


class AdminController extends AbstractApiController
{
    public function __construct()
    {
    }

    /**
     * @Rest\Post("/admin/login")
     * @Rest\Options("/admin/login")
     */
    public function adminLogin(Request $request): void
    {
    }
}
