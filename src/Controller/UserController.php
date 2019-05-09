<?php

namespace Controller;

use Model\User;
use Model\UserManager;

/**
 * Class UserController
 *
 * @package \Controller
 */
class UserController extends AbstractController
{

    public function userShow(int $id)
    {
        $userManager = new UserManager($this->getPdo());
        $user = $userManager->selectOneById($id);

        return $this->twig->render('Admin/AdminUser/adminShow.html.twig', ['user' => $user]);
    }

    public function usersIndex()
    {
        $usersManager = new UserManager($this->getPdo());
        $users = $usersManager->selectAllUsers();
        return $this->twig->render('Admin/AdminUser/indexUsers.html.twig', ['users' => $users]);
    }

    public function userDelete(int $id)
    {
        $userManager = new UserManager($this->getPdo());
        $userManager->userDelete($id);

    }

    public function suscribeUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') // affiche si
        {
            $userManager = new UserManager($this->getPdo());
            $newUser = new User();
            $newUser->setFirstname($_POST['firstname']);
            $newUser->setLastname($_POST['lastname']);
            $newUser->setEmail($_POST['email']);
            $newUser->setPass($_POST['password']);
            $id = $userManager->suscribe($newUser);
            // TODO rediriger vers page d'acceuil
            header('Location: /articles');
            /*        }elseif($newUser === false) {
                        die('Impossible d\'ajouter cet utilisateur');*/
        }
        $active = "suscribeUser";
        return $this->twig->render('signUp.html.twig', ["active" => $active]); // traitement
    }

}
