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
}
