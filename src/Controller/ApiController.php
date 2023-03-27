<?php

namespace App\Controller;
use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class LoginController
{
    #[Route('/apis/login', name: 'api_login', methods:'POST')]
    public function login(Request $request, UserPasswordEncoderInterface $encoder, JWTTokenManagerInterface $jwtManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $username = $data['username'] ?? null;
        $password = $data['password'] ?? null;

        if (!$username || !$password) {
            throw new BadCredentialsException();
        }

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $username]);

        if (!$user) {
            throw new BadCredentialsException();
        }

        $isValid = $encoder->isPasswordValid($user, $password);

        if (!$isValid) {
            throw new BadCredentialsException();
        }

        $token = $jwtManager->create($user);

        return new JsonResponse(['token' => $token]);
    }
}
