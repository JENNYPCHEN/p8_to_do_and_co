<?php

namespace App\Service;

use App\Entity\Task;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Response;

class TaskService
{
    private $entityManager;
    public function __construct( EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function saveTask($task)
    {
       $task->getId()!==null?$task->setUpdatedAt(new DateTime()):$task->setCreatedAt(new DateTime());
        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }
    public function removeTask ($task){
        $this->entityManager->remove($task);
        $this->entityManager->flush();
    }
}
