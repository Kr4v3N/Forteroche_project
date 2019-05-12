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
        if (isset($_SESSION['user']) && (!empty($_POST))) {
                $CommentManager = new AdminCommentManager($this->getPdo());
                $comment = new Comment();
                $comment->setContent($_POST['content']);
                $comment->setArticleId($articleId);
                $comment->setUserId($_SESSION['user']['id']);
                $id = $CommentManager->insert($comment);
                header('Location: /article/' . $articleId);
        }else{
            $errorConnexion = 'Vous devez être connecté pour commenter cet article.';
            $return = $_SERVER['HTTP_REFERER'];
            return $this->twig->render('Article/logToComment.html.twig', ['errorConnexion' => $errorConnexion, 'return' => $return]);
            // TODO redirection on last visited page after connexion
        }

    }

    public function indexAdminComments()
    {
        $commentsManager = new AdminCommentManager($this->getPdo());
        $comments = $commentsManager->selectAllComments();
        $active = "comments";
        return $this->twig->render('Admin/AdminComment/indexAdminComment.html.twig', ['comments' => $comments, "active" => $active] );
    }

    //Index of all reported comments
    public function indexAdminCommentsSignals()
    {
        $commentsSignals = new AdminCommentManager($this->getPdo());
        $shows = $commentsSignals->showSignal();
        return $this->twig->render('Admin/AdminComment/showCommentSignal.html.twig', ['comments' => $shows]);
    }

    public function delete(int $id)
    {
        $commentManager = new AdminCommentManager($this->getPdo());
        $commentManager->delete($id);
    }

    //To add a report to a specific comment, it is incremental
    public function addCommentSignal($id)
    {
        $commentSignal = new AdminCommentManager($this->getPdo());
        $commentSignal->addSignal($id);
    }
   //delete reports if this is not justified
    public function resetSignal($id)
    {
        $commentSignal = new AdminCommentManager($this->getPdo());
        $commentSignal->resetSignal($id);
    }

}
