<?php

namespace Model;

/**
 * Class AdminCommentManager
 *
 * @package \Model
 */
class AdminCommentManager extends AbstractManager
{
    const TABLE = 'comment';
    public function __construct(\PDO $pdo)
    {
        parent::__construct(self::TABLE, $pdo);
    }

    //insert a comment while not logged in user
    public function insert(Comment $comment): int
    {
        $statement = $this->pdo->prepare("INSERT INTO $this->table (date, content, article_id)
        VALUES (DATE(NOW()), :content, :article_id)");
        $statement->bindValue(':content', $comment->getContent(),\PDO::PARAM_STR);
        $statement->bindValue(':article_id', $comment->getArticleId(), \PDO::PARAM_INT);
        if ($statement->execute()) {
            return $this->pdo->lastInsertId();
        }
    }

    public function selectAllComments(): array
    {
        return $this->pdo->query('SELECT comment.content, article.title FROM comment INNER JOIN article ON article_id = comment.article_id;', \PDO::FETCH_CLASS, $this->className)->fetchAll();
    }
}
