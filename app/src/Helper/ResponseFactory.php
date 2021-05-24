<?php


namespace App\Helper;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseFactory
{
    private bool $success;
    private $content;
    private ?int $currentPage;
    private ?int $perPage;
    private int $httpCode;

    public function __construct(
        bool $success,
        $content,
        int $httpCode = Response::HTTP_OK,
        int $currentPage = null,
        int $perPage = null
    )
    {
        $this->success = $success;
        $this->content = $content;
        $this->currentPage = $currentPage;
        $this->perPage = $perPage;
        $this->httpCode = $httpCode;
    }

    public function getResponse(): JsonResponse
    {
        $responseContent = [
            'success' => $this->success,
            'currentPage' => $this->currentPage,
            'perPage' => $this->perPage,
            'data' => $this->content
        ];

        if (is_null($this->currentPage)) {
            unset($responseContent['currentPage']);
            unset($responseContent['perPage']);

        }

        return new JsonResponse($responseContent, $this->httpCode);
    }
}