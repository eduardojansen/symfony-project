<?php


namespace App\EventListeners;

use App\Helper\EntityFactoryException;
use App\Helper\ResponseFactory;
use Doctrine\ORM\ORMException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionHandler implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [
                ['handleEntityException', 1],
                ['handle404Exception', 0],
                ['handleORMException', -1]
            ],
        ];
    }

    public function handle404Exception(ExceptionEvent $event)
    {
        if ($event->getThrowable() instanceof NotFoundHttpException) {
            $responseFactory = new ResponseFactory(
                false,
                $event->getThrowable()
                    ->getMessage(),
                Response::HTTP_NOT_FOUND,
            );

            $event->setResponse($responseFactory->getResponse());
        }
    }

    public function handleEntityException(ExceptionEvent $event)
    {
        if ($event->getThrowable() instanceof EntityFactoryException) {
            $responseFactory = new ResponseFactory(
                false,
                $event->getThrowable()
                    ->getMessage(),
                Response::HTTP_BAD_REQUEST,
            );

            $event->setResponse($responseFactory->getResponse());

        }
    }

    public function handleORMException(ExceptionEvent $event)
    {
        if ($event->getThrowable() instanceof ORMException) {
            $responseFactory = new ResponseFactory(
                false,
                $event->getThrowable()
                    ->getMessage(),
                Response::HTTP_BAD_REQUEST,
            );

            $event->setResponse($responseFactory->getResponse());

        }
    }
}