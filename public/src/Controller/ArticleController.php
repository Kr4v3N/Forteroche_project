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

    /**
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
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

    /**
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
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

    /**
     * show an article and its comments on show view
     * @param int $id
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     *
     */
    public function show(int $id)
    {
        $articleManager = new ArticleManager($this->getPdo());
        $article = $articleManager->selectOneArticleById($id);
        $commentsManager = new AdminCommentManager($this->getPdo());
        $comments = $commentsManager->ShowAllComments($id);
        return $this->twig->render('Article/show.html.twig', [
            'article' => $article,
            'comments'=> $comments,
            'isLogged' => $this->isLogged()]);
        header("Location: /article/' . $articleId");
    }

    /**
     * @param int $id
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
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

    /**
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function mentionsLegals()
    {
        return $this->twig->render('Users/mentionsLegals.html.twig');
    }

}
