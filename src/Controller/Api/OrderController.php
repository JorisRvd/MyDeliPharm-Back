<?php

namespace App\Controller\Api;

use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
            return $this->json(['error' => 'livraison non trouvé.'], 404);
        }
        return $this->json($order, 200, [], 
        [
            'groups' => 'get_order'
        ]);
    }

    /**
     * Get all order 
     * 
     * @Route("/api/order", name="api_order_all", methods={"GET"})
     */
    public function getAllOrder()
    {
        // code ...
    }

    



}
