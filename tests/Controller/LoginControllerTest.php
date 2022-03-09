<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class LoginControllerTest extends WebTestCase
{
    protected $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    public function testPasswordIncorrect(): void
    {
        $crawler = $this->client->request('GET', '/login');
        $this->fillForm($crawler, $this->client, 'user', 'wrongpassword');
        $this->assertResponseRedirects('http://localhost/login');
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert-danger', "Veuillez vérifier votre nom d'utilisateur/mot de passe.");
    }

    public function testUsernameIncorrect(): void
    {
        $crawler = $this->client->request('GET', '/login');
        $this->fillForm($crawler, $this->client, 'wrongusername', '12345678');
        $this->assertResponseRedirects('http://localhost/login');
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert-danger', "Veuillez vérifier votre nom d'utilisateur/mot de passe.");
    }

    public function testLoginDetailCorrect(): void
    {
        $crawler = $this->client->request('GET', '/login');
        $this->fillForm($crawler, $this->client, 'user', '12345678');
        $this->assertResponseRedirects('http://localhost/');
        $this->client->followRedirect();
        $this->assertSelectorNotExists('.alert.alert-danger');
    }

    private function fillForm($crawler, $client, $username, $password)
    {
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => $username,
            '_password' => $password,
        ]);
        $client->submit($form);
    }
}
