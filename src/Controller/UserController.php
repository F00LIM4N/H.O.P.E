<?php

namespace App\Controller;

use App\Manager\UserManager;
use App\Security\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\NativePasswordHasher;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    private UserManager $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

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

        // Crée un utilisateur avec un rôle par défaut
        $user = new User([
            'id_user' => $id_user,
            'name_user' => $identite,
            'pswd_user' => $hashedPswd,
            'role_user' => 2, // ROLE_STUDENT par défaut
            'birth_user' => $birth,
        ]);

        // Connexion automatique après inscription
        $security->login($user);

        return $this->redirectToRoute('app_profil');
    }

    #[Route('/profil', name: 'app_profil')]
    public function profil(Security $security): Response
    {
        $user = $security->getUser();

        // Si l'utilisateur n'est pas connecté, redirection vers la page de connexion
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $roles = $user->getRoles();

        // Redirection en fonction du rôle de l'utilisateur
        if (in_array('ROLE_TEACHER', $roles)) {
            return $this->redirectToRoute('app_profil_teacher');
        } elseif (in_array('ROLE_STUDENT', $roles)) {
            return $this->redirectToRoute('app_profil_student');
        }

        // Si aucun rôle spécifique n'est trouvé, redirection vers la page d'accueil
        return $this->redirectToRoute('app_home');
    }

    // Profil enseignant
    #[Route('/profil/enseignant', name: 'app_profil_teacher')]
    public function profilTeacher(Security $security): Response
    {
        $user = $security->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('profil/teacher.html.twig', [
            'user' => $user,
        ]);
    }

    // Profil étudiant
    #[Route('/profil/etudiant', name: 'app_profil_student')]
    public function profilStudent(Security $security): Response
    {
        $user = $security->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('profil/student.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/home', name: 'app_home')]
    public function home(Security $security): Response
    {
        $user = $security->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $roles = $user->getRoles();

        // Redirection vers la page de profil selon le rôle
        if (in_array('ROLE_TEACHER', $roles)) {
            return $this->redirectToRoute('app_profil_teacher');
        } elseif (in_array('ROLE_STUDENT', $roles)) {
            return $this->redirectToRoute('app_profil_student');
        }

        return $this->redirectToRoute('app_login');
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): Response
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
