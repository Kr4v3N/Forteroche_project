<?php

namespace Controller;

use Model\ArticleManager;
use Model\Article;
use Model\AuthManager;
use Model\User;
use Model\UserManager;
use Model\AdminCommentManager;

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
        if ($_SERVER['REQUEST_URI'] != '/admin/logAdmin') {
            $this->verifyAdmin();
        }
    }

    public function showDashboard()
    {
        $connexionMessage = null;
        $article = 'home';
        $countArticle = new ArticleManager($this->getPdo());            // connexion au pdo de l'article manager
        $numberArticles = $countArticle->count();                       // comptage du nombre d'article
        $countUsers = new UserManager($this->getPdo());                 // idem mais pour les utilisateurs
        $lastArticles = new ArticleManager($this->getPdo());
        $lastArticles = $lastArticles->selectArticlesForIndex();
        $numberUsers = $countUsers->count();
        $countComment = new AdminCommentManager($this->getPdo());       // idem pour les commentaires
        $numberComments = $countComment->count();
        $lastUsers = new UserManager($this->getPdo());                 // idem mais pour les utilisateurs
        $lastUsers = $lastUsers->selectUsersForIndex();
        $lastComments = new AdminCommentManager($this->getPdo());       // idem pour les commentaires
        $lastComments = $lastComments->selectCommentsForIndex();
        $signals = $countComment->countSignal();

        if (isset($_SESSION['admin']) && isset($_SESSION['admin']['message'])) {
            $connexionMessage = $_SESSION['admin']['message'];
            unset($_SESSION['admin']['message']);
        }
        return $this->twig->render('Admin/admin_dashboard.html.twig', ['active' => $article, 'user' => $_SESSION['admin'],
            'totalArticles' => $numberArticles, 'totalUsers' => $numberUsers, 'totalComments' => $numberComments,
            'session' => $_SESSION, 'connexionMessage' => $connexionMessage, 'isLogged' => $this->isLoggedAdmin(),
            'signals' => $signals, 'lastArticles', 'lastarticles' => $lastArticles, 'lastusers' => $lastUsers, 'lastcomments' => $lastComments,
        ]);
    }

    //show one article to admin in order to modify or not
    public function adminShow(int $id)
    {
        $articleManager = new ArticleManager($this->getPdo());
        $article = $articleManager->selectOneById($id);

        return $this->twig->render('Admin/AdminArticle/adminShow.html.twig', ['article' => $article]);
    }

    //add an article
    public function add()
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') // shows if

        {
            $articleManager = new ArticleManager($this->getPdo());
            $article = new Article();
            if (empty($_POST['title'])) {
                $errors['title'] = 'Le titre est requis !';
            }
            if (empty($_POST['content'])) {
                $errors['content'] = 'Le contenu est requis !';
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
                if (($size > $maxSize) || ($size === 0)) {
                    $errors['image'] = 'Votre fichier est trop volumineux. Taille maximale autorisée : 1Mo.';
                }
            }

            if (empty($errors)) {
                $filename = 'image-' . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], '../public/assets/images/' . $filename);
                $filenameMin = 'image-' . $_FILES['imageMin']['name'];
                move_uploaded_file($_FILES['imageMin']['tmp_name'], '../public/assets/images/' . $filenameMin);
                $article->setUserId($_SESSION['admin']['id']);
                $article->setTitle($_POST['title']);
                $article->setContent($_POST['content']);
                $article->setCategoryId($_POST['category']);
                $article->setPicture($filename);
                $id = $articleManager->insert($article);
                header('Location:/admin/article/' . $id);
            }
        }
        $active = 'add';
        return $this->twig->render('Admin/AdminArticle/add.html.twig', ['active' => $active, 'errors' => $errors, 'content' => $_POST]); // traitement
    }

    //edit an article, change title, content, picture
    /**
     * @param int $id
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function edit(int $id)
    {
        $errors = [];

        $articleManager = new ArticleManager($this->getPdo());
        $article = $articleManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST['title'] === '') {
                $errors['title'] = 'Le titre est requis !';
            }
            if ($_POST['content'] === '') {
                $errors['content'] = 'Le contenu est requis !';
            }
            if (!empty($_FILES['image']['name'])) {
                $allowExtension = ['.jpg', '.jpeg', '.gif', '.png'];
                $maxSize = 1000000;
                $extension = strtolower(strrchr($_FILES['image']['name'], '.'));
                $size = $_FILES['image']['size'];
                if (!in_array($extension, $allowExtension)) {
                    $errors['image'] = 'Seuls les fichiers image .jpg, .jpeg, .gif et .png sont autorisés.';
                }
                if (($size > $maxSize) || ($size === 0)) {
                    $errors['image'] = 'Votre fichier est trop volumineux. Taille maximale autorisée : 1Mo.';
                }
                if(!$errors){
                    $filename = 'image-' . $_FILES['image']['name'];
                    move_uploaded_file($_FILES['image']['tmp_name'], '../public/assets/images/' . $filename);
                    $article->setPicture($filename);
                }
            }
            if(empty($errors)) {
                $article->setTitle($_POST['title']);
                $article->setContent($_POST['content']);
                header('Location: /admin/article/' . $id);
                $id = $articleManager->update($article);
            }
        }
        return $this->twig->render('Admin/AdminArticle/edit.html.twig', [
            'article' => $article,
            'errors' => $errors,
            'content' => $_POST]);
    }

    //delete an article
    public function delete(int $id)
    {
        $articleManager = new ArticleManager($this->getPdo());
        $articleManager->delete($id);
    }

    //show all articles for admin
    public function indexAdmin()
    {
        $articlesManager = new ArticleManager($this->getPdo());
        $articles = $articlesManager->selectAllArticles();
        $active = 'articles';
        return $this->twig->render('Admin/AdminArticle/indexAdmin.html.twig', [
            'articles' => $articles,
            'active' => $active]);
    }

    //connexion administrator
    public function logAdmin()
    {

        //if admin is connected
        if (isset($_SESSION['admin'])) {
            header('Location: /admin/dashboard');
            exit();
        }

        $errorLogin = null;

        if (!empty($_POST)) {
            //Check if the data is posted and then initialize the authentication component.
            $auth = new AuthManager($this->getPdo());
            $admin = $auth->login($_POST['email']);

            if ($admin) {

                if (password_verify($_POST['password'], $admin->getPass())) {
                    //If password ok, creation session admin with lastname, firstname, and email.
                    $_SESSION['admin'] = [
                        'id' => $admin->getId(),
                        'lastname' => $admin->getlastname(),
                        'firstname' => $admin->getFirstname(),
                        'email' => $admin->getEmail(),
                        'message' => 'Vous êtes connecté'
                    ];

                    header('Location: /admin/dashboard');
                } else {
                    $errorLogin = 'Identifiant incorrect';
                }
            } else {
                $errorLogin = 'Identifiant incorrect';
            }
        }
        return $this->twig->render('Admin/logAdmin.html.twig', ['errorLogin' => $errorLogin]);
    }

    //logout for admin
    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /admin/logAdmin');
    }

    public function addUser()
    {

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') //displays if
        {
            if (empty($_POST['firstname'])) {
                $errors['firstname'] = 'le nom est requis';
            }
            if (empty($_POST['lastname'])) {
                $errors['lastname'] = 'Le prénom est requis !';
            }
            if (empty($_POST['email'])) {
                $errors['email'] = "L'email est requis !";
            }
            if (empty($_POST['password'])) {
                $errors['password'] = 'Le mot de passe est requis !';
            }
            if (empty($_POST['status'])) {
                $errors['status'] = 'Le status est requis !';
            }
            if (empty($errors))
            {
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

        $active = 'add';
        return $this->twig->render('Admin/AdminUser/addUser.html.twig', [
            'active' => $active,
            'errors' => $errors,
            'nameErr' => $_POST]); //treatment
    }

    //show user and his comments
    public function userShow(int $id)
    {
        $userManager = new UserManager($this->getPdo());
        $user = $userManager->selectUserById($id);
        $commentManager = new AdminCommentManager($this->getPdo());
        $comment = $commentManager->selectCommentByUser($id);
        return $this->twig->render('Admin/AdminUser/adminShow.html.twig', [
            'user' => $user,
            'comments' => $comment]);
    }

    //show all users to manage them
    public function usersIndex()
    {
        $usersManager = new UserManager($this->getPdo());
        $users = $usersManager->selectAllUsers();
        $active = 'utilisateurs';
        return $this->twig->render('Admin/AdminUser/indexUsers.html.twig', [
            'users' => $users,
            'active' => $active]);
    }

    //delete a user
    public function userDelete(int $id)
    {
        $newUserManager = new UserManager($this->getPdo());
        $newUserManager->userDelete($id);
    }

}







