<?php

namespace App\Controller;

use App\Helper\EntityFactory;
use App\Helper\ExtractRequestData;
use App\Helper\ResponseFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $entityManager;

    /**
     * @var ObjectRepository
     */
    protected ObjectRepository $repository;
    /**
     * @var EntityFactory
     */
    protected EntityFactory $factory;
    /**
     * @var ExtractRequestData
     */
    private ExtractRequestData $extractRequestData;
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;


    public function __construct(
        ObjectRepository $repository,
        EntityManagerInterface $entityManager,
        EntityFactory $factory,
        ExtractRequestData $extractRequestData
    )
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->factory = $factory;
        $this->extractRequestData = $extractRequestData;
    }

    public function index(Request $request): Response
    {
        $filter = $this->extractRequestData->getFilter($request);
        $order = $this->extractRequestData->getOrder($request);
        [$currentPage, $perPage] = $this->extractRequestData->getPaginationData($request);
        $result = $this->repository->findBy(
            $filter,
            $order,
            $perPage,
            ($currentPage - 1) * $perPage
        );

        $responseFactory = new ResponseFactory(
            true,
            $result,
            Response::HTTP_OK,
            $currentPage,
            $perPage,
        );

        return $responseFactory->getResponse();
    }

}