<?php

namespace App\Controller\Api;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ApiLoginController extends AbstractController
{

    private $token;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->token = $tokenStorage;
    }


    /**
     * @Route("/api/login", name="api_login", methods={"GET","POST"})
     */
    public function login(?User $user): Response
    {
        
        //dd($user); 
        if (null === $user) {
            return $this->json([
                'message' => 'missing credentials',
                ], Response::HTTP_UNAUTHORIZED);
            }
            
           
               
        return $this->json([
            'user' => $user->getUserIdentifier(),
            
            
            
        ]);
    }


    /**
     * @Route("/api/user/me", name="get_me")
     */
    public function getMe(): ?User
    {
        $token = $this->tokenStorage->getToken();

        if (!$token) {
            return null;
        }
    
        $user = $token->getUser();
    
        if (!$user instanceof User) {
            return null;
        }
    
        return $user;
    }

}