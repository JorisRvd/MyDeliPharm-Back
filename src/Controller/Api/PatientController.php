<?php

namespace App\Controller\Api;


use App\Entity\Patient;
use App\Entity\User;
use App\Repository\PatientRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PatientController extends AbstractController
{
    /**
     * Fonction permettant de créer un profil patient 
     * 
     * @Route ("/api/user/patient/", name="api_patient_create", methods={"GET","POST"})
     */
    public function createPatient( Request $request, EntityManagerInterface $em, ManagerRegistry $doctrine, SerializerInterface $serializer, ValidatorInterface $validator, UserPasswordHasherInterface $userPasswordHasher, UserInterface $user)
    {
        
        // Récupérer le contenu JSON
        $jsonContent = $request->getContent();
        //dd($jsonContent);
        try {
            // Désérialiser (convertir) le JSON en entité Doctrine Patient
            $newPatient = $serializer->deserialize($jsonContent, User::class, 'json');
        } catch (NotEncodableValueException $e) {
            // Si le JSON fourni est "malformé" ou manquant, on prévient le client
            return $this->json(
                ['error' => 'JSON invalide'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        
         //hash password
         $hashedPassword = $userPasswordHasher->hashPassword($user,$user->getPassword() );
         // On écrase le mot de passe en clair par le mot de passe haché
         $newPatient->setPassword($hashedPassword);

         // Valider l'entité
        $errors = $validator->validate($newPatient);

        // Y'a-t-il des erreurs ?
        if (count($errors) > 0) {
            // @todo Retourner des erreurs de validation propres
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        
        // On sauvegarde l'entité
        $em = $doctrine->getManager();
        $em->persist($newPatient);
        $em->flush();
        return new JsonResponse([
            'success_message' => 'Thank you for registering'
        ]);
    }

    /**
     * Fonction permettant d'éditer un patient 
     * 
     * @Route("/api/user/patient/{id}", name="api_patient_edit", methods={"PUT"})
     * 
     */
    public function editPatient(Request $request, SerializerInterface $serializer, ManagerRegistry $doctrine, Patient $patient, int $id): Response
    {
        
        
        $em = $doctrine->getManager();
        
        $patient = $em->getRepository(Patient::class)->find($id);
        
        $patient = $serializer->deserialize($request->getContent(), Patient::class, 'json');
         
        $em->flush();

        return new JsonResponse([
            'success_message' => 'Profil patient mis à jour.'
        ]);
    }

    /**
     * Fonction permettant d'accèder aux donnés du patient 
     * 
     * @Route("/api/user/patient/{id}", name="api_patient", methods={"GET"})
     */
    public function getPatient(Patient $patient = null): Response
    {
        if ($patient === null) {
            return $this->json(['error' => 'Patient non trouvé.'], 404);
        }
        return $this->json($patient, 200, [], 
        [
            'groups' => 'get_collection'
        ]);
    
    }
          

}
