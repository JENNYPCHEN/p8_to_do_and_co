<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Repository\UserRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * @codeCoverageIgnore
 */
class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    private $userRepository;
    public function __construct( UserRepository $userRepository) {
        $this->userRepository=$userRepository;
    }
    
    public function load(ObjectManager $manager): void
    {
        $task1=new Task;
        $task1->setTitle('task1');
        $task1->setContent('task1 without user');
        $task1->setCreatedAt(new \DateTime(sprintf('-%d days', rand(1, 50))));
        $task1->toggle(1);
        $manager->persist($task1);

        $task2=new Task;
        $task2->setTitle('task2');
        $task2->setContent('task2 created by admin');
        $task2->toggle(0);
        $task2->setCreatedAt(new \DateTime(sprintf('-%d days', rand(1, 50))));
        $task2->setAuthor($this->getReference(UserFixtures::USER1_REFERENCE));
        $manager->persist($task2);

        $task3=new Task;
        $task3->setTitle('task3');
        $task3->setContent('task3 with user');
        $task3->toggle(0);
        $task3->setCreatedAt(new \DateTime(sprintf('-%d days', rand(1, 50))));
        $task3->setAuthor($this->getReference(UserFixtures::USER2_REFERENCE));
        $manager->persist($task3);

        $manager->flush();
    }

    public function getDependencies(): array
	{
		return [
			UserFixtures::class,
		];
	}
}
