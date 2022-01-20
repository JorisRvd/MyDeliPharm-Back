<?php

namespace App\Controller\Api;

use App\Entity\Address;
use App\Entity\Dispensary;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DispensaryController extends AbstractController
{
    /**
     * Fonction permettant de créer une officine 
     * 
     * @Route("/api/address/dispensary/{id}", name="api_dispensary_create",methods={"POST"})
     */
    public function createDispensary( Request $request, EntityManagerInterface $em, ValidatorInterface $validator, ManagerRegistry $doctrine, int $id)
    {
        $em = $doctrine->getManager();
        $address = $em->getRepository(Address::class)->find($id); 
        $dispensary = new Dispensary(); 
        $dispensary->setStatus(1); 
        $dispensary->setOther('ca fonctionne'); 
        $dispensary->setOpeningHours('peut être mais la flemme'); 
        $dispensary->setAddress($address);

                
        $em->persist($dispensary);
        $em->flush();
        return new JsonResponse([
            'success_message' => 'Officine bien enregistrée'
        ]);
    }
    /**
     * Fonction permettant d'éditer une officine 
     * 
     * @Route("/api/address/dispensary/{id}", name="api_dispensary_update",methods={"PUT"})
     */
    public function editDispensary(ManagerRegistry $doctrine, int $id): Response
    {
        $em = $doctrine->getManager();
        $dispensary = $em->getRepository(Dispensary::class)->find($id);
        
        if (!$dispensary) {
            throw $this->createNotFoundException(
                'No user found for id '.$id
            );
        }
        $dispensary->setStatus(1); 
        $dispensary->setOther('ca fonctionne'); 
        $dispensary->setOpeningHours('flemme'); 
        
        $em->flush();

        return new JsonResponse([
            'success_message' => 'Officine mise à jour'
        ]);
    }
    
    /**
     * Fonction permettant d'afficher l'officine
     * 
     * @Route("/api/dispensary/{id}", name="api_dispensary",methods={"GET"} )
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
     * @Route ("/api/dispensary/{id}", name="api_dispensary_delete", methods={"POST", "DELETE"})
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
