<?php

namespace App\Controller\Api;

use App\Entity\Order;
use App\Entity\Patient;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

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
     * @Route ("/api/secure/order/new/", name="api_order_create", methods={"GET","POST"})
     * 
     */
    public function createOrder(int $id, Patient $patient, Request $request, EntityManagerInterface $em, ManagerRegistry $doctrine, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        
        // Récupérer le contenu JSON
        $jsonContent = $request->getContent();
        
        //dd($newOrder);
        try {
            // Désérialiser (convertir) le JSON en entité Doctrine Order
            $patient = $serializer->deserialize($jsonContent, Patient::class, 'json');
            
        } catch (NotEncodableValueException $e) {
            // Si le JSON fourni est "malformé" ou manquant, on prévient le client
            return $this->json(
                ['error' => 'JSON invalide'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        

        $newOrder = new Order();
        $newOrder->setSafetyCode(0000);
        $newOrder->setStatus(0);
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
    public function createImages(Request $request, ValidatorInterface $validator) : Response
    {
         // On passe directement par l'objet Request pour récupérer l'image
         $uploadedFile = $request->files->get('prescriptionImage');

         // Exception Erreur 400 si image non présente
         if (!$uploadedFile) {
             throw new BadRequestHttpException('"prescriptionImage" is required');
         }
 
         // Optionnel (recommandé) : Validation du fichier
         $errors = $validator->validate($uploadedFile, [
             // Contrainte image
   
         ]);
 
         if (count($errors) > 0) {
             return $this->json($errors, Response::HTTP_BAD_REQUEST);
         }
 
         $destination = $this->getParameter('kernel.project_dir').'/public/uploads/images/order';
         
         
         $uploadedFile->move($destination);
         
         return new JsonResponse([
            'success_message' => 'Image upload'
          ]);
    }

}
