<?php

namespace Controller;

use Model\AdminCommentManager;
use Model\ArticleManager;

/**
 * Class ArticleController
 *
 * @package \Controller
 */
class  ArticleController extends AbstractController
{

//    show chapter and its comments on show view
    public function show(int $id)
    {
        $articleManager = new ArticleManager($this->getPdo());
        $article = $articleManager->selectOneById($id);
        $commentsManager = new AdminCommentManager($this->getPdo());
        $comments = $commentsManager->ShowAllComments($id);
        return $this->twig->render('Article/show.html.twig', ['article' => $article, 'comments'=> $comments, 'session' => $_SESSION]);
        header("Location: /article/' . $articleId");
    }

    public function index()
    {
        $articlesManager = new ArticleManager($this->getPdo());
        $articles = $articlesManager->selectAllArticles();
        return $this->twig->render('Article/indexUser.html.twig', ['articles' => $articles, 'session' => $_SESSION]);
    }


}


