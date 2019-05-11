<?php
namespace Controller;

use Model\ArticleManager;
use Model\Article;
use Model\AdminCommentManager;

/**
 * Class ArticleController
 *
 * @package \Controller
 */
class  ArticleController extends AbstractController
{

    //    show an article and its comments on show view
    public function show(int $id)
    {
        $articleManager = new ArticleManager($this->getPdo());
        $article = $articleManager->selectOneById($id);
        $commentsManager = new AdminCommentManager($this->getPdo());
        $comments = $commentsManager->ShowAllComments($id);
        return $this->twig->render('Article/show.html.twig', ['article' => $article, 'comments'=> $comments, 'isLogged' => $this->isLogged()]);
        header("Location: /article/' . $articleId");
    }


    public function indexAccueil()
    {   $connexionMessage = null;
        $articlesManager = new ArticleManager($this->getPdo());
        $articles = $articlesManager->selectArticlesForIndex();
        if (isset($_SESSION['user']) && isset($_SESSION['user']['message'])){
            $connexionMessage = $_SESSION['user']['message'];
            unset($_SESSION['user']['message']);
        };
        return $this->twig->render('Users/index.html.twig', ['articles' => $articles, 'session' => $_SESSION,
            'connexionMessage' => $connexionMessage, 'isLogged' => $this->isLogged()]);
    }

    public function index()
    {
        $articlesManager = new ArticleManager($this->getPdo());
        $articles = $articlesManager->selectAllArticles();
        return $this->twig->render('Article/indexUser.html.twig', ['articles' => $articles, 'isLogged' => $this->isLogged()]);
    }


}
