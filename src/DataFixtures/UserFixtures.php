<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @codeCoverageIgnore
 */
class UserFixtures extends Fixture 
{
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    public const USER1_REFERENCE =  '1';
    public const USER2_REFERENCE = '2';
    
    public function load(ObjectManager $manager): void
    {
        $user1=new User;
        $user1->setUsername('admin');
        $user1->setEmail('admin@admin.com');
        $user1->setRoles(['ROLE_ADMIN']);
        $password = $this->hasher->hashPassword($user1, '12345678');
        $user1->setPassword($password);
        $manager->persist($user1);

        $user2=new User;
        $user2->setUsername('user');
        $user2->setEmail('user@user.com');
        $user2->setRoles(['ROLE_USER']);
        $password = $this->hasher->hashPassword($user2, '12345678');
        $user2->setPassword($password);
        $manager->persist($user2);

        $manager->flush();
        $this->addReference(self::USER1_REFERENCE, $user1);
        $this->addReference(self::USER2_REFERENCE, $user2);
     
    }
}
