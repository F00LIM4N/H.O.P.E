<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    private ?string $id_user;
    private string $name_user;
    private string $pswd_user;
    private $birth_user;
    private array $roles;

    public function __construct(array $userData)
    {
        $this->id_user = $userData['id_user'];
        $this->name_user = $userData['name_user'];
        $this->pswd_user = $userData['pswd_user'];
        $this->role_user = $userData['role_user'];
        $this->birth_user = $userData['birth_user'];
    }

    public function getUserIdentifier(): string
    {
        return $this->name_user;
    }

    public function getPassword(): ?string
    {
        return $this->pswd_user;
    }

    public function getBirth(): ?string
    {
        return $this->birth_user;
    }

    public function getRoles(): array
    {
        $roles = [];

        switch ($this->role_user) {
            case 1:
                $roles[] = 'ROLE_ADMIN';
                break;
            default:
                $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function eraseCredentials()
    {
        // Si vous stockez des informations sensibles, vous pouvez les effacer ici
    }
}