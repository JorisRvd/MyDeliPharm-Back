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

class AddressController extends AbstractController
{
    /**
     * Fonction permettant de créer une adresse 
     * 
     * @Route ("/api/user/address/{id}", name="api_address_create", methods={"GET","POST"})
     */
    public function createAdress( Request $request, EntityManagerInterface $em, ValidatorInterface $validator, ManagerRegistry $doctrine, int $id)
    {
        $em = $doctrine->getManager();
        $user = $em->getRepository(User::class)->find($id); 
        $address = new Address(); 
        $address->setStreet('rue olivier de clisson'); 
        $address->setPostcode('56890'); 
        $address->setCity('Vannes'); 
        $address->setUser($user);

                
        $em->persist($address);
        $em->flush();
        return new JsonResponse([
            'success_message' => 'Adresse bien enregistrée'
        ]);
    }
    
    /**
     * Fonction permettant d'afficher une adresse 
     * 
     * @Route("/api/address/{id}", name="api_address", methods={"GET"})
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
     * @Route ("/api/address/{id}", name="api_address_delete", methods={"POST", "DELETE"})
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
     * @Route ("/api/address/{id}", name="api_address_edit", methods={"PUT"})
     */
    public function editAddress(ManagerRegistry $doctrine, int $id): Response
    {
        $em = $doctrine->getManager();
        $address = $em->getRepository(Address::class)->find($id);
        
        if (!$address) {
            throw $this->createNotFoundException(
                'No user found for id '.$id
            );
        }
        $address->setStreet('rue Jean Jaurès'); 
        $address->setPostcode('56890'); 
        $address->setCity('Vannes'); 
        
        $em->flush();

        return new JsonResponse([
            'success_message' => 'Adresse mise à jour'
        ]);
    }
}
