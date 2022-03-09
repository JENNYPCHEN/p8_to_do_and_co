<?php declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

final class HomepageControllerTest extends WebTestCase
{
	protected $client;
	
	public function setUp(): void
	{
		parent::setUp();
		$this->client = static::createClient();
    
	}
    public function testVisiterHomepage(){
        $this->client->request('GET', '/'); 
        $this->assertSelectorTextContains('button', 'Se connecter');
    }
    public function testLoggedInUserHomepage(){
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username'=> 'user']);
        $this->client->loginUser($testUser);
        $this->client->request('GET', '/'); 
        $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");

    }
}