<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserTest extends KernelTestCase
{ 
    protected $user;
    public function setUp():void {
        $this->user=self::mock_user();
    }

    public function test_create_user()
    {
        $this->assertEquals('test', $this->user->getUsername());
        $this->assertEquals('test@user1.com', $this->user->getEmail());
        $this->assertEquals(['ROLE_ADMIN'], $this->user->getRoles());
        $this->assertEquals('123456789',$this->user->getPassword());
    }
    public function test_invalid_email_format()
    {
        $this->user->setEmail('user2');
        $this->invalid($this->user,1);
        
    }
    public function test_invalid_password_length()
    {
        $this->user->setPassword('0');
        $this->invalid($this->user,1);
    }
    public function test_blank_password()
    {
        $this->user->setPassword('');
        $this->invalid($this->user,1);
    }
    public function test_user_email_duplicate()
    {
        $this->user->setEmail('user@user.com');
        $this->invalid($this->user,1);
    }
    public function test_blank_user_email()
    {
        $this->user->setEmail('');
        $this->invalid($this->user,1);
    }
    public function test_user_name_duplicate()
    {
        $this->user->setUsername('user');
        $this->invalid($this->user,1);
    }
    public function test_blank_user_name()
    {
        $this->user->setUsername('');
        $this->invalid($this->user,2);
    }
    public function test_invalid_user_name_length()
    {
        $this->user->setUsername('xxxxxxxxxxxxxxxxxxxxxxxxxx');
        $this->invalid($this->user,1);
    }
    public function test_get_task()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user=$userRepository->findOneBy(['username'=>'user']);
        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $task=$taskRepository->findOneBy(['author'=>$user]);
        $this->assertContains($task,$user->getTasks());
    }

    private function mock_user()
    {
        $user = new user;
        $user->setUsername('test');
        $user->setEmail('test@user1.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword('123456789');
        return $user;
    }
    private function invalid($code,$count){
        self::bootKernel();
        $container = static::getContainer();
        $validator = $container->get('validator');
        $violations = $validator->validate($code);
        $this->assertEquals($count, count($violations));
    }
}
