<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Entity\Task;
use App\Service\TaskService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class TaskController extends AbstractController
{
    private $taskRepository;
    private $taskService;
    public function __construct(TaskRepository $taskRepository, TaskService $taskService)
    {
        $this->taskRepository = $taskRepository;
        $taskService=$this->taskService = $taskService;

    }


    #[Route('/tasks', name: 'task_list')]
    #[IsGranted('ROLE_USER')]
    public function listAction(): Response
    {
        $task_list = $this->taskRepository->findBy( ['isDone' => '0']);
                
        return $this->render('task/list.html.twig', [
            'tasks' => $task_list,
        ]);
    }

    #[Route('/tasks/create', name: 'task_create')]
    #[IsGranted('ROLE_USER')]
    public function createAction(Request $request)
    {
       
        $task = new Task();
        $task->setAuthor($this->getUser());
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->taskService->saveTask($task);
            $this->addFlash('success', 'La tâche a été bien ajoutée.');
            return $this->redirectToRoute('task_list');
        }
        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/tasks/{id}/edit', name: 'task_edit')]
    #[IsGranted('ROLE_USER')]
    public function editAction(Task $task, Request $request)
    {
      if (!$this->isGranted('TASK_EDIT', $task)) {
        $this->addFlash('error', "Désolé. Vous n'avez pas le droit d'y accéder.");
        return $this->redirectToRoute('task_list');
       }
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
    #[IsGranted('ROLE_USER')]
    public function toggleTaskAction(Task $task)
    {
       if (!$this->IsGranted('TASK_EDIT',$task)) {
            $this->addFlash('error', "Désolé. Vous n'avez pas le droit d'y accéder.");
            return $this->redirectToRoute('task_list');
           }
        $task->getIsDone()==0?$task->toggle(1):$task->toggle(0);
        $this->taskService->saveTask($task);
        if($task->getIsDone()==1){
        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));
        }
        if($task->getIsDone()==0){
        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme pas encore faite.', $task->getTitle()));
        }return $this->redirectToRoute('task_list');
    }
    #[Route('/tasks/{id}/delete', name: 'task_delete')]
    public function deleteTaskAction(Task $task)
    {
     if (!$this->isGranted('TASK_DELETE', $task)) {
        $this->addFlash('error', "Désolé. Vous n'avez pas le droit d'y accéder.");
        return $this->redirectToRoute('task_list');
       }
      $this->taskService->removeTask($task);
      $this->addFlash('success', 'La tâche a bien été supprimée.');
      return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/isDone', name: 'task_isDone')]
    #[IsGranted('ROLE_USER')]
    public function isDoneTask()
    {
        $task_list = $this->taskRepository->findBy( ['isDone' => '1']);
             
        return $this->render('task/list.html.twig', [
            'tasks' => $task_list,
        ]);
    }
}
