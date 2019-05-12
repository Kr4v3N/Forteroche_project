<?php

namespace Controller;

use http\Env\Request;
use Model\ArticleManager;
use Model\Article;
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
        if ($_SERVER['REQUEST_URI'] != '/admin/logAdmin'){
            $this->verifyAdmin();
        }
    }

    public function showDashboard() {
        $connexionMessage = null;
        $article = "home";
        $countArticle = new ArticleManager($this->getPdo());            // connexion au pdo de l'article manager
        $numberArticles = $countArticle->count();                       // comptage du nombre d'article
        $countUsers = new UserManager($this->getPdo());                 // idem mais pour les utilisateurs
        $numberUsers = $countUsers->count();
        $countComment = new AdminCommentManager($this->getPdo());       // idem pour les commentaires
        $numberComments = $countComment->count();
        $signals = $countComment->countSignal();

        if (isset($_SESSION['admin']) && isset($_SESSION['admin']['message'])){
            $connexionMessage = $_SESSION['admin']['message'];
            unset($_SESSION['admin']['message']);
        }
        return $this->twig->render('Admin/admin_dashboard.html.twig', ['active' => $article, 'user' => $_SESSION['admin'],
            'totalArticles' => $numberArticles, 'totalUsers' => $numberUsers, 'totalComments' => $numberComments,
            'signals' => $signals, 'session' => $_SESSION, 'connexionMessage' => $connexionMessage, 'isLogged' => $this->isLoggedAdmin()]);
    }

    //show user and his comments
    public function userShow(int $id)
    {
        $userManager = new UserManager($this->getPdo());
        $user = $userManager->selectOneById($id);
        $commentManager = new AdminCommentManager($this->getPdo());
        $comment = $commentManager->selectCommentByUser($id);
        return $this->twig->render('Admin/AdminUser/adminShow.html.twig', ['user' => $user, 'comments' => $comment]);

    }

    // show all users to manage them
    public function usersIndex()
    {
        $usersManager = new UserManager($this->getPdo());
        $users = $usersManager->selectAllUsers();
        $active = "utilisateurs";
        return $this->twig->render('Admin/AdminUser/indexUsers.html.twig', ['users' => $users, 'active' => $active]);
    }

    // delete a user TODO add cascade to delete user and his comments
    public function userDelete(int $id)
    {
        $newUserManager = new UserManager($this->getPdo());
        $newUserManager->userDelete($id);

    }

    // logout for admin
    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /admin/logAdmin');
    }

    // show one article to admin in order to modify or not
    public function adminShow(int $id)
    {
        $articleManager = new ArticleManager($this->getPdo());
        $article = $articleManager->selectOneById($id);

        return $this->twig->render('Admin/AdminArticle/adminShow.html.twig', ['article' => $article]);
    }

    // show all articles for admin
    public function indexAdmin()
    {
        $articlesManager = new ArticleManager($this->getPdo());
        $articles = $articlesManager->selectAllArticles();
        $active = "articles";
        return $this->twig->render('Admin/AdminArticle/indexAdmin.html.twig', ['articles' => $articles, 'active' => $active]);
    }

    // add an article
    public function add()
    {
        $titleErr = $contentErr = '';
        $title = $content = '';
        $error = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') // affiche si
        {
            if (empty($_POST["title"])) {
                $titleErr = "Le titre est requis !";
            } elseif (empty($_POST["content"])) {
                $contentErr = "Le contenu est requis !";
            } elseif (!empty($_POST)) {
                $articleManager = new ArticleManager($this->getPdo());
                $article = new Article();
                $article->setTitle($_POST['title']);
                $article->setContent($_POST['content']);
                $article->setCategoryId($_POST['category']);
                if (!empty($_FILES)) {
                    $allowExtension = ['.jpg', '.jpeg', '.gif', '.png'];
                    $maxSize = 3000000;
                    $extension = strtolower(strrchr($_FILES['image']['name'], '.'));
                    $size = $_FILES['image']['size'];

                    if (!in_array($extension, $allowExtension)) {
                        $error['errorExt'] = 'Seuls les fichiers image .jpg, .jpeg, .gif et .png sont autorisés.';
                    }
                    if ($size > $maxSize) {
                        $error['errorSize'] = 'Votre fichier est trop volumineux. Taille maximale autorisée : 3Mo.';
                    }

                     if (!$error){
                        $filename = 'image-' . $_FILES['image']['name'];
                        move_uploaded_file($_FILES['image']['tmp_name'], '../public/assets/images/' . $filename);
                        $article->setPicture($filename);
                     }

                    $id = $articleManager->insert($article);
                    header('Location:/admin/article/' . $id);
                }
            }
        }

        $active = "add";
        return $this->twig->render('Admin/AdminArticle/add.html.twig', ["active" => $active, 'titleErr' => $titleErr, 'contentErr' => $contentErr,
            'errorFile' => $error]); // traitement
    }


    public function logAdmin()
    {
        // Si admin connecter
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

    //delete an article
    public function delete(int $id)
    {
        $articleManager = new ArticleManager($this->getPdo());
        $articleManager->delete($id);
    }

    // edit an article, change title, content, picture
    public function edit(int $id)
    {
        $titleErr = $contentErr = "";
        $error = [];
        $articleManager = new ArticleManager($this->getPdo());
        $article = $articleManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            var_dump($_POST);
            if ($_POST["title"] == '') {
                $titleErr = "Le titre est requis !";
            } elseif ($_POST["content"] == "") {
                $contentErr = "Le contenu est requis !";
            } else {
                $article->setTitle($_POST['title']);
                $article->setContent($_POST['content']);

                if (!empty($_FILES)) {
                    $allowExtension = ['.jpg', '.jpeg', '.gif', '.png'];
                    $maxSize = 3000000;
                    $extension = strtolower(strrchr($_FILES['image']['name'], '.'));
                    $size = $_FILES['image']['size'];

                    if (!in_array($extension, $allowExtension)) {
                        $error['errorExt'] = 'Seuls les fichiers image .jpg, .jpeg, .gif et .png sont autorisés.';
                    }
                    if ($size > $maxSize) {
                        $error['errorSize'] = 'Votre fichier est trop volumineux. Taille maximale autorisée : 3Mo.';
                    }

                    if (!$error) {
                        $filename = 'image-' . $_FILES['image']['name'];
                        move_uploaded_file($_FILES['image']['tmp_name'], '../public/assets/images/' . $filename);
                        $article->setPicture($filename);
                    }
                }
                header('Location: /admin/article/' . $id);
                $id = $articleManager->update($article);
            }
        }
        return $this->twig->render('Admin/AdminArticle/edit.html.twig', ['article' => $article, 'titleErr' => $titleErr, 'contentErr' => $contentErr, 'errorFile' => $error]);
    }

}

