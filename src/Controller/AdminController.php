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
        $article = "home";
        return $this->twig->render('Admin/admin_dashboard.html.twig', ["active" => $article, "user" => $_SESSION['admin']]);
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
        $articles = $articlesManager->selectAllArticlesAndCategory();
        $active = "articles";
        return $this->twig->render('Admin/AdminArticle/indexAdmin.html.twig', ['articles' => $articles, 'active' => $active] );
    }

//    public function usersIndex()
//    {
//        $usersManager = new UserManager($this->getPdo());
//        $users = $usersManager->selectAllUsers();
//        $active = 'utilisateurs';
//        return $this->twig->render('Admin/AdminUser/indexUsers.html.twig', ['users' => $users, 'active' => $active]);
//    }

//    public function userShow(int $id)
//    {
//        $userManager = new UserManager($this->getPdo());
//        $user = $userManager->selectOneById($id);
//
//        return $this->twig->render('Admin/AdminUser/adminShow.html.twig', ['user' => $user]);
//    }

    public function add()
    {
        $titleErr = $contentErr = '';
        $title = $content = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') // affiche si
        {   if (empty($_POST["title"])) {
            $titleErr = "Le titre est requis !";
        } elseif (empty($_POST["content"])) {
            $contentErr = "Le contenu est requis !";
        }
        else
        {
            $articleManager = new ArticleManager($this->getPdo());
            $article = new Article();
            $article->setTitle($_POST['title']);
            $article->setContent($_POST['content']);
            $article->setCategoryId($_POST['category']);
            $id = $articleManager->insert($article);
            header('Location:/admin/article/' . $id);
        }
        }
        $active = "add";
        return $this->twig->render('Admin/AdminArticle/add.html.twig', ["active" => $active, 'titleErr'=> $titleErr, 'contentErr' => $contentErr ]); // traitement

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
            $auth = new AuthManager($this->getPdo());
            $admin = $auth->login($_POST['email']);


            if ($admin) {

                if (password_verify($_POST['password'], $admin->getPass())) {
                    //Si password ok, creation session admin avec lastname, firstname, et email.
                    $_SESSION['admin'] = [
                        "lastname" => $admin->getlastname(),
                        "firstname" => $admin->getFirstname(),
                        "email" => $admin->getEmail(),
                    ];

                    header('Location: /admin/dashboard');
                }
            }
            else {
                $errorLogin = 'Identifiant incorrect';
            }


        }
        return $this->twig->render('Admin/logAdmin.html.twig', ["errorLogin" => $errorLogin]);


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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $article->setTitle($_POST['title']);
            $article->setContent($_POST['content']);
            $articleManager->update($article);
            header('Location: /admin/articles');
        }
        return $this->twig->render('Admin/AdminArticle/edit.html.twig', ["article" => $article]);
    }


}
