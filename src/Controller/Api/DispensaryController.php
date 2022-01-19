<?php

namespace App\Controller\Api;

use App\Entity\Dispensary;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class DispensaryController extends AbstractController
{
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
     * @Route ("/api/patient/{id}", name="api_patient_delete", methods={"POST"})
     */
    public function delete(Request $request, Dispensary $dispensary, EntityManagerInterface $entityManager,SerializerInterface $serializer) : Response
    {
        if ($this->isCsrfTokenValid('delete'. $dispensary->getAddress()->getUser()->getId(), $request->request->get('_token'))) {
            $entityManager->remove($dispensary);
            $entityManager->flush();
        }
        return $this->json(
            $dispensary,
            200,
            [],
            [
            'groups' => 'get_dispensary'
        ]
        );
    }
        
    /**
     * Fonction permettant de modifier les infos d'un patient 
     * 
     * @Route ("/api/patient/{id}", name="api_patient_edit", methods={"GET","PUT"})
     */
    public function edit(Dispensary $dispensary,Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(PatientType::class, $dispensary);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
        }
        
        return $this->json($dispensary, 200, [], 
        [
            'groups' => 'get_collection'
        ]);
    }
}
