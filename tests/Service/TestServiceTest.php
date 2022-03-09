<?php declare(strict_types=1);

namespace App\Tests\Service;

use App\DataFixtures\TaskFixtures;
use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Service\TaskService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class TaskTest extends KernelTestCase {

    public function testSaveNewTask(){
        $em = $this->createMock(EntityManagerInterface::class);
        $service= new TaskService($em);
        $task=new Task; 
        $service->saveTask($task);
        $this->assertNull($task->getUpdatedAt());
        $this->assertIsObject($task->getCreatedAt());
    }
    public function testSaveEditedTask(){
        $em = $this->createMock(EntityManagerInterface::class);
        $service= new TaskService($em);
        $task= static::getContainer()->get(TaskRepository::class)->findOneBy(["title"=>"task3"]);
        $service->saveTask($task);
        $this->assertIsObject($task->getCreatedAt());
        $this->assertIsObject($task->getUpdatedAt());
    
    }

}