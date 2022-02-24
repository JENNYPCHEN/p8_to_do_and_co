<?php declare(strict_types=1);

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
	
    public function test_password_incorrect(): void
    {
     $crawler = $this->client->request('GET', '/login');
     $this->fill_form($crawler,$this->client,'user','wrongpassword');
        $this->assertResponseRedirects('http://localhost/login');
        $this->assertCount(1,[$crawler->filter('.alert.alert-danger')->count()]);
    }
   
    public function test_username_is_incorrect(): void
    {
        $crawler = $this->client->request('GET', '/login');
        $this->fill_form($crawler,$this->client,'wrongusername','12345678');
            $this->assertResponseRedirects('http://localhost/login');
            $this->assertCount(1,[$crawler->filter('.alert.alert-danger')->count()]);
        }

        public function test_login_detail_correct(): void
        {
            $crawler = $this->client->request('GET', '/login');
            $this->fill_form($crawler,$this->client,'user','12345678');
                $this->assertResponseRedirects('http://localhost/');
                $this->assertSelectorNotExists('.alert.alert-danger');
        }

        private function fill_form($crawler,$client,$username,$password){
            $form = $crawler->selectButton('Se connecter')->form([
                    '_username' => $username,
                    '_password' => $password,
                ]);
                $this->client->submit($form);
        }

    }
    
    