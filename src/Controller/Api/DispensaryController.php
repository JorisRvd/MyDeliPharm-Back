<?php

namespace App\Controller\Api;

use App\Entity\Address;
use App\Entity\Dispensary;
use App\Entity\Pharmacist;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DispensaryController extends AbstractController
{
    /**
     * Fonction permettant de créer une officine 
     * 
     * @Route("/api/address/dispensary/{id}", name="api_dispensary_create",methods={"POST"})
     */
    public function createDispensary( Request $request, EntityManagerInterface $em, ValidatorInterface $validator, ManagerRegistry $doctrine, SerializerInterface $serializer, Pharmacist $pharmacist)
    {
        // Récupérer le contenu JSON
        $jsonContent = $request->getContent();
        //dd($jsonContent);
        try {
            // Désérialiser (convertir) le JSON en entité Doctrine Patient
            $newDispensary = $serializer->deserialize($jsonContent, Dispensary::class, 'json');
            
        } catch (NotEncodableValueException $e) {
            // Si le JSON fourni est "malformé" ou manquant, on prévient le client
            return $this->json(
                ['error' => 'JSON invalide'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        
        $newDispensary->addPharmacist($pharmacist); 
         // Valider l'entité
        $errors = $validator->validate($newDispensary);

        // Y'a-t-il des erreurs ?
        if (count($errors) > 0) {
            // @todo Retourner des erreurs de validation propres
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        
        // On sauvegarde l'entité
        $em = $doctrine->getManager();
        $em->persist($newDispensary);
        $em->flush();
        //return new JsonResponse([
        //    'success_message' => 'Thank you for registering'
        //]);
        return $this->json($newDispensary, 201, [], 
        [
            'groups' => 'get_collection'
        ]);
    }
    /**
     * Fonction permettant d'éditer une officine 
     * 
     * @Route("/api/secure/address/dispensary/{id}", name="api_dispensary_update",methods={"PUT"})
     */
    public function editDispensary( Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        
        $dispensary = $entityManager->getRepository(Dispensary::class)->find($id);
        
        
        // dd($patient); 
        $content = $request->getContent(); // Get json from request
        
        $updateDispensary = $serializer->deserialize($content, Dispensary::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $dispensary]);
        
        
       
        
        $entityManager->flush();

        return new JsonResponse([
            'success_message' => 'Profil officine mis à jour.'
        ]);
    }
    
    /**
     * Fonction permettant d'afficher l'officine
     * 
     * @Route("/api/secure/dispensary/{id}", name="api_dispensary",methods={"GET"} )
     */
    public function getDispensary(Dispensary $dispensary = null): Response
    {
        if ($dispensary === null) {
            return $this->json(['error' => 'Officine non trouvé.'], 404);
        }
        return $this->json($dispensary, 200, [], 
        [
            'groups' => 'get_dispensary'
        ]);
    
    }
        
    /**
     * Fonction permettant de supprimer les données d'un patient 
     * @Route ("/api/secure/dispensary/{id}", name="api_dispensary_delete", methods={"POST", "DELETE"})
     */
    public function delete(ManagerRegistry $doctrine, int $id) : Response
    {
        $entityManager = $doctrine->getManager();
        $dispensary = $entityManager->getRepository(Dispensary::class)->find($id);

        if (!$dispensary) {
            throw $this->createNotFoundException(
                'No dispensary found for id '.$id
            );
        }

        $entityManager->remove($dispensary);
        $entityManager->flush();

        return new JsonResponse([
            'success_message' => 'Pharmacie supprimé.'
        ]);
    }
        
}
