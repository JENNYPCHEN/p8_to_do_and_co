<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\UserService;
use App\Form\UserType;


class UserController extends AbstractController
{
    protected $userRepository;
    protected $UserService;

    public function __construct(UserRepository $userRepository, UserService $UserService)
    {
        $this->userRepository = $userRepository;
        $this->UserService = $UserService;
    }

    #[Route('/users', name: 'user_list')]
    public function listAction(): Response
    {
        $user_list=$this->userRepository->findAll();

        return $this->render('user/list.html.twig', [
            'controller_name' => 'UserController',
            'users' => $user_list
        ]);
    }

    #[Route('/users/create', name: 'user_create')]
    public function createAction(Request $request): Response
    {
        $user = new User();
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $this->UserService->saveUser($userForm, $user);
            $this->addFlash('success', "L'utilisateur a bien été ajouté.");
            return $this->redirectToRoute('user_list');
        }
        return $this->render('user/create.html.twig', [
            'userForm' => $userForm->createView(),
        ]);
    }

    #[Route('/users/{id}/edit', name: 'user_edit')]
    public function editAction(User $user, Request $request)
    {
        $form = $this->createForm(UserType::class , $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->UserService->saveUser($form,$user);
            $this->addFlash('success', "L'utilisateur a bien été modifié");
            return $this->redirectToRoute('user_list');
        }
        return $this->render('user/edit.html.twig', [
            'userForm' => $form->createView(), 
            'user' => $user]);
    }
}