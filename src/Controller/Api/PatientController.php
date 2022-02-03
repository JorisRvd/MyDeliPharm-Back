<?php

namespace App\Controller\Api;

use App\Entity\Order;
use App\Entity\Patient;
use App\Entity\User;
use App\Repository\OrderRepository;
use App\Repository\PatientRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PatientController extends AbstractController
{
    /**
     * Fonction permettant de créer un profil patient 
     * 
     * @Route ("/api/user/patient", name="api_patient_create", methods={"GET","POST"})
     */
    public function createPatient( Request $request, EntityManagerInterface $em, ManagerRegistry $doctrine, SerializerInterface $serializer, ValidatorInterface $validator, UserPasswordHasherInterface $userPasswordHasher)
    {
        
        // Récupérer le contenu JSON
        $jsonContent = $request->getContent();
        //dd($jsonContent);
        try {
            // Désérialiser (convertir) le JSON en entité Doctrine Patient
            $newPatient = $serializer->deserialize($jsonContent, Patient::class, 'json');
            
        } catch (NotEncodableValueException $e) {
            // Si le JSON fourni est "malformé" ou manquant, on prévient le client
            return $this->json(
                ['error' => 'JSON invalide'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        
         //hash password
         
         $hashedPassword = $userPasswordHasher->hashPassword($newPatient->getUser(),$newPatient->getUser()->getPassword());
         // On écrase le mot de passe en clair par le mot de passe haché
         $newPatient->getUser()->setPassword($hashedPassword);
         
         $newPatient->getUser()->setRoles(["ROLE_PATIENT"]); 

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
        // return $this->json($newPatient, 201, [], 
        // [
        //     'groups' => 'get_collection', 'get_patient'
        // ]);
    }

    /**
     * Fonction permettant d'éditer un patient 
     * 
     * @Route("/api/secure/user/patient/{id}", name="api_patient_edit", methods={"PUT"})
     * 
     */
    public function editPatient( Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $patient = $entityManager->getRepository(Patient::class)->find($id);
        
        
        $content = $request->getContent(); // Get json from request
        
        $updatePatient = $serializer->deserialize($content, Patient::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $patient]);
        
        
        $entityManager->flush();

        return new JsonResponse([
            'success_message' => 'Profil patient mis à jour.'
        ]);
    }

    /**
     * Fonction permettant d'accèder aux donnés du patient 
     * 
     * @Route("/api/secure/user/patient/{id}", name="api_patient", methods={"GET"})
     */
    public function getPatient(Patient $patient = null, $id): Response
    {
        if ($patient === null) {
            return $this->json(['error' => 'Patient non trouvé.'], 404);
        }
        return $this->json($patient, 200, [], 
        [
            'groups' => 'get_patient'
        ]);
    
    }
    /**
     * Create vitalcard file 
     * 
     * @Route ("/api/secure/patient/new/{id}/vital", name="api_patient_create_vital", methods={"GET","POST"})
     * 
     */
    public function createVitalfile(Request $request, ValidatorInterface $validator, Patient $patient, ManagerRegistry $doctrine, FileUploader $fileUploader) : Response
    {
         // On passe directement par l'objet Request pour récupérer l'image
         $vitalCard = $request->files->get('vitalCardFile');

         // Exception Erreur 400 si image non présente
         if (!$vitalCard) {
             throw new BadRequestHttpException('"vitalCardFile" is required');
         }
 
         // Optionnel (recommandé) : Validation du fichier
         $errors = $validator->validate($vitalCard, [
             // Contrainte image
   
         ]);
 
         if (count($errors) > 0) {
             return $this->json($errors, Response::HTTP_BAD_REQUEST);
         }
 
         $destination = $this->getParameter('kernel.project_dir').'/public/uploads/images/patient/vitalcard';
         
         $imageFileName = $fileUploader->upload($vitalCard);

         $patient->setVitalCardFile($imageFileName);
        
        
         
         $vitalCard->move($destination,$imageFileName);

         $em = $doctrine->getManager();
         
         $em->flush();

         
         return new JsonResponse([
            'success_message' => 'Vitalcard upload'
          ]);
    }
    /**
     * Create vitalcard file 
     * 
     * @Route ("/api/secure/patient/new/{id}/mutuelle", name="api_patient_create_mutuelle", methods={"GET","POST"})
     * 
     */
    public function createMutuelleFile(Request $request, ValidatorInterface $validator, Patient $patient, ManagerRegistry $doctrine, FileUploader $fileUploader) : Response
    {
         // On passe directement par l'objet Request pour récupérer l'image
         $mutuelleCard = $request->files->get('mutuelleFile');

         // Exception Erreur 400 si image non présente
         if (!$mutuelleCard) {
             throw new BadRequestHttpException('"mutuelleFile" is required');
         }
 
         // Optionnel (recommandé) : Validation du fichier
         $errors = $validator->validate($mutuelleCard, [
             // Contrainte image
   
         ]);
 
         if (count($errors) > 0) {
             return $this->json($errors, Response::HTTP_BAD_REQUEST);
         }
 
         $destination = $this->getParameter('kernel.project_dir').'/public/uploads/images/patient/mutuellecard';
         
         $imageFileName = $fileUploader->upload($mutuelleCard);

         $patient->setMutuelleFile($imageFileName);
        
        
         
         $mutuelleCard->move($destination,$imageFileName);

         $em = $doctrine->getManager();
         
         $em->flush();

         
         return new JsonResponse([
            'success_message' => 'mutuelleCard upload'
          ]);
    }
          
}
