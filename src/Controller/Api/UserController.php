<?php

namespace App\Controller\Api;


use App\Entity\User;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class UserController  extends AbstractController
{
    
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
    public function edit(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, UserPasswordHasherInterface $userPasswordHasher, ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(User::class)->find($id);
        
        
         //dd($user); 
        $content = $request->getContent(); // Get json from request
        
        $updateUser = $serializer->deserialize($content, User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);
        //dd($updateUser);
        //$hashedPassword = $userPasswordHasher->hashPassword($updateUser, $updateUser->getPassword() );
        // On écrase le mot de passe en clair par le mot de passe haché
        //$updateUser->setPassword($hashedPassword); 
        $entityManager->flush();

        return new JsonResponse([
            'success_message' => 'Profil user mis à jour.'
        ]);
    }


    /**
     * Create profilpic file 
     * 
     * @Route ("/api/secure/user/new/{id}/pic", name="api_user_create_pic", methods={"GET","POST"})
     * 
     */
    public function createProfilPicFile(Request $request, ValidatorInterface $validator, User $user, ManagerRegistry $doctrine, FileUploader $fileUploader)
    {
        $profilPic = $request->files->get('profilPic');

        if (!$profilPic) {
            throw new BadRequestHttpException('"profilPic" is required');
        }

        $errors = $validator->validate($profilPic, []);

        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $destination = $this->getParameter('kernel.project_dir').'/public/uploads/images/user';

        $imageFileName = $fileUploader->upload($profilPic);

        $user->setProfilPic($imageFileName);
        $profilPic->move($destination,$imageFileName);
        $em = $doctrine->getManager();
        $em->flush();

        return new JsonResponse([
            'success_message' => 'profilPic upload'
          ]);


    }
}
