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

    public function ShowAllComments(int $id){

        // prepared request
        $statement = $this->pdo->prepare("SELECT comment.id, comment.content, DATE_FORMAT(comment.date, \"%e %M %Y à %Hh %i\") AS date, user.lastname, user.firstname FROM $this->table INNER JOIN user ON comment.user_id=user.id WHERE article_id=:id ORDER BY date DESC");
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->className);
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function selectAllComments(): array
    {
        return $this->pdo->query('SELECT comment.content, article.title FROM comment INNER JOIN article ON article_id = comment.article_id;', \PDO::FETCH_CLASS, $this->className)->fetchAll();
    }

    public function delete(int $id): int
    {
        $statement = $this->pdo->prepare("DELETE FROM comment WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

}
