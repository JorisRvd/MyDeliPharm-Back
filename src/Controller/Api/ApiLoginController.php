<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\JsonResponse;


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