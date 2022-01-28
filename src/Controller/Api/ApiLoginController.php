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
     * @Route("/api/user/me", name="get_me", methods={"GET","POST"})
     */
    public function newTokenAction(Request $request)
    {
        $user = $this->getDoctrine()
            ->getRepository('User')
            ->findOneBy(['email' => $request->getUser()]);
            if (!$user) {
                throw $this->createNotFoundException();
            }
            
            $isValid = $this->get('security.password_encoder')
            ->isPasswordValid($user, $request->getPassword());
        if (!$isValid) {
            throw new BadCredentialsException();
        }

        $token = $this->get('lexik_jwt_authentication.encoder')
            ->encode([
                'username' => $user->getUsername(),
                'exp' => time() + 3600 // 1 hour expiration
            ]);

            return new JsonResponse(['token' => $token]);
    }
}