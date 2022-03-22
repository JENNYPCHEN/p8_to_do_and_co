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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\FormLoginAuthenticator;

class UserController extends AbstractController
{

    protected $userRepository;
    protected $UserService;
    protected $authenticator;
    protected $userAuthenticator;


    public function __construct(UserRepository $userRepository, UserService $UserService, FormLoginAuthenticator $authenticator, UserAuthenticatorInterface $userAuthenticator)
    {
        $this->userRepository = $userRepository;
        $this->UserService = $UserService;
        $this->authenticator=$authenticator;
        $this->userAuthenticator=$userAuthenticator;

    }
    #[Route('/users', name: 'user_list')]
    #[IsGranted("ROLE_USER")]
    public function listAction(): Response
    {  
       $current_user=$this->getUser()->getRoles();
       if ($current_user=== ['ROLE_ADMIN']){
        $user_list=$this->userRepository->findAll();
    } 
       if($current_user=== ['ROLE_USER']) {
           $user_list=$this->userRepository->findOneBy(['username'=>$this->getUser()->getUserIdentifier()]);
       }
    
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
            $this->UserService->saveUser($userForm->get('plainPassword')->getData(), $user);
            $this->userAuthenticator->authenticateUser($user,$this->authenticator, $request );
            $this->addFlash('success', "L'utilisateur a bien été ajouté.");
            return $this->redirectToRoute('user_list');
        } 
        return $this->render('user/create.html.twig', [
            'userForm' => $userForm->createView(),
        ]);
    }

    #[Route('/users/{id}/edit', name: 'user_edit')]
    #[IsGranted('ROLE_USER')]
    public function editAction(User $user, Request $request)
    {
        $current_user=$this->getUser();
        if ($current_user->getRoles()!==['ROLE_ADMIN']) {
            if ($user!==$current_user){
        $this->addFlash('error', "Désolé. Vous n'avez pas le droit d'y accéder.");
        return $this->redirectToRoute('homepage');
            }
        }
        $form = $this->createForm(UserType::class , $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->UserService->saveUser($form->get('plainPassword')->getData(),$user);
            $this->addFlash('success', "L'utilisateur a bien été modifié");
            return $this->redirectToRoute('user_list');
        }
        return $this->render('user/edit.html.twig', [
            'userForm' => $form->createView(), 
            'user' => $user]);
    }
}