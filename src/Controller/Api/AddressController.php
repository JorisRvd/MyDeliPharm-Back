<?php

namespace App\Controller\Api;

use App\Entity\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class AddressController extends AbstractController
{
    /**
     * Fonction permettant d'afficher une adresse 
     * 
     * @Route("/api/address/{id}", name="api_address", methods={"GET"})
     */
    public function getAddress(Address $address): Response
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
     * @Route ("/api/address/{id}", name="api_address_delete", methods={"POST"})
     */
    public function delete(Request $request, Address $address, EntityManagerInterface $entityManager,SerializerInterface $serializer) : Response
    {
        if ($this->isCsrfTokenValid('delete'. $address->getUser()->getId(), $request->request->get('_token'))) {
            $entityManager->remove($address);
            $entityManager->flush();

            
        }
        return $this->json($address, 200, [], 
        [
            'groups' => 'get_address'
        ]);

    }
    /**
     * Fonction permettant de modifier les infos d'une adresse 
     * 
     * @Route ("/api/address/{id}", name="api_address_edit", methods={"GET","PUT"})
     */
    public function edit(Address $address,Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(PatientType::class, $address);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
        }
        
        return $this->json($address, 200, [], 
        [
            'groups' => 'get_address'
        ]);
    }
}
