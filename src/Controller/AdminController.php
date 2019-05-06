<?php

namespace Controller;

use Model\Article;
use Model\ArticleManager;
use Model\UserManager;

/**
 * Class AdminController
 *
 * @package \Controller
 */
class AdminController extends AbstractController
{
    public function showDashboard() {
        $article = "home";
        return $this->twig->render('Admin/admin_dashboard.html.twig', ["active" => $article]);
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
        $active = "articles";
        return $this->twig->render('Admin/AdminArticle/indexAdmin.html.twig', ['articles' => $articles, "active" => $active]);
    }

    public function usersIndex()
    {
        $usersManager = new UserManager($this->getPdo());
        $users = $usersManager->selectAllUsers();
        $active = "utilisateurs";
        return $this->twig->render('Admin/AdminUser/indexUsers.html.twig', ['users' => $users, "active" => $active]);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') // affiche si
        {
            $articleManager = new ArticleManager($this->getPdo());
            $article = new Article();
            $article->setTitle($_POST['titre']);
            $article->setContent($_POST['contenu']);
            $id = $articleManager->insert($article);
            header('Location:/admin/article/' . $id);
        }
        $active = "add";
        return $this->twig->render('Admin/AdminArticle/add.html.twig', ["active" => $active]); // traitement

        // si admin en session
        return $this->twig->render('Admin/admin_dashboard.html.twig');

        // sinon redirect vers login
    }

    public function logAdmin() {

        $errorLogin= '';

        if (!empty($_POST))
        {
            // Verifier si les données sont postées puis initialise le composant d'authentification.
            $auth = new \Model\AuthManager($this->getPdo());
            $admin = $auth->login($_POST['username'], $_POST['password']);
            if ($admin) {

                // enregistre $admin dans la session
                header('Location: /admin/dashboard');
            } else {
                // message d'erreur
                $errorLogin = 'Identifiant ou mot de passe incorrect';
            }
        }
        return $this->twig->render('Admin/logAdmin.html.twig', ['errorLogin' => $errorLogin]);
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
}

