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
        $errorLogin = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') // affiche si
            if($_POST['password'] == ($_POST['password_control']))
            {
                $userManager = new UserManager($this->getPdo());
                $newUser = new User ;
                $newUser->setFirstname($_POST['firstname']);
                $newUser->setLastname($_POST['lastname']);
                $newUser->setEmail($_POST['email']);
                $newUser->setPass($_POST['password']);
                $id = $userManager->suscribe($newUser);
                // TODO rediriger vers page d'acceuil
                header('Location: /articles');
            } else{
                $errorLogin = 'Les mots de passe saisis ne sont pas identiques.';
            }
        return $this->twig->render('signUp.html.twig', ["errorLogin" => $errorLogin]); // traitement
    }

}
