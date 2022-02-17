<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Task;
use App\Service\TaskService;

class TaskController extends AbstractController
{
    private $taskRepository;
    private $taskService;
    public function __construct(TaskRepository $taskRepository, TaskService $taskService)
    {
        $task_list=$this->taskRepository = $taskRepository;
        $taskService=$this->taskService = $taskService;
        
        return $this->render('task/list.html.twig', [
            'tasks' => '$task_list',
        ]);
    }


    #[Route('/tasks', name: 'task_list')]
    public function listAction(): Response
    {
        $task_list = $this->taskRepository->findAll();

        return $this->render('task/list.html.twig', [
            'controller_name' => 'TaskController',
        ]);
    }
    #[Route('/tasks/create', name: 'task_create')]
    public function createAction(Request $request)
    {
        $task = new Task();
        $task->setAuthor($this->getUser());
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->taskService->saveTask($task);
            $this->addFlash('success', 'La tâche a été bien été ajoutée.');
            return $this->redirectToRoute('task_list');
        }
        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/tasks/{id}/edit', name: 'task_edit')]
    public function editAction(Task $task, Request $request)
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->taskService->saveTask($task);
            $this->addFlash('success', 'La tâche a bien été modifiée.');
            return $this->redirectToRoute('task_list');
        }
        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    #[Route('/tasks/{id}/toggle', name: 'task_toggle')]
    public function toggleTaskAction(Task $task)
    {
        $task->getIsDone==1?$task->toggle(0):$task->toggle(1);
        $this->taskService->saveTask($task);
        if($task->toggle(1)){
        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));
        }
        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme pas encore faite.', $task->getTitle()));
        return $this->redirectToRoute('task_list');
    }
    #[Route('/tasks/{id}/delete', name: 'task_delete')]
    public function deleteTaskAction(Task $task)
    {
      $this->taskService->removeTask($task);
      $this->addFlash('success', 'La tâche a bien été supprimée.');
      return $this->redirectToRoute('task_list');
    }

}
