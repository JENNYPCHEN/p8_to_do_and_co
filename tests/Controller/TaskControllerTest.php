<?php

namespace App\Tests\Controller;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;


class TaskControllerTest extends WebTestCase
{
    protected $client;

    public function setUp(): void
	{
		parent::setUp();
		$this->client = static::createClient();
    
	}
    
    public function testRoleUsersReadTasks()
    {
        $this->loginAUser('user');
        $this->client->request('GET', '/tasks'); 
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('a:contains("Créer une tâche")');

    }
    public function testRoleAdminReadTasks()
    {
        $this->loginAUser('admin');
        $this->client->request('GET', '/tasks'); 
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('a:contains("Créer une tâche")');
    }

    public function testVisiterReadTasks()
    {
        $this->client->request('GET', '/tasks'); 
        $this->assertResponseRedirects('http://localhost/login');
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert-danger', "Veuillez vérifier votre nom d'utilisateur/mot de passe.");
    }
    public function testCreateTaskSuccessfully()
    {
        $this->loginAUser('user');
        $crawler = $this->client->request('POST', '/tasks/create');
        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]' => 'test',
            'task[content]' => 'test',
        ]);
        $this->client->submit($form);
        $this->assertResponseRedirects('/tasks');
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', "Superbe ! La tâche a été bien ajoutée.");
    }
    public function testTaskOwnerEditTask()
    {
        $this->loginAUser('user');
        $taskId=$this->findTaskId('user');
        $crawler = $this->client->request('POST','/tasks/'.$taskId.'/edit');
        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => 'task 3 modifié',
            'task[content]' => 'task3 with user modifié',
        ]);
            $this->client->submit($form);
            $this->assertResponseRedirects('/tasks');
            $this->client->followRedirect();
            $this->assertSelectorTextContains('.alert-success', "Superbe ! La tâche a bien été modifiée.");
    }
    public function testNonTaskOwnerEditTask()
    {
        $this->loginAUser('admin');
        $taskId=$this->findTaskId('user');
        $this->client->request('POST','/tasks/'.$taskId.'/edit');
        $this->assertResponseRedirects('/tasks');
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert', "Oops ! Désolé. Vous n'avez pas le droit d'y accéder.");   
           
    }
    public function testRoleAdminEditAnonymeTask()
    {
        $this->loginAUser('admin');
        $taskId=$this->findTaskId(NULL);
        $crawler = $this->client->request('POST','/tasks/'.$taskId.'/edit');
        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => 'task 1 modifié par admin',
            'task[content]' => 'task 1 sans author modifié par admin',
        ]);
        $this->client->submit($form);
        $this->assertResponseRedirects('/tasks');
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', "La tâche a bien été modifiée.");


    }
    public function testTaskOwnerEditToggle()
    {
        $this->loginAUser('user');
        $taskId=$this->findTaskId('user');
        $task=$this->findTask($taskId);
        $this->client->request('POST','/tasks/'.$taskId.'/toggle');
        $this->assertResponseRedirects('/tasks');
        $this->client->followRedirect();
        if($task->getIsDone()==1){
        $this->assertSelectorTextContains('.alert-success', "a bien été marquée comme faite");
        }
        if($task->getIsDone()==0){
            $this->assertSelectorTextContains('.alert-success', "a bien été marquée comme pas encore faite");
            }
    }
    public function testNonTaskOwnerEditToggle()
    {
        $this->loginAUser('user');
        $taskId=$this->findTaskId('admin');
        $this->findTask($taskId);
        $this->client->request('POST','/tasks/'.$taskId.'/toggle');
        $this->assertResponseRedirects('/tasks');
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert', "Désolé. Vous n'avez pas le droit d'y accéder.");

    }
    public function testRoleAdminEditAnonymeToggle()
    {
        $this->loginAUser('admin');
        $taskId=$this->findTaskId(NULL);
        $task=$this->findTask($taskId);
        $this->client->request('POST','/tasks/'.$taskId.'/toggle');
        $this->assertResponseRedirects('/tasks');
        $this->client->followRedirect();
        if($task->getIsDone()==1){
            $this->assertSelectorTextContains('.alert-success', "a bien été marquée comme faite");
            };
        if($task->getIsDone()==0){
            $this->assertSelectorTextContains('.alert-success', "a bien été marquée comme pas encore faite");
           }
    }
    public function testTaskOwnerDeleteTask()
    {
        $this->loginAUser('user');
        $taskId=$this->findTaskId('user');
        $this->client->request('POST','/tasks/'.$taskId.'/delete');
        $this->assertResponseRedirects('/tasks');
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', "Superbe ! La tâche a bien été supprimée.");
    }
    public function testNonTaskOwnerDeleteTask()
    {
        $this->loginAUser( 'admin');
        $taskId=$this->findTaskId('user');
        $this->client->request('POST','/tasks/'.$taskId.'/delete');
        $this->assertResponseRedirects('/tasks');
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert', "Désolé. Vous n'avez pas le droit d'y accéder.");

    }
    public function testTaskAdminDeleteAnonymeTask()
    {
        $this->loginAUser('admin');
        $taskId=$this->findTaskId(NULL);
        $this->client->request('POST','/tasks/'.$taskId.'/delete');
        $this->assertResponseRedirects('/tasks');
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', "Superbe ! La tâche a bien été supprimée.");
    }
    private function loginAUser($username){
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username'=> $username]);
        $this->client->loginUser($testUser);
    }
    private function findTaskId($username){
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['username'=> $username]);
        $taskRepository= static::getContainer()->get(TaskRepository::class);
        $taskId=$taskRepository->findOneBy(['author'=>$testUser])->getId();
        return $taskId;
    }

    private function findTask($taskId){
        $taskRepository= static::getContainer()->get(TaskRepository::class);
        $taskId=$taskRepository->findOneBy(['id'=>$taskId]);
        return $taskId;
    }
}
