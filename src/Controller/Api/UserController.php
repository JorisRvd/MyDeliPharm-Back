<?php

namespace App\Controller\Api;


use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class UserController  extends AbstractController
{

    /**
     * Fonction permettant de créer un user
     * 
     * @Route ("/api/user/", name="api_create_user", methods={"GET", "POST"})
     */
    public function createUser(Request $request, EntityManagerInterface $em, ManagerRegistry $doctrine, SerializerInterface $serializer, ValidatorInterface $validator, UserPasswordHasherInterface $userPasswordHasher)
    {
        // Récupérer le contenu JSON
        $jsonContent = $request->getContent();
         
        try {
            // Désérialiser (convertir) le JSON en entité Doctrine User
            $newUser = $serializer->deserialize($jsonContent, User::class, 'json');
        } catch (NotEncodableValueException $e) {
            // Si le JSON fourni est "malformé" ou manquant, on prévient le client
            return $this->json(
                ['error' => 'JSON invalide'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        
        //hash password
        $hashedPassword = $userPasswordHasher->hashPassword($newUser, $newUser->getPassword() );
        
        // On écrase le mot de passe en clair par le mot de passe haché
        $newUser->setPassword($hashedPassword);

            // Valider l'entité
        $errors = $validator->validate($newUser);

        // Y'a-t-il des erreurs ?
        if (count($errors) > 0) {
            // @todo Retourner des erreurs de validation propres
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        
        // On sauvegarde l'entité
        $em = $doctrine->getManager();
        $em->persist($newUser);
        $em->flush();
        return new JsonResponse([
            'success_message' => 'Thank you for registering'
        ]);
}

    /**
     * Fonction permettant d'afficher un utilisateur
     * 
     * @Route("/api/secure/user/{id}", name="api_user", methods={"GET"})
     */
    public function getUserProfil(User $user = null): Response
    {
        if ($user === null) {
            return $this->json(['error' => 'Utilisateur non trouvé.'], 404);
        }
        return $this->json($user, 200, [], 
        [
            'groups' => 'get_collection', 'get_driver', 'get_patient', 'get_pharmacist'
        ]);
    
    }
    /**
     * Fonction permettant de supprimer un utilisateur
     * @Route ("/api/secure/user/{id}", name="api_user_delete", methods={"POST", "DELETE"})
     */
    public function delete(ManagerRegistry $doctrine,$id) : Response
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
     * @Route ("/api/secure/user/{id}", name="api_user_edit", methods={"PUT"})
     */
    public function edit(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, ManagerRegistry $doctrine, int $id, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $entityManager = $doctrine->getManager();
        
        $user = $entityManager->getRepository(User::class)->find($id);
        
        
        // dd($patient); 
        $content = $request->getContent(); // Get json from request
        
        $updateUser = $serializer->deserialize($content, User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);
        
        $hashedPassword = $userPasswordHasher->hashPassword($user, $user->getPassword() );
        
        // On écrase le mot de passe en clair par le mot de passe haché
        $user->setPassword($hashedPassword);
        
       
        
        $entityManager->flush();

        return new JsonResponse([
            'success_message' => 'Profil user mis à jour.'
        ]);
    }

    /**
     * Get the profil of the user authenticated 
     * 
     * @Route("/api/secure/profil", name="api_user_profil", methods={"GET"})
     */
    public function getProfil(): Response
    {
        $user = $this->getUser();
        
        return $this->json(
            // Data to serialized
            $user,
            // Status code
            200,
            // Headers
            [],
            // Groups used for the serializer
            ['groups' => 'get_collection']
        );
    }

    
}
