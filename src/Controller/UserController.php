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
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        parent:: __construct();
        if ($_SERVER['REQUEST_URI'] != '/login' && ($_SERVER['REQUEST_URI'] != '/logout')) {
            $this->verifyUser();
        }
    }

    /**
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function suscribeUser()
    {
        $errorRegister = [];
        $regexName = "#^([a-zàáâäçèéêëìíîïñòóôöùúûü]+(( |')[a-z]+)*)+([-]([a-z]+(( |')[a-z]+)*)+)*$#iu";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // call the manager
            $userManager = new UserManager($this->getPdo());

            if (!preg_match($regexName, $_POST['lastname']))
            {
                $errorRegister['lastname'] = "Le nom n'est pas au bon format." ;
            }
            if (strlen($_POST['lastname']) < 2 || strlen($_POST['lastname']) > 20)
            {
                $errorRegister['lastname'] = 'Le nom doit comporter entre 2 et 20 caractères.';
            }
            if (!preg_match($regexName, $_POST['firstname']))
            {
                $errorRegister['firstname'] = "Le prénom n'est pas au bon format.";
            }
            if (strlen($_POST['firstname']) < 2 || strlen($_POST['firstname']) > 20)
            {
                $errorRegister['firstname'] = 'Le prénom doit comporter entre 2 et 20 caractères.';
            }
            if (!preg_match("#^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-z]{2,4}$#", $_POST['email']))
            {
                $errorRegister['email'] = 'Mauvais format de votre adresse email.';
            }
            // verifies that the email we send is not in the database
            if ($userManager->existUser($_POST['email']))
            {
                $errorRegister['email'] = 'Cette adresse email est déjà utilisée.';
            }
            if (strlen($_POST['password']) < 8 )
            {
                $errorRegister['password'] = 'Le mot de passe doit comporter au minimum 8 caractères.';
            }
            if ($_POST['password'] !== $_POST['password_control'])
            {
                $errorRegister['password'] = 'Les mots de passe saisis ne sont pas identiques.';
            }
            if (empty($errorRegister))
            {
                $newUser = new User;
                $newUser->setLastname($this->verifyInput($_POST['lastname']));
                $newUser->setFirstname($this->verifyInput($_POST['firstname']));
                $newUser->setEmail($this->verifyInput($_POST['email']));
                $newUser->setPass($this->verifyInput($_POST['password']));

                $id = $userManager->suscribe($newUser);
//                var_dump($newUser);
//                die;
                header('Location: /login');
            }
        }

        return $this->twig->render('signUp.html.twig', [
            'errorRegister' => $errorRegister,
            'post' =>$_POST]);
    }

    /**
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function logUser()
    {

        // if user is connected
        if (isset($_SESSION['user'])) {
            header('Location: /');
            exit();
        }

        $errorLoginUser = null;

        if (!empty($_POST)) {

            // call the manager
            $auth = new UserManager($this->getPdo());
            $user = $auth->loginUser($_POST['email']);

            if ($user) {
                if (password_verify($_POST['password'], $user->getPass())) {
                    // if password ok, creation session user with lastname, firstname, and email.
                    $_SESSION['user'] = [
                        'id' => $user->getId(),
                        'lastname' => $user->getlastname(),
                        'firstname' => $user->getFirstname(),
                        'email' => $user->getEmail(),
                        'message' => 'Vous êtes connecté'
                    ];

                    var_dump($user);
                    die;
                    header('Location: /');

                } else {
                    $errorLoginUser = 'Identifiants incorrects ';

                }
            } else {
                $errorLoginUser = 'Identifiants incorrects';
            }
        }

        return $this->twig->render('loginUser.html.twig', [
            'errorLoginUser' => $errorLoginUser]);
    }

    /**
     * logout user
     */
    public function logoutUser()
    {
        session_destroy();
        header('Location: /');
    }

    /**
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function error()
    {
        return $this->twig->render('Users/error.html.twig');
    }

}

