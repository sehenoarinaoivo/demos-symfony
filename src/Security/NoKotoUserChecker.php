<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

class NoKotoUserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        $this->checkAuth($user);
    }
    public function checkPostAuth(UserInterface $user)
    {
        $this->checkAuth($user);
    }
    private function checkAuth(UserInterface $user)
    {
        if (!$user instanceof User) {
            return;
        }
        if (str_contains($user->getUsername(), 'Koto')) {
            // throw new CustomUserMessageAccountStatusException('Désolée mais y a pas de Koto ici !');
        }
    }
}