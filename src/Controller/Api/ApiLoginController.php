<?php

namespace App\Controller\Api;

use App\Entity\ApiUser;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ApiLoginController extends AbstractController
{
    /**
     * @Route("/api/login", name="api_login", methods={"GET","POST"})
     */
    public function index(?User $user): Response
    {
        //dd('frefer'); 
        if (null === $user) {
            return $this->json([
                'message' => 'missing credentials',
                ], Response::HTTP_UNAUTHORIZED);
            }

           

        return $this->json([
            'user' => $user->getUserIdentifier(),
            
        ]);
    }
}
