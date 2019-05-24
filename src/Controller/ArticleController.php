<?php

namespace Controller;

use Model\ArticleManager;
use Model\AdminCommentManager;

/**
 * Class ArticleController
 *
 * @package \Controller
 */
class  ArticleController extends AbstractController
{

    public function indexAccueil()
    {
        $connexionMessage = null;

        $articlesManager = new ArticleManager($this->getPdo());
        $articles = $articlesManager->selectArticlesForIndex();

        if (isset($_SESSION['user']) && isset($_SESSION['user']['message'])){
            $connexionMessage = $_SESSION['user']['message'];
            unset($_SESSION['user']['message']);
        }
        return $this->twig->render('Users/index.html.twig', [
            'articles' => $articles,
            'session' => $_SESSION,
            'connexionMessage' => $connexionMessage,
            'isLogged' => $this->isLogged()]);
    }

    public function index()
    {
        $articlesManager = new ArticleManager($this->getPdo());
        $articles = $articlesManager->selectAllArticles();
        $category = $articlesManager->selectCategory();
        return $this->twig->render('Article/indexUser.html.twig', [
            'articles' => $articles,
            'isLogged' => $this->isLogged(),
            'category' => $category]);
    }

    // show an article and its comments on show view
    public function show(int $id)
    {
        $articleManager = new ArticleManager($this->getPdo());
        $article = $articleManager->selectOneById($id);
        $commentsManager = new AdminCommentManager($this->getPdo());
        $comments = $commentsManager->ShowAllComments($id);
        return $this->twig->render('Article/show.html.twig', [
            'article' => $article,
            'comments'=> $comments,
            'isLogged' => $this->isLogged()]);
        header("Location: /article/' . $articleId");
    }

    public function showbycat(int $id)
    {
        $articlesManager = new ArticleManager($this->getPdo());
        $articles = $articlesManager->selectArticlesByCategory($id);
        $category = $articlesManager->selectCategory();
        return $this->twig->render('Article/tri.html.twig', [
            'articles' => $articles,
            'session' => $_SESSION,
            'category' => $category]);
    }

    public function mentionsLegals()
    {
        return $this->twig->render('Users/mentionsLegals.html.twig');
    }

}
