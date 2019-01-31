<?php

namespace App\Controller;

use App\Repository\LockerRequestRepository;

class LockerRequestController extends AbstractApiController
{
    /** @var LockerRequestRepository */
    private $lockerRequestRepository;

    public function __construct(LockerRequestRepository $lockerRequestRepository)
    {
        $this->lockerRequestRepository = $lockerRequestRepository;
    }
}
