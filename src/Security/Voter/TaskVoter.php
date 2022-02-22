<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['TASK_EDIT', 'TASK_DELETE'])
            && $subject instanceof \App\Entity\Task;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'TASK_EDIT':
                if($user===$subject->getAuthor()){
                    return true;
                }
                break;
                if($subject->getAuthor->get->getUserIdentifier()==='anonyme' && $this->security->isGranted()=='ROLE_ADMIN'){
                    return true;
                }
                break;
            case 'TASK_DELETE':
                if($user===$subject->getAuthor()){
                    return true;
                }
                break;
                if($subject->getAuthor->get->getUserIdentifier()==='anonyme' && $this->security->isGranted()=='ROLE_ADMIN'){
                    return true;
                }
                break;
        }

        return false;
    }
}
