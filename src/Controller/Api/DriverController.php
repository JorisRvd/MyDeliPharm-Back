<?php

namespace App\Controller\Api;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Driver;

class DriverController extends AbstractController
{

    /**
     * Create profil driver
     * 
     * @Route ("/api/user/driver", name="api_driver_create", methods={"GET","POST"})
     * 
     */
    public function createDriver(Request $request, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $user = new User();
        $user->setFirstname('test');  
        $user->setLastname('lastname');  
        $user->setEmail('email');
        $user->setPassword('password');   
        $user->setPhoneNumber('phoneNumber');   
        $user->setIsAdmin('isAdmin');
        $newDriver = new Driver();
        $newDriver->setLocation('Paris');
        $newDriver->setVehicule('T-Max');
        $newDriver->setProfilPic('Tmax-en-i.jpeg');
        $newDriver->setStatus('0');
        $newDriver->setUser($user);
        
        
        
        $em->persist($newDriver);
        $em->flush();
        return new JsonResponse([
            'success_message' => 'Thank you for registering'
        ]);
        
    }

    /**
     * @Route("/api/user/driver/{id}", name="api_driver",methods={"GET"})
     */
    public function getDriver(Driver $driver = null): Response
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
     * @Route("/api/user/driver/{id}", name="api_driver_edit", methods={"PUT"})
     */
    public function edit(ManagerRegistry $doctrine, int $id): Response
    {
        $em = $doctrine->getManager();
        $driver = $em->getRepository(Driver::class)->find($id);
        
        if (!$driver) {
            throw $this->createNotFoundException(
                'No driver found for id '.$id
            );
        }

            $driver->setLocation('Paris XV');
            $driver->setVehicule('T-Max');
            $driver->setStatus('1');
            $driver->setProfilPic('T-max-edit.jpeg');
            $em->flush();
        
        
            return new JsonResponse([
                'success_message' => 'Profil mis à jour.'
            ]);
    }

}
