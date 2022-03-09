<?php declare(strict_types=1);

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Task;
use App\Entity\User;
use DateTime;
use Symfony\Component\Validator\Validation;

final class TaskTest extends TestCase {

function test_create_task(){
    $task=new Task;
    $task->setTitle('create a task title');
    $task->setContent('create a task content');
    $date = new DateTime('2001-01-01');
    $date->setTime(14, 55);
    $task->setCreatedAt($date);
    $this->assertEquals("create a task title", $task->getTitle());
    $this->assertEquals("create a task content", $task->getContent());
    $this->assertEquals($date, $task->getCreatedAt());
    $this->assertEquals(0, $task->getIsDone());
    $this->assertEquals('anoyme', $task->getAuthorName());
}
function test_create_task_with_blank_title(){
    $task =new Task;
    $task->setTitle('');
    $task->setContent('test');
    $validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
    $violations = $validator->validate($task);
    $this->assertEquals( 1,count($violations));
    
}
function test_create_task_with_blank_content(){
    $task =new Task;
    $task->setTitle('test');
    $task->setContent('');
    $validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
    $violations = $validator->validate($task);
    $this->assertEquals( 1,count($violations));

}
function test_set_toggle(){
    $task=new Task;
    $task->toggle(1);
    $this->assertEquals(1,$task->getIsDone());
}

function test_get_authorname(){
    $task=new Task;
    $user=new User;
    $user->setUsername('fakeUser');
    $task->setAuthor($user);
    $this->assertEquals('fakeUser',$task->getAuthorName());

}
}