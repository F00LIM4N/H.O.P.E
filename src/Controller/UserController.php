<?php

namespace App\Controller;

use App\Manager\UserManager;
use App\Security\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\NativePasswordHasher;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    public function __construct(
        private UserManager $userManager,
    ){  }

    #[Route('/connexion', name: 'app_login')]
    public function connexion(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/connection.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/inscription', name: 'app_inscription')]
    public function inscription(): Response
    {
        return $this->render('user/inscription.html.twig');
    }

    #[Route('/creation', name: 'app_created', methods: ['POST'])]
    public function created(Request $request, NativePasswordHasher $passwordHasher, Security $security): Response
    {
        $identite = $request->get('name-user');
        $hashedPswd = $passwordHasher->hash($request->get('pswd-user'));
        $birth = $request->get('birth-user');

        $this->userManager->inscription($identite, $hashedPswd, $birth);
        $id_user = $this->userManager->getNewUser($identite, $birth);

        $user = new User([
            'id_user' => $id_user,
            'name_user' => $identite,
            'pswd_user' => $hashedPswd,
            'role_user' => 0,
            'birth_user' => $birth,
        ]);

        $security->login($user);

        return $this->redirectToRoute('app_home');
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): Response
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
