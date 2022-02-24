<?php declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

final class DefaultControllerTest extends WebTestCase
{
	protected $client;
	
	public function setUp(): void
	{
		parent::setUp();
		$this->client = static::createClient();
    
	}
    public function test_visiter_homepage(){
        $this->client->request('GET', '/'); 
        $this->assertSelectorTextContains('button', 'Se connecter');
    }
    public function test_logged_in_user_homepage(){
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username'=> 'user']);
        $this->client->loginUser($testUser);
        $this->client->request('GET', '/'); 
        $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");

    }
}