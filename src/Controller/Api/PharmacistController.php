<?php

namespace App\Controller\Api;

use App\Entity\Order;
use App\Entity\Pharmacist;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class PharmacistController extends AbstractController
{

    /**
     * Create profil pharmacist
     * 
     * @Route ("/api/user/pharmacist", name="api_pharmacist_create", methods={"GET","POST"})
     * 
     */
    public function createPharmacist(Request $request, EntityManagerInterface $em, ManagerRegistry $doctrine, SerializerInterface $serializer, ValidatorInterface $validator, UserPasswordHasherInterface $userPasswordHasher)
    {
       // Récupérer le contenu JSON
       $jsonContent = $request->getContent();
       //dd($jsonContent);
       try {
           // Désérialiser (convertir) le JSON en entité Doctrine Patient
           $newPharmacist = $serializer->deserialize($jsonContent, Pharmacist::class, 'json');
           
       } catch (NotEncodableValueException $e) {
           // Si le JSON fourni est "malformé" ou manquant, on prévient le client
           return $this->json(
               ['error' => 'JSON invalide'],
               Response::HTTP_UNPROCESSABLE_ENTITY
           );
       }
       
        //hash password
        
        $hashedPassword = $userPasswordHasher->hashPassword($newPharmacist->getUser(),$newPharmacist->getUser()->getPassword());
        // On écrase le mot de passe en clair par le mot de passe haché
        $newPharmacist->getUser()->setPassword($hashedPassword);

        $newPharmacist->getUser()->setRoles(["ROLE_PHARMACIST"]); 
        
        // Valider l'entité
       $errors = $validator->validate($newPharmacist);

       // Y'a-t-il des erreurs ?
       if (count($errors) > 0) {
           // @todo Retourner des erreurs de validation propres
           return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
       }

       
       // On sauvegarde l'entité
       $em = $doctrine->getManager();
       $em->persist($newPharmacist);
       $em->flush();
       return new JsonResponse([
           'success_message' => 'Thank you for registering'
       ]);
   
        
    }


    /**
     * Get profil pharmacist
     * 
     * @Route("/api/secure/user/pharmacist/{id}", name="api_pharmacist", methods={"GET"})
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
     * @Route ("/api/secure/user/pharmacist/{id}", name="api_pharmacist_edit", methods={"PUT"})
     */
    public function edit( Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $pharmacist = $entityManager->getRepository(Pharmacist::class)->find($id);
         
        $content = $request->getContent(); // Get json from request
        
        $updatePharmacist = $serializer->deserialize($content, Pharmacist::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $pharmacist]);
        
        
       
        
        $entityManager->flush();

        return new JsonResponse([
            'success_message' => 'Profil pharmacien mis à jour.'
        ]);
    }

     /**
     * Get profil pharmacist
     * 
     * @Route("/api/secure/user/pharmacists", name="api_pharmacists", methods={"GET"})
     */
    public function getAllPharmacist(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Pharmacist::class);
        $pharmacists = $repository->findAll();
    
        return $this->json($pharmacists, 200, [], 
        [
            'groups' => 'get_pharmacists'
        ]);
        
    }

    /**
     * Accept order
     * 
     * @Route("/api/secure/user/pharmacist/order/{id}", name="api_pharmacists_accept_order", methods={"PUT"})
     */
    public function acceptOrder(Request $request,EntityManagerInterface $entityManager, SerializerInterface $serializer,Order $order, ManagerRegistry $doctrine, int $id)
    {
        $entityManager = $doctrine->getManager();

        $pharmacist = $entityManager->getRepository(Pharmacist::class);

        $content = $request->getContent();

        $order->setPharmacist($pharmacist);
        
        $entityManager->flush();

        return new JsonResponse([
            'success_message' => 'pharmacien mis à jour.'
        ]);
    }



    /**
     * Edit order pharmacist
     * 
     * @Route("/api/secure/order/pharmacist/{order_id}", name="api_order_pharmacist", methods={"PUT"})
     * @ParamConverter("order", options={"id" = "order_id"})
     */
    public function orderPharmacist(Request $request, Pharmacist $pharmacist, SerializerInterface $serializer,EntityManagerInterface $entityManager, ManagerRegistry $doctrine, int $id)
    {
        $entityManager = $doctrine->getManager();

        $order = $entityManager->getRepository(Order::class)->find($id);

        $content = $request->getContent(); // Get json from request
        
        $updateOrder = $serializer->deserialize($content, Order::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $order]);

        $updateOrder->setPharmacist();

        // $entityManager->flush();

        return new JsonResponse([
            'success_message' => 'Order associé.'
        ]);
    }


}
