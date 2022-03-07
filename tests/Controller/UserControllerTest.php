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
      // $this->cleanUpUser();
	}

    public function test_role_user_read_users(){
     $this->loginAUser($this->client,'user');
        $crawler=$this->client->request('GET', '/users'); 
        $this->assertResponseIsSuccessful();
        $this->assertMatchesRegularExpression('/user/', $this->client->getResponse()->getContent());
        $this->assertSelectorExists('th:contains("1")');
        $this->assertSelectorNotExists('th:contains("2")');

    }

    public function test_role_admin_read_users(){
        $this->loginAUser($this->client,'admin');
        $crawler=$this->client->request('GET', '/users'); 
        $this->assertResponseIsSuccessful();
        $this->assertMatchesRegularExpression('/admin/', $this->client->getResponse()->getContent());
        $this->assertMatchesRegularExpression('/user/', $this->client->getResponse()->getContent());

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
    }
    public function test_create_user_two_different_passwords(){
        $crawler = $this->client->request('POST', '/users/create');
        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'test',
            'user[email]' => 'add@test.com',
            'user[plainPassword][first]' => '12345678',
            'user[plainPassword][second]' => '87654321',
            'user[roles]' => "ROLE_ADMIN",
        ]);
        $this->client->submit($form);
        $this->assertSelectorExists('li:contains("Les deux mots de passe doivent correspondre.")');
    }
    public function test_role_user_access_edit_other_accounts(){
      
        $this->loginAUser($this->client,'user');
        $userId=$this->findUserId($this->client,'admin');
        $crawler = $this->client->request('GET', '/users/'.$userId.'/edit');
        $this->assertResponseRedirects('/');
        $this->client->followRedirect();
		$this->assertSelectorTextContains('.alert-danger', "Désolé. Vous n'avez pas le droit d'y accéder.");
    }

    public function test_role_admin_access_edit_other_accounts(){
        $this->loginAUser($this->client,'admin');
        $userId=$this->findUserId($this->client,'user');
        $crawler = $this->client->request('GET', '/users/'.$userId.'/edit');
        $this->assertSelectorExists('h1:contains("Modifier")');
    }

    public function test_visiter_access_edit_user_page(){
        $userId=$this->findUserId($this->client,'user');
        $crawler = $this->client->request('GET', '/users/'.$userId.'/edit');
        $this->assertResponseRedirects( 'http://localhost/login');
        
    }
    public function test_edit_user_successfully(){
        $this->loginAUser($this->client, 'admin');
        $userId=$this->findUserId($this->client,'user');
        $crawler = $this->client->request('GET', '/users/'.$userId.'/edit');
        $form = $crawler->selectButton('Sauvegarder')->form([
            'user[email]' => 'edit@edit.com',
            'user[plainPassword][first]' => '12345678',
            'user[plainPassword][second]' => '12345678',
        ]);
        $this->client->submit($form);
        $this->assertResponseRedirects('/users');
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', "Superbe ! L'utilisateur a bien été modifié");        

    }
    private function loginAUser($client,$username){
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username'=> $username]);
        $client->loginUser($testUser);
    }
    private function findUserId($client,$username){
        $userRepository = static::getContainer()->get(UserRepository::class);
        $userId = $userRepository->findOneBy(['username'=> $username])->getId();
        return $userId;

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