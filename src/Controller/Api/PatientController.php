<?php

namespace App\Controller\Api;

use App\Entity\Patient;
use App\Form\PatientType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;



class PatientController extends AbstractController
{
    /**
     * Fonction permettant d'accèder aux donnés du patient 
     * 
     * @Route("/api/patient/{id}", name="api_patient", methods={"GET"})
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
    /**
     * Fonction permettant de supprimer les données d'un patient 
     * @Route ("/api/patient/{id}", name="api_patient_delete", methods={"POST"})
     */
    public function delete(Request $request, Patient $patient, EntityManagerInterface $entityManager,SerializerInterface $serializer) : Response
    {
        if ($this->isCsrfTokenValid('delete'. $patient->getUser()->getId(), $request->request->get('_token'))) {
            $entityManager->remove($patient);
            $entityManager->flush();
        }
        return $this->json(
            $patient,
            200,
            [],
            [
            'groups' => 'get_collection'
        ]
        );
    }
    /**
     * Fonction permettant de modifier les infos d'un patient 
     * 
     * @Route ("/api/patient/{id}", name="api_patient_edit", methods={"GET", "POST"})
     */
    public function edit(Patient $patient,Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(PatientType::class, $patient);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();
            
            return $this->redirectToRoute("api_patient", ['id' => $patient->getId()], Response::HTTP_SEE_OTHER);
        }
 
        
    }

       

}
