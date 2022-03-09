<?php declare(strict_types=1);

namespace App\Tests\Service;

use App\DataFixtures\TaskFixtures;
use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Service\TaskService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class TaskTest extends KernelTestCase {

    public function test_save_new_task(){
        $em = $this->createMock(EntityManagerInterface::class);
        $service= new TaskService($em);
        $task=new Task; 
        $service->saveTask($task);
        $this->assertNull($task->getUpdatedAt());
        $this->assertIsObject($task->getCreatedAt());
    }
    public function test_save_edited_task(){
        $em = $this->createMock(EntityManagerInterface::class);
        $service= new TaskService($em);
        $task= static::getContainer()->get(TaskRepository::class)->findOneBy(["title"=>"task3"]);
        $service->saveTask($task);
        $this->assertIsObject($task->getCreatedAt());
        $this->assertIsObject($task->getUpdatedAt());
    
    }
    public function test_remove_task(){
        $em = $this->createMock(EntityManagerInterface::class);
        $TaskRepository=$this->createMock(TaskRepository::class);
        $service= new TaskService($em);
        $task=new Task;
        $task->setTitle('title');
        $service->removeTask($task);
        $task= $TaskRepository->findOneBy(["title"=>"title"]);
        $this->assertNull($task);
       

    }
}