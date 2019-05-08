<?php

namespace Controller;

use Model\ArticleManager;

/**
 * Class ArticleController
 *
 * @package \Controller
 */
class  ArticleController extends AbstractController
{

    public function show(int $id)
    {
        $articleManager = new ArticleManager($this->getPdo());
        $article = $articleManager->selectOneById($id);

        return $this->twig->render('Article/show.html.twig', ['article' => $article]);
    }

    public function index()
    {
        $articlesManager = new ArticleManager($this->getPdo());
        $articles = $articlesManager->selectAllArticles();
        return $this->twig->render('Article/indexUser.html.twig', ['articles' => $articles]);
    }


}


