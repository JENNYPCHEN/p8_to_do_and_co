<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

class UserService
{

    private $userPasswordHasher;
    private $entityManager;
    public function __construct(UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager)
    {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->entityManager = $entityManager;
    }
    public function saveUser($plainpassword, $user)
    {
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $plainpassword
            )
        );
        $this->entityManager->persist($user);
        $this->entityManager->flush();
     
    }

}
