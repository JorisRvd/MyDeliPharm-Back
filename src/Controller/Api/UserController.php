<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
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
     * @Route ("/api/user/{id}", name="api_user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager,SerializerInterface $serializer) : Response
    {
        if ($this->isCsrfTokenValid('delete'. $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();

            
        }
        return $this->json($user, 200, [], 
        [
            'groups' => 'get_collection'
        ]);

    }
    /**
     * Fonction permettant de modifier les infos d'un patient 
     * 
     * @Route ("/api/user/{id}", name="api_patient_edit", methods={"GET","PUT"})
     */
    public function edit(User $user,Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
        }
        
        return $this->json($user, 200, [], 
        [
            'groups' => 'get_collection'
        ]);
    }

}
