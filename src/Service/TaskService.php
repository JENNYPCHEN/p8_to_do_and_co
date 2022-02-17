<?php

namespace App\Service;

use App\Entity\Task;
use DateTime;
use Doctrine\ORM\EntityManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Response;

class TaskService
{
    private $entityManager;
    public function __contruct(EntityManager $entityManager)
    {
        $this->untityManager = $entityManager;
    }
    public function saveTask($task)
    {
        null!==$task->getId()?$task->setUpdatedBy(new DateTime()):$task->setCreatedBy(new DateTime());
        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }
    public function removeTask ($task){
        $this->entityManager->remove($task);
        $this->entityManager->flush();
    }
}
