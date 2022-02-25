<?php declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase {
    protected $client;
	
	public function setUp(): void
	{
		parent::setUp();
		$this->client = static::createClient();
        $this->cleanUpUser();
	}

    public function test_role_user_read_users(){
       $this->loginUser($this->client,'user');
        $crawler=$this->client->request('GET', '/users'); 
        $this->assertResponseIsSuccessful();
        $this->assertMatchesRegularExpression('/user@user.com/', $this->client->getResponse()->getContent());
        $this->assertSelectorExists('th:contains("1")');
        $this->assertSelectorNotExists('th:contains("2")');

    }

    public function test_role_admin_read_users(){
        $this->loginUser($this->client,'admin');
        $crawler=$this->client->request('GET', '/users'); 
        $this->assertResponseIsSuccessful();
        $this->assertMatchesRegularExpression('/admin@admin.com/', $this->client->getResponse()->getContent());
        $this->assertMatchesRegularExpression('/user@user.com/', $this->client->getResponse()->getContent());

    }
    
    public function test_visiter_read_users(){
        $crawler=$this->client->request('GET', '/users'); 
        $this->assertResponseRedirects( 'http://localhost/login');
    }

    public function test_create_user_successfully(){
        
        $crawler = $this->client->request('POST', '/users/create');
        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'test',
            'user[email]' => 'test@test.com',
            'user[plainPassword][first]' => '12345678',
            'user[plainPassword][second]' => '12345678',
            'user[roles]' => "ROLE_ADMIN",
        ]);
        $this->client->submit($form);
        $this->assertResponseRedirects('/users');
        $this->assertCount(1,[$crawler->filter('.success')->count()]);
    }
    public function test_create_user_two_different_passwords(){
        $crawler = $this->client->request('POST', '/users/create');
        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'test',
            'user[email]' => 'test@test.com',
            'user[plainPassword][first]' => '12345678',
            'user[plainPassword][second]' => '87654321',
            'user[roles]' => "ROLE_ADMIN",
        ]);
        $this->client->submit($form);
        $this->assertSelectorExists('li:contains("Les deux mots de passe doivent correspondre.")');
    }
    public function test_role_user_access_edit_other_accounts(){
        $this->loginUser($this->client,'user');
        $crawler = $this->client->request('GET', '/users/2/edit');
        $this->assertResponseRedirects('/');
        $this->assertCount(1,[$crawler->filter('.alarm')->count()]);
    }

    public function test_role_admin_access_edit_other_accounts(){
        $this->loginUser($this->client,'admin');
        $crawler = $this->client->request('GET', '/users/3/edit');
        $this->assertSelectorExists('h1:contains("Modifier")');
    }

    public function test_visiter_access_edit_user_page(){
        $crawler = $this->client->request('GET', '/users/3/edit');
        $this->assertResponseRedirects( 'http://localhost/login');
        
    }
    public function test_edit_user_successfully(){
        $this->loginUser($this->client, 'admin');
        $crawler = $this->client->request('POST', '/users/3/edit');
        $form = $crawler->selectButton('Sauvegarder')->form([
            'user[email]' => 'test@test.com',
            'user[plainPassword][first]' => '12345678',
            'user[plainPassword][second]' => '12345678',
        ]);
        $this->client->submit($form);
        $this->assertResponseRedirects('/users');
        $this->assertCount(1,[$crawler->filter('.success')->count()]);


    }
    private function loginUser($client,$username){
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username'=> $username]);
        $client->loginUser($testUser);
    }
    private function cleanUpUser(){
        $userRepository = static::getContainer()->get(UserRepository::class);
        $entityManager= static::getContainer()->get(EntityManagerInterface::class);
        $user=$userRepository->findOneBy(['username'=> 'test']);
        if ($user){
            $entityManager->remove($user);
            $entityManager->flush();
        }
    }
}