<?php declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\User;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserServiceTest extends TestCase {
    public function test_password_is_hashed(){
        $em = $this->createMock(EntityManagerInterface::class);
        $hasher=$this->createMock(UserPasswordHasherInterface::class);
        $service= new UserService($hasher,$em);
        $service->saveUser('12345678',$user=new User);
        $this->assertEquals($user->getPassword(), $hasher->hashPassword( $user,'12345678'));
    }
}