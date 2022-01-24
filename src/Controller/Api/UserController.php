<?php

namespace App\Controller\Api;

use App\Entity\ApiUser;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class UserController  extends AbstractController
{
    /**
     * Fonction permettant d'afficher un utilisateur
     * 
     * @Route("/api/user/{id}", name="api_user", methods={"GET"})
     */
    public function getUser(User $user = null): Response
    {
        if ($user === null) {
            return $this->json(['error' => 'Patient non trouvé.'], 404);
        }
        return $this->json($user, 200, [], 
        [
            'groups' => 'get_collection'
        ]);
    
    }
    /**
     * Fonction permettant de supprimer un utilisateur
     * @Route ("/api/user/{id}", name="api_user_delete", methods={"POST", "DELETE"})
     */
    public function delete(ManagerRegistry $doctrine, int $id) : Response
    {
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$id
            );
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return new JsonResponse([
            'success_message' => 'Profil supprimé.'
        ]);
    }
    /**
     * Fonction permettant de modifier les infos d'un patient 
     * 
     * @Route ("/api/user/{id}", name="api_user_edit", methods={"PUT"})
     */
    public function edit(ManagerRegistry $doctrine, int $id): Response
    {
        $em = $doctrine->getManager();
        $user = $em->getRepository(User::class)->find($id);
        
        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$id
            );
        }

            $user->setFirstname('Stephen');  
            $user->setLastname('Curry');  
            $user->setEmail('email@gmail.com');
            $user->setPassword('password');   
            $user->setPhoneNumber('065258687458');   
            $user->setIsAdmin('isAdmin');
            $em->flush();
        
        
            return new JsonResponse([
                'success_message' => 'Profil mis à jour.'
            ]);
    }

    
}
