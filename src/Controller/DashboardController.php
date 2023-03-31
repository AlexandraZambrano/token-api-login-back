<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard', methods:"GET")]
    public function dashboard(ManagerRegistry $doctrine): Response
    {
        $user = $doctrine
        ->getRepository(User::class)
        ->findAll();
        
        foreach ($user as $users) {
            $data[] = [
                'id' => $users->getId(),
                'username' => $users->getUsername(),
            ];
        }

        $datanueva =  json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        $response = new Response($datanueva);

        $response->headers->add([
            'Content-Type' => 'application/json'
        ]);


        return $response;
    }
}
