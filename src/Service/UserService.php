<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Response;

class UserService
{

    private $userPasswordHasher;
    private $entityManager;
    public function __contruct(UserPasswordHasherInterface $userPasswordHasher, EntityManager $entityManager)
    {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->untityManager = $entityManager;
    }
    public function saveUser($form, $user)
    {
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            )
        );
        $this->entityManager->persist($user);
        $this->entityManager->flush();
     
    }
}
