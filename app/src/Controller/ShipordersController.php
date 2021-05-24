<?php

namespace App\Controller;

use App\Helper\ExtractRequestData;
use App\Helper\ShiporderFactory;
use App\Repository\ShiporderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class ShipordersController extends BaseController
{
    public function __construct(
        EntityManagerInterface $entityManager,
        ShiporderFactory $factory,
        ShiporderRepository $repository,
        ExtractRequestData $extractRequestData,
        LoggerInterface $logger
    )
    {
        parent::__construct($repository, $entityManager, $factory, $extractRequestData, $logger);
    }


}