<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


class LogOutController extends AbstractController
{

    #[Route('/log/out', name: 'app_log_out')]

    public function logout(TokenStorageInterface $tokenStorage, JWTTokenManagerInterface $jwtManager): JsonResponse
    {
        $token = $tokenStorage->getToken();
        if ($token) {
            $jwt = $jwtManager->decode($token->getToken());
            $jwtManager->invalidate($jwt['jti']);
            $tokenStorage->setToken(null);
        }

        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }
}