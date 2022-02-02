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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ApiLoginController extends AbstractController
{

    /**
     * @Route("/api/login", name="api_login", methods={"GET","POST"})
     */
    public function login(): Response
    {
         
        return $this->json([
            'user' => $this->getUser() ? $this->getUser()->getId() : null]
        );
    }



   /**
     * Logout
     * 
     * @Route("/api/secure/logout", name="api_login_logout")
     */
    public function logout()
    {
        // Ce code ne sera jamais exécuté
        // le composant de sécurité va intercepter la requête avant.
        return new JsonResponse([
            'success_message' => 'Déconnexion.'
        ]);
    }
}