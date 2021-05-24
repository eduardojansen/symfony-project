<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginController extends AbstractController
{

    /**
     * @var UserRepository
     */
    private UserRepository $repository;
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $encoder;


    public function __construct(
        UserRepository $repository,
        UserPasswordEncoderInterface $encoder
    )
    {
        $this->repository = $repository;
        $this->encoder = $encoder;
    }

    /**
     * @Route("/login", name="login", methods={"POST"})
     */
    public function login(Request $request): Response
    {
        $jsonData = json_decode($request->getContent());

        if (is_null($jsonData->username ?? null) || is_null($jsonData->password ?? null)) {
            return new JsonResponse([
                'erro' => 'Favor verifique se usuário e senha estão corretos'
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->repository->findOneBy([
            'username' => $jsonData->username,
        ]);

        if (!$this->encoder->isPasswordValid($user, $jsonData->password)) {
            return new JsonResponse([
                'erro' => 'Usuário ou senha inválidos'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = JWT::encode(
            ['username' => $user->getUsername()],
            'minhachave$egurança',
            'HS256'
        );

        return new JsonResponse([
            'access_token' => $token
        ]);


    }
}
