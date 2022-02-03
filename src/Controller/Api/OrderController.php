<?php

namespace App\Controller\Api;

use App\Entity\Order;
use App\Entity\Patient;
use App\Service\FileUploader;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class OrderController extends AbstractController
{
    /**
     * Get order by id
     * 
     * @Route("/api/secure/order/{id}", name="api_order", methods={"GET"})
     */
    public function getOrder(Order $order): Response
    {
        if ($order === null) {
            return $this->json(['error' => 'livraison non trouvée.'], 404);
        }
        return $this->json($order, 200, [], 
        [
            'groups' => 'get_order'
        ]);
    }

    /**
     * Create new order
     * 
     * @Route ("/api/secure/order/new/{id}", name="api_order_create", methods={"GET","POST"})
     * 
     */
    public function createOrder( Patient $patient, Request $request, EntityManagerInterface $em, ManagerRegistry $doctrine, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        
        // Récupérer le contenu JSON
        $jsonContent = $request->getContent();
        
        //dd($newOrder);
        try {
            // Désérialiser (convertir) le JSON en entité Doctrine Order
            $newOrder = $serializer->deserialize($jsonContent, Order::class, 'json');
            
        } catch (NotEncodableValueException $e) {
            // Si le JSON fourni est "malformé" ou manquant, on prévient le client
            return $this->json(
                ['error' => 'JSON invalide'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        

        
        $newOrder->setSafetyCode(0000);
        $newOrder->setPatient($patient);

         // Valider l'entité
        $errors = $validator->validate($newOrder);

        // Y'a-t-il des erreurs ?
        if (count($errors) > 0) {
            // @todo Retourner des erreurs de validation propres
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        
        // On sauvegarde l'entité
        $em = $doctrine->getManager();
        $em->persist($newOrder);
        $em->flush();
        //return new JsonResponse([
        //    'success_message' => 'Thank you for registering'
        //]);
        return $this->json($newOrder, 201, [], 
        [
            'groups' => 'get_order'
        ]);
        
    }

    

    /**
     * Create new order
     * 
     * @Route ("/api/secure/order/new/{id}/image", name="api_order_create_image", methods={"GET","POST"})
     * 
     */
    public function createImages(Request $request, ValidatorInterface $validator, Order $order, ManagerRegistry $doctrine, FileUploader $fileUploader) : Response
    {
         // On passe directement par l'objet Request pour récupérer l'image
         $prescriptionImage = $request->files->get('prescriptionImage');

         // Exception Erreur 400 si image non présente
         if (!$prescriptionImage) {
             throw new BadRequestHttpException('"prescriptionImage" is required');
         }
 
         // Optionnel (recommandé) : Validation du fichier
         $errors = $validator->validate($prescriptionImage, [
             // Contrainte image
   
         ]);
 
         if (count($errors) > 0) {
             return $this->json($errors, Response::HTTP_BAD_REQUEST);
         }
 
         $destination = $this->getParameter('kernel.project_dir').'/public/uploads/images/order';
         
         $imageFileName = $fileUploader->upload($prescriptionImage);

         $order->setprescriptionImage($imageFileName);
        
        
         
         $prescriptionImage->move($destination,$imageFileName);

         $em = $doctrine->getManager();
         
         $em->flush();

         
         return new JsonResponse([
            'success_message' => 'Image upload'
          ]);
    }

    /**
     * Fonction permettant de modifier les infos d'une commande 
     * 
     * @Route ("/api/secure/order/{id}", name="api_order_edit", methods={"PUT"})
     */
    public function edit(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, ManagerRegistry $doctrine, int $id, Order $order): Response
    {
        $entityManager = $doctrine->getManager();
        
        $order = $entityManager->getRepository(Order::class)->find($id);
        
        
        // dd($patient); 
        $content = $request->getContent(); // Get json from request
        
        $updateOrder = $serializer->deserialize($content, Order::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $order]);
        
        
       
        
        $entityManager->flush();

        return new JsonResponse([
            'success_message' => 'commande mis à jour.'
        ]);
    }

}
