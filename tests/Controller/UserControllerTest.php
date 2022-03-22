<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{

    protected $client;


    public function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    public function testRoleUserReadUsers()
    {
        $this->loginAUser('user');
        $this->client->request('GET', '/users');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('th:contains("1")');
        $this->assertSelectorNotExists('th:contains("2")');
        $this->assertCount( 1,['user']);
    }

    public function testRoleAdminReadUsers()
    {
        $this->loginAUser('admin');
        $this->client->request('GET', '/users');
        $this->assertResponseIsSuccessful();
        $this->assertCount( 1,['admin']);
        $this->assertCount( 1,['user']);
       // $this->assertMatchesRegularExpression('/admin/', $this->client->getResponse()->getContent());
       // $this->assertMatchesRegularExpression('/user/', $this->client->getResponse()->getContent());
    }

    public function testVisiterReadUsers()
    {
        $this->client->request('GET', '/users');
        $this->assertResponseRedirects('http://localhost/login');
    }

    public function testCreateUserSuccessfully()
    {

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
        $this->client->followRedirect();
        $this->assertSelectorExists('h1:contains("Liste des utilisateurs")');
    }
    public function testCreateUserTwoDifferentPasswords()
    {
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
    public function testRoleUserAccessEditOtherAccounts()
    {
        $this->loginAUser('user');
        $userId = $this->findUserId('admin');
        $this->client->request('GET', '/users/' . $userId . '/edit');
        $this->assertResponseRedirects('/');
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert-danger', "Désolé. Vous n'avez pas le droit d'y accéder.");
    }

    public function testRoleAdminAccessEditOtherAccounts()
    {
        $this->loginAUser('admin');
        $userId = $this->findUserId('user');
        $this->client->request('GET', '/users/' . $userId . '/edit');
        $this->assertSelectorExists('h1:contains("Modifier")');
    }

    public function testVisiterAccessEditUserPage()
    {
        $userId = $this->findUserId('user');
        $this->client->request('GET', '/users/' . $userId . '/edit');
        $this->assertResponseRedirects('http://localhost/login');
    }
    public function testEditUserSuccessfully()
    {
        $this->loginAUser('admin');
        $userId = $this->findUserId('user');
        $crawler = $this->client->request('GET', '/users/' . $userId . '/edit');
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
    private function loginAUser($username)
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username' => $username]);
        $this->client->loginUser($testUser);
    }
    private function findUserId($username)
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $userId = $userRepository->findOneBy(['username' => $username])->getId();
        return $userId;
    }
}
