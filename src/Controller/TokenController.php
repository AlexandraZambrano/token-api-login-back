<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
// use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\HttpFoundation\Cookie;
// use Symfony\Component\HttpFoundation\RedirectResponse;


use App\Repository\UserRepository;

use App\Entity\User;
use Symfony\Component\Security\Http\Attribute\CurrentUser;


class TokenController extends AbstractController
{

    #[Route('/token', name: 'app_token', methods: ['GET'])]
    public function index(Request $request, UserRepository $userRepository, ManagerRegistry $doctrine): Response
    {

        $em = $doctrine->getManager();

        if ($request->query->get('bearer')) {
            $token = $request->query->get('bearer');
        } else {
            return $this->redirectToRoute('app_login');
        }

        $tokenParts = explode(".", $token);
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);

        $user = $userRepository->findOneByEmail($jwtPayload->username);

        $response = new Response();
        $response->setContent(json_encode([
            'auth' => 'ok',
            'userId' => $user->getId(),
            'username' => $user->getUsername()
        ]));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('pass', 'ok');
        $response->headers->set('userId', $user->getId());
        $response->headers->set('username', $user->getUsername());
        $response->headers->setCookie(new Cookie('Authorization', $token));
        $response->headers->setCookie(new Cookie('BEARER', $token));
        // dump($response);
        // die;
        return $response;
    }
}