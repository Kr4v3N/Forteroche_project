<?php

namespace Controller;

use Model\UserManager;
use Model\User;
use App\Session;
use http\Env\Request;

/**
 * Class UserController
 *
 * @package \Controller
 */
class UserController extends AbstractController
{
    public function __construct()
    {
        parent:: __construct();
        if ($_SERVER['REQUEST_URI'] != '/login'){
            $this->verifyUser();
        }
    }

    public function logoutUser()
    {
        session_start();
        session_destroy();
        header('Location: index.php?dc=ok');
    }

    public function userShow(int $id)
    {
        $userManager = new UserManager($this->getPdo());
        $user = $userManager->selectOneById($id);
        return $this->twig->render('AdminUser/show.html.twig', ['user' => $user]);
    }

    public function usersIndex()
    {
        $usersManager = new UserManager($this->getPdo());
        $users = $usersManager->selectAllUsers();
        return $this->twig->render('AdminUser/indexUsers.html.twig', ['users' => $users]);
    }

    public function userDelete(int $id)
    {
        $userManager = new UserManager($this->getPdo());
        $userManager->userDelete($id);
    }

    public function suscribeUser()
    {
        $errorRegister = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // appeler le manager
            $userManager = new UserManager($this->getPdo());
            if (!preg_match("/^[a-zA-Z ]*$/",$_POST['lastname']))
            {
                $errorRegister['lastname'] = "Seul les lettres et espaces sont autorisés." ;
            }
            if (strlen($_POST['lastname']) < 2 || strlen($_POST['lastname']) > 15)
            {
                $errorRegister['lastname'] = "Le nom doit comporter entre 2 et 15 caractères";
            }
            if (!preg_match("/^[a-zA-Z ]*$/",$_POST['firstname']))
            {
                $errorRegister['firstname'] = "Le prénom doit comporter seulement des lettres et espaces.";
            }
            if (strlen($_POST['firstname']) < 2 || strlen($_POST['firstname']) > 15)
            {
                $errorRegister['firstname'] = "Le prénom doit comporter entre 2 et 15 caractères";
            }
            if (!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $_POST['email']))
            {
                $errorRegister['email'] = "Mauvais format de votre adresse email";
            }
            // Vérifie que l'email qu'on envoi n'est pas en base de donnée
            if ($userManager->existUser($_POST['email']))
            {
                $errorRegister['email'] = "L'adresse email est déja utilisé.";
            }
            if (strlen($_POST['password']) < 8 )
            {
                $errorRegister['password'] = "Le mot de passe doit comporter au minimum 8 caractères";
            }
            if ($_POST['password'] !== ($_POST['password_control']))
            {
                $errorRegister['password'] = "Les mots de passe saisis ne sont pas identiques.";
            }
            if (empty($errorRegister))
            {
                $newUser = new User;
                $newUser->setLastname($_POST['lastname']);
                $newUser->setFirstname($_POST['firstname']);
                $newUser->setEmail($_POST['email']);
                $newUser->setPass($_POST['password']);
                $id = $userManager->suscribe($newUser);
                // TODO Renvoyer vers le bonne page
                header('Location: /articles');
            }
        }
        return $this->twig->render('signUp.html.twig', ["errorRegister" => $errorRegister]); // traitement
    }

    public function logUser()
    {
        // Si user connecter
        if (isset($_SESSION['user'])) {
            //TODO Renvoyer vers l'index
            header('Location: /articles');
            exit();
        }
        $errorLoginUser = "";
        if (!empty($_POST)) {
            // appeler le manager
            $auth = new UserManager($this->getPdo());
            $user = $auth->loginUser($_POST['email']);
            if ($user) {
                if (password_verify($_POST['password'], $user->getPass())) {
                    // Si password ok, creation session user avec lastname, firstname, et email.
                    $_SESSION['user'] = [
                        "id" => $user->getId(),
                        "lastname" => $user->getlastname(),
                        "firstname" => $user->getFirstname(),
                        "email" => $user->getEmail(),
                        "message" => 'Vous êtes connecté'
                    ];
                    // Message
//                    $this->twig->addGlobal('session', $_SESSION);
//                    $this->session->setFlash('Vous êtes connecté !','success');
                    // TODO Renvoyer vers l'index
                    header('Location: /articles');
                }else{
                    $errorLoginUser = 'Identifiants incorrects ';
                }
            }
            else {
                $errorLoginUser = 'Identifiants incorrects';
            }
        }
        return $this->twig->render('loginUser.html.twig', ['errorLoginUser' => $errorLoginUser]);
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /login');
    }
}
