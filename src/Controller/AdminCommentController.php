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
        $CommentManager = new AdminCommentManager($this->getPdo());
        $comment = new Comment();
        $comment->setContent($_POST['content']);
        $comment->setArticleId($articleId);
        $id = $CommentManager->insert($comment);
        header('Location:/article/' . $articleId);
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
