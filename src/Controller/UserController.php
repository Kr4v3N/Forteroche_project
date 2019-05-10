<?php

namespace Controller;

use Model\User;
use Model\UserManager;
use Model\AdminCommentManager;
use Model\Comment;

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
        $commentManager = new AdminCommentManager($this->getPdo());
        $comment = $commentManager->selectCommentByUser($id);
        return $this->twig->render('Admin/AdminUser/adminShow.html.twig', ['user' => $user, 'comments' => $comment]);

    }

    public function usersIndex()
    {
        $newUsersManager = new UserManager($this->getPdo());
        $newUsers = $newUsersManager->selectAllUsers();
        return $this->twig->render('Admin/AdminUser/indexUsers.html.twig', ['users' => $newUsers]);
    }

    public function userDelete(int $id)
    {
        $newUserManager = new UserManager($this->getPdo());
        $newUserManager->userDelete($id);

    }

    public function addUser()
    {
        /*$fisrtnameErr = $lastnameErr = $emailErr = $pwdErr = $statusErr = "";
        $fisrtname = $lastname = $email = $pwd = $status = "";*/

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') // affiche si
        {
            if (empty($_POST["firstname"])) {
                //$fisrtnameErr = "Le nom est requis !";
                $errors['firstname'] = "le nom est requis";
            }
            if (empty($_POST["lastname"])) {
                $errors['lastname'] = "Le prénom est requis !";
            }
            if (empty($_POST["email"])) {
                $errors['email'] = "L'email est requis !";
            }
            if (empty($_POST["password"])) {
                $errors['password'] = "Le mot de passe est requis !";
            }
            if (empty($_POST["status"])) {
                $errors['status'] = "Le status est requis !";
            }
            if (empty($errors)) {
                $newUserManager = new UserManager($this->getPdo());
                $newUser = new User;

                $newUser->setLastname($_POST['firstname']);
                $newUser->setFirstname($_POST['lastname']);
                $newUser->setEmail($_POST['email']);
                $newUser->setPass($_POST['password']);
                $newUser->setStatus($_POST['status']);
                $id = $newUserManager->userAdd($newUser);
                header('Location: /admin/users');
            }
        }

        $active = "add";
        return $this->twig->render('Admin/AdminUser/addUser.html.twig', ["active" => $active, 'errors' => $errors, 'nameErr' => $_POST]); // traitement
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
