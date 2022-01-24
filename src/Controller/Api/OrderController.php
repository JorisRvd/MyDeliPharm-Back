<?php

namespace App\Controller\Api;

use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class OrderController extends AbstractController
{
    /**
     * Get order by id
     * 
     * @Route("/api/order/{id}", name="api_order", methods={"GET"})
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
     * @Route ("/api/order/new", name="api_order_create", methods={"GET","POST"})
     * 
     */
    public function createOrder(Request $request, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $newOrder = new Order();
        $newOrder->setPrescription('create prescription');  
        $newOrder->setSafetyCode('1212121');  
        $newOrder->setStatus('0');


        $em->persist($newOrder);
        $em->flush();
        return new JsonResponse([
            'success_message' => 'Thank you for registering'
        ]);
        
    }

    



}
