<?php

namespace App\Controller\Api;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Driver;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class DriverController extends AbstractController
{

    /**
     * Create profil driver
     *
     * @Route ("/api/user/driver", name="api_driver_create", methods={"GET","POST"})
     *
     */
    public function createDriver(Request $request, EntityManagerInterface $em, ManagerRegistry $doctrine, SerializerInterface $serializer, ValidatorInterface $validator, UserPasswordHasherInterface $userPasswordHasher)
    {
        // Récupérer le contenu JSON
        $jsonContent = $request->getContent();
        //dd($jsonContent);
        try {
            // Désérialiser (convertir) le JSON en entité Doctrine Patient
            $newDriver = $serializer->deserialize($jsonContent, Driver::class, 'json');
        } catch (NotEncodableValueException $e) {
            // Si le JSON fourni est "malformé" ou manquant, on prévient le client
            return $this->json(
                ['error' => 'JSON invalide'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        
        //hash password
         
        $hashedPassword = $userPasswordHasher->hashPassword($newDriver->getUser(), $newDriver->getUser()->getPassword());
        // On écrase le mot de passe en clair par le mot de passe haché
        $newDriver->getUser()->setPassword($hashedPassword);

        $newDriver->getUser()->setRoles(["ROLE_DRIVER"]); 

        // Valider l'entité
        $errors = $validator->validate($newDriver);

        // Y'a-t-il des erreurs ?
        if (count($errors) > 0) {
            // @todo Retourner des erreurs de validation propres
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        
        // On sauvegarde l'entité
        $em = $doctrine->getManager();
        $em->persist($newDriver);
        $em->flush();
        //return new JsonResponse([
        //    'success_message' => 'Thank you for registering'
        //]);
        return $this->json(
            $newDriver,
            201,
            [],
            [
            'groups' => 'get_collection'
        ]
        );
    }

    /**
     * @Route("/api/secure/user/driver/{id}", name="api_driver",methods={"GET"})
     */
    public function getDriver(Driver $driver): Response
    {
        if ($driver === null) {
            return $this->json(['error' => 'livreur non trouvé.'], 404);
        }
        return $this->json(
            $driver,
            200,
            [],
            [
            'groups' => 'get_driver'
        ]
        );
    }

    /**
     * Edit profil Driver
     *
     * @Route("/api/secure/user/driver/{id}", name="api_driver_edit", methods={"PUT"})
     */
    public function editDriver( Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        
        $driver = $entityManager->getRepository(Driver::class)->find($id);
        
        
        // dd($patient);
        $content = $request->getContent(); // Get json from request
        
        $updateDriver = $serializer->deserialize($content, Driver::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $driver]);
        
        
       
        
        $entityManager->flush();

        return new JsonResponse([
            'success_message' => 'Profil livreur mis à jour.'
        ]);
    }
}