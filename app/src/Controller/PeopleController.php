<?php

namespace App\Controller;

use App\Helper\ExtractRequestData;
use App\Helper\PersonFactory;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class PeopleController extends BaseController
{
    public function __construct(
        EntityManagerInterface $entityManager,
        PersonFactory $factory,
        PersonRepository $repository,
        ExtractRequestData $extractRequestData,
        LoggerInterface $logger
    )
    {
        parent::__construct($repository, $entityManager, $factory, $extractRequestData, $logger);
    }


}