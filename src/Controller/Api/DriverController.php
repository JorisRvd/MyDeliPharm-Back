<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Driver;

class DriverController extends AbstractController
{
    /**
     * @Route("/api/driver/{id}", name="api_driver")
     */
    public function getDriver(Driver $driver): Response
    {
        if ($driver === null) {
            return $this->json(['error' => 'livraison non trouvé.'], 404);
        }
        return $this->json($driver, 200, [], 
        [
            'groups' => 'get_driver'
        ]);
    }

    /**
     * Edit profil Driver
     * 
     * @Route("/api/driver/{id}", name="api_driver_edit", methods={"GET","POST"})
     */
    public function edit()
    {
        // code...
    }

    /**
     * Delete profil Driver
     * 
     * @Route("/api/driver/{id}", name="api_driver_delete", methods={"DELETE"})
     */
    public function delete()
    {
        // code...
    }
}
