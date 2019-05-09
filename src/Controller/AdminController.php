<?php

namespace Controller;

use Model\Article;
use Model\ArticleManager;
use Model\UserManager;
use http\Env\Request;
use Model\AuthManager;

/**
 * Class AdminController
 *
 * @package \Controller
 */
class AdminController extends AbstractController
{
    public function __construct()
    {
        parent:: __construct();
        if ($_SERVER['REQUEST_URI'] != '/admin/logAdmin'){
            $this->verifyAdmin();
        }
    }


    public function showDashboard() {
        $article = 'home';
        return $this->twig->render('Admin/admin_dashboard.html.twig', ['active' => $article, 'user' => $_SESSION['admin']]);
    }


    public function adminShow(int $id)
    {
        $articleManager = new ArticleManager($this->getPdo());
        $article = $articleManager->selectOneById($id);

        return $this->twig->render('Admin/AdminArticle/adminShow.html.twig', ['article' => $article]);
    }

    public function indexAdmin()
    {
        $articlesManager = new ArticleManager($this->getPdo());
        $articles = $articlesManager->selectAllArticles();
        $active = 'articles';
        return $this->twig->render('Admin/AdminArticle/indexAdmin.html.twig', ['articles' => $articles, 'active' => $active]);
    }

    public function usersIndex()
    {
        $usersManager = new UserManager($this->getPdo());
        $users = $usersManager->selectAllUsers();
        $active = 'utilisateurs';
        return $this->twig->render('Admin/AdminUser/indexUsers.html.twig', ['users' => $users, 'active' => $active]);
    }

    public function userShow(int $id)
    {
        $userManager = new UserManager($this->getPdo());
        $user = $userManager->selectOneById($id);

        return $this->twig->render('Admin/AdminUser/adminShow.html.twig', ['user' => $user]);
    }

    public function add()
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') // affiche si
        {   $articleManager = new ArticleManager($this->getPdo());
            $article = new Article();
            if (empty($_POST["title"])) {
                $errors["title"] = "Le titre est requis !";
            }

            if (empty($_POST["content"])) {
                $errors["content"] = "Le contenu est requis !";
            }
            if (empty($_FILES['image']['name'])) {
                $errors['image'] = 'Ajoutez une image';
            } elseif (!empty($_POST) && !empty($_FILES['image'])){
                $allowExtension = ['.jpg', '.jpeg', '.gif', '.png'];
                $maxSize = 1000000;
                $extension = strtolower(strrchr($_FILES['image']['name'], '.'));
                $size = $_FILES['image']['size'];
                if (!in_array($extension, $allowExtension)) {
                    $errors['image'] = 'Seuls les fichiers image .jpg, .jpeg, .gif et .png sont autorisés.';
                }
                if (($size > $maxSize) || ($size == 0)) {
                    $errors['image'] = 'Votre fichier est trop volumineux. Taille maximale autorisée : 1Mo.';
                }
                if(empty($errors)) {

                }

            }
            if (empty($_FILES['imageMin']['name'])) {
                $errors['imageMin'] = 'Ajoutez une miniature';
            } elseif (!empty($_POST) && !empty($_FILES['imageMin'])) {
                // TODO show message when miniature error
                $allowExtension = ['.jpg', '.jpeg', '.gif', '.png'];
                $maxSize = 1000000;
                $extension = strtolower(strrchr($_FILES['image']['name'], '.'));
                $size = $_FILES['imageMin']['size'];

                if (!in_array($extension, $allowExtension)) {
                    $errors['imageMin'] = 'Seuls les fichiers image .jpg, .jpeg, .gif et .png sont autorisés.';
                }
                if (($size > $maxSize) || ($size == 0)) {
                    $errors['imageMin'] = 'Votre fichier est trop volumineux. Taille maximale autorisée : 1Mo.';
                }
            }


            if (empty($errors)) {
                $filename = 'image-' . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], '../public/assets/images/image-' . $filename);
                $filenameMin = 'image-' . $_FILES['imageMin']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], '../public/assets/images/' . $filenameMin);
                $article->setUserId($_SESSION['admin']['id']);
                $article->setTitle($_POST['title']);
                $article->setContent($_POST['content']);
                $article->setCategoryId($_POST['category']);
                $article->setMiniature($filenameMin);
                $article->setPicture($filename);
                $id = $articleManager->insert($article);
                header('Location:/admin/article/' . $id);
            }

        }
        $active = "add";
        return $this->twig->render('Admin/AdminArticle/add.html.twig', ["active" => $active, 'errors' => $errors, 'content' => $_POST]); // traitement
    }

    public function logAdmin()
    {

        // Si admin connecté
        if (isset($_SESSION['admin'])) {
            header('Location: /admin/dashboard');
            exit();
        }

        $errorLogin = '';

        if (!empty($_POST)) {
            // Verifier si les données sont postées puis initialise le composant d'authentification.
            $auth = new \Model\AuthManager($this->getPdo());
            $admin = $auth->login($_POST['email']);


            if ($admin) {

                if (password_verify($_POST['password'], $admin->getPass())) {
                    //Si password ok, creation session admin avec lastname, firstname, et email.
                    $_SESSION['admin'] = [
                        'lastname' => $admin->getlastname(),
                        'firstname' => $admin->getFirstname(),
                        'email' => $admin->getEmail(),
                    ];

                    header('Location: /admin/dashboard');
                }
            }
            else {
                $errorLogin = 'Identifiant incorrect';
            }
        }
        return $this->twig->render('Admin/logAdmin.html.twig', ['errorLogin' => $errorLogin]);


    }

    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /admin/logAdmin');
    }

    public function delete(int $id)
    {
        $articleManager = new ArticleManager($this->getPdo());
        $articleManager->delete($id);

    }

    public function edit(int $id)
    {
        $articleManager = new ArticleManager($this->getPdo());
        $article = $articleManager->selectOneById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $article->setTitle($_POST['title']);
            $article->setContent($_POST['content']);
//            if (!empty($_FILES['image']){
//                $article->setPicture($_FILES['image']['name']);
//            }
            $articleManager->update($article);
            header('Location: /admin/articles');
        }
        return $this->twig->render('Admin/AdminArticle/edit.html.twig', ["article" => $article]);
    }


}

