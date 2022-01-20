<?php

namespace App\Controller\Api;

use App\Entity\Pharmacist;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PharmacistController extends AbstractController
{

    /**
     * Create profil pharmacist
     * 
     * @Route ("/api/user/pharmacist", name="api_pharmacist_create", methods={"GET","POST"})
     * 
     */
    public function createPharmacist(Request $request, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $user = new User();
        $user->setFirstname('test');  
        $user->setLastname('lastname');  
        $user->setEmail('email');
        $user->setPassword('password');   
        $user->setPhoneNumber('phoneNumber');   
        $user->setIsAdmin('isAdmin');
        $newPharmacist = new Pharmacist();
        $newPharmacist->setRppsNumber('555');
        $newPharmacist->setStatus('1');
        $newPharmacist->setProfilPic('profil_pic');
        $newPharmacist->setUser($user);
         
        $em->persist($newPharmacist);
        $em->flush();
        return new JsonResponse([
            'success_message' => 'Thank you for registering'
        ]);
        
    }


    /**
     * Get profil pharmacist
     * 
     * @Route("/api/user/pharmacist/{id}", name="api_pharmacist", methods={"GET"})
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
     * Fonction permettant de modifier les infos d'un pharmacien 
     * 
     * @Route ("/api/user/patient/{id}", name="api_patient_edit", methods={"PUT"})
     */
    public function edit(ManagerRegistry $doctrine, int $id): Response
    {
        $em = $doctrine->getManager();
        $pharmacist = $em->getRepository(Pharmacist::class)->find($id);
        
        if (!$pharmacist) {
            throw $this->createNotFoundException(
                'No pharmacist found for id '.$id
            );
        }

            $pharmacist->setRppsNumber('1212121212');
            $pharmacist->setStatus('1');
            $pharmacist->setProfilPic('testimage');
            $em->flush();
        
        
            return new JsonResponse([
                'success_message' => 'Profil mis à jour.'
            ]);
    }


}
