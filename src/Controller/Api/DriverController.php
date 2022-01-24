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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DriverController extends AbstractController
{

    /**
     * Create profil driver
     * 
     * @Route ("/api/user/driver", name="api_driver_create", methods={"GET","POST"})
     * 
     */
    public function createDriver(Request $request, EntityManagerInterface $em, ValidatorInterface $validator, UserPasswordHasherInterface $userPasswordHasher)
    {
        $user = new User();
        $user->setFirstname('test');  
        $user->setLastname('lastname');  
        $user->setEmail('email');
        $hashedPassword = $userPasswordHasher->hashPassword($user, "1234");
        // On écrase le mot de passe en clair par le mot de passe haché
        $user->setPassword($hashedPassword);  
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
    public function getDriver(Driver $driver): Response
    {
        if ($driver === null) {
            return $this->json(['error' => 'livreur non trouvé.'], 404);
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

            $driver->setLocation('Paris x');
            $driver->setVehicule('vélo');
            $driver->setStatus('1');
            $driver->setProfilPic('test.jpeg');
            $em->flush();
        
        
            return new JsonResponse([
                'success_message' => 'Profil mis à jour.'
            ]);
    }

}
