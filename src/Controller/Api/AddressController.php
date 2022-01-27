<?php

namespace App\Controller\Api;

use App\Entity\Address;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class AddressController extends AbstractController
{
    /**
     * Fonction permettant de créer une adresse 
     * 
     * @Route ("/api/secure/user/address/{id}", name="api_address_create", methods={"GET","POST"})
     */
    public function createAddress( Request $request, EntityManagerInterface $em, ValidatorInterface $validator, ManagerRegistry $doctrine, SerializerInterface $serializer, User $user)
    {
        // Récupérer le contenu JSON
        $jsonContent = $request->getContent();
        //dd($jsonContent);
        try {
            // Désérialiser (convertir) le JSON en entité Doctrine Patient
            $newAddress = $serializer->deserialize($jsonContent, Address::class, 'json');
            
        } catch (NotEncodableValueException $e) {
            // Si le JSON fourni est "malformé" ou manquant, on prévient le client
            return $this->json(
                ['error' => 'JSON invalide'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        
        $newAddress->setUser($user); 
         // Valider l'entité
        $errors = $validator->validate($newAddress);

        // Y'a-t-il des erreurs ?
        if (count($errors) > 0) {
            // @todo Retourner des erreurs de validation propres
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        
        // On sauvegarde l'entité
        $em = $doctrine->getManager();
        $em->persist($newAddress);
        $em->flush();
        //return new JsonResponse([
        //    'success_message' => 'Thank you for registering'
        //]);
        return $this->json($newAddress, 201, [], 
        [
            'groups' => 'get_collection'
        ]);
    }
    
    /**
     * Fonction permettant d'afficher une adresse 
     * 
     * @Route("/api/secure/address/{id}", name="api_address", methods={"GET"})
     */
    public function getAddress(Address $address = null): Response
    {
        if ($address === null) {
            return $this->json(['error' => 'Adresse non trouvé.'], 404);
        }
        return $this->json($address, 200, [], 
        [
            'groups' => 'get_address'
        ]);
    }
    
    /**
     * Fonction permettant de supprimer une adresse 
     * @Route ("/api/secure/address/{id}", name="api_address_delete", methods={"POST", "DELETE"})
     */
    public function deleteAddress(ManagerRegistry $doctrine, int $id) : Response
    {
        $entityManager = $doctrine->getManager();
        $address = $entityManager->getRepository(Address::class)->find($id);

        if (!$address) {
            throw $this->createNotFoundException(
                'No user found for id '.$id
            );
        }

        $entityManager->remove($address);
        $entityManager->flush();

        return new JsonResponse([
            'success_message' => 'Adresse supprimée.'
        ]);
    }
    
    /**
     * Fonction permettant de modifier les infos d'une adresse 
     * 
     * @Route ("/api/secure/address/{id}", name="api_address_edit", methods={"PUT"})
     */
    public function editAddress( Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        
        $address = $entityManager->getRepository(Address::class)->find($id);
        
        
        // dd($patient); 
        $content = $request->getContent(); // Get json from request
        
        $updatePatient = $serializer->deserialize($content, Address::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $address]);
        
        
       
        
        $entityManager->flush();

        return new JsonResponse([
            'success_message' => 'Profil patient mis à jour.'
        ]);
    }
}
