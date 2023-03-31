<?php

namespace App\Controller;

use App\Entity\User;

// use Symfony\Bridge\Doctrine\ManagerRegistry;

// use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManagerInterface;
// use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterController extends AbstractController
{
    #[Route('/api/register', name: 'api_register', methods:'POST')]
    public function apiRegister(Request $request, ManagerRegistry $doctrine): JsonResponse
    {

        // $token = $request->headers->get('Authorization');
        // $token = str_replace('Bearer ', '', $token);

        $data = json_decode($request->getContent(), true);

        // $errors = $this->validateRegistrationData($data);
        // if(count($errors) > 0){
        //     return new JsonResponse(['success' => false, 'errors' => $errors], 400);
        // }

        // $user = new User();

        $user = new User();
        // $token = $jwtManager->create($user);
        $user->setEmail($data['username']);
        $user->setPassword(password_hash($data['password'], PASSWORD_DEFAULT));

        $em = $doctrine->getManager();
        $em->persist($user);
        $em->flush();

        return new JsonResponse(['success' => true]);
        // return new JsonResponse(['token' => $token]);
    }
}
