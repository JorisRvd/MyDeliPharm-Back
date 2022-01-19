<?php

namespace App\Controller\Api;

use App\Entity\Pharmacist;
use App\Form\PharmacistType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PharmacistController extends AbstractController
{
    /**
     * Get profil pharmacist
     * 
     * @Route("/api/pharmacist/{id}", name="api_pharmacist", methods={"GET"})
     */
    public function getPharmacist(Pharmacist $pharmacist): Response
    {
        if ($pharmacist === null) {
            return $this->json(['error' => 'Patient non trouvé.'], 404);
        }
        return $this->json($pharmacist, 200, [], 
        [
            'groups' => 'get_pharmacist'
        ]);
        
    }

    /**
     * Edit profil pharmacist
     * 
     * @Route("/api/pharmacist/{id}", name="api_pharmacist_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Pharmacist $pharmacist, EntityManagerInterface $entityManager) : Response
    {
        $form = $this->createForm(PharmacistType::class, $pharmacist);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
        }
        
        return $this->json($pharmacist, 200, [], 
        [
            'groups' => 'get_pharmacist'
        ]);
    }


    /**
     * Delete profil pharmacist
     * 
     * @Route("/api/pharmacist/{id}", name="api_pharmacist_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Pharmacist $pharmacist, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pharmacist->getUser()->getId(), $request->request->get('_token'))) {
            $entityManager->remove($pharmacist);
            $entityManager->flush();
        }
        return $this->json($pharmacist, 200, [],
    [
        'groups' => 'get_pharmacist'
    ]);
    }
}
