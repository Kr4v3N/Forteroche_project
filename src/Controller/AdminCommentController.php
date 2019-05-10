<?php

namespace Controller;

use Model\AdminCommentManager;
use Model\Comment;

/**
 * Class AdminCommentController
 *
 * @package \Controller
 */
class AdminCommentController extends AbstractController
{
// TODO add construct for user's connection
    public function add(int $articleId)
    {
        $errorConnexion ='';

        if (isset($_SESSION['user'])) {
            $CommentManager = new AdminCommentManager($this->getPdo());
            $comment = new Comment();
            $comment->setContent($_POST['content']);
            $comment->setArticleId($articleId);
// TODO modify user value with session start
            $comment->setUserId($_SESSION['user']['id']);
            $id = $CommentManager->insert($comment);
            header('Location: /article/' . $articleId);
        }else{
            $errorConnexion = 'Vous devez être connecté pour commenter cet article.';
            $retour = $_SERVER['HTTP_REFERER'];
            return $this->twig->render('Article/logToComment.html.twig', ['errorConnexion' => $errorConnexion, 'retour' => $retour]);
            // TODO renvoyer sur la page précédente après connexion
        }
    }

    public function indexAdminComments()
    {
        $commentsManager = new AdminCommentManager($this->getPdo());
        $comments = $commentsManager->selectAllComments();
        $active = "comments";
        return $this->twig->render('Admin/AdminComment/indexAdminComment.html.twig', ['comments' => $comments, 'active' => $active ]);
    }

    public function delete(int $id)
    {
        $commentManager = new AdminCommentManager($this->getPdo());
        $commentManager->delete($id);
    }
}
