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
        $statement->bindValue(':content', $comment->getContent(), \PDO::PARAM_STR);
        $statement->bindValue(':article_id', $comment->getArticleId(), \PDO::PARAM_INT);

        if ($statement->execute()) {
            return $this->pdo->lastInsertId();
        }
    }

    public function ShowAllComments(int $id){

        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM $this->table WHERE article_id=:id ORDER BY date DESC");
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->className);
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function selectAllComments(): array
    {
        return $this->pdo->query("SELECT comment.id, comment.content, article.title FROM $this->table 
        INNER JOIN article ON article.id = comment.article_id", \PDO::FETCH_CLASS, $this->className)->fetchAll();
    }

//    public function selectAllComments(): array
//    {
//        return $this->pdo->query("SELECT comment.id, comment.content, article.title, user.lastname  FROM comment
//        INNER JOIN article ON article.id = comment.article_id
//        INNER JOIN user ON user.id=comment.user_id;", \PDO::FETCH_CLASS, $this->className)->fetchAll();
//    }

    public function delete(int $id): int
    {
        $statement = $this->pdo->prepare("DELETE FROM comment WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    public function count()
    {
        $numbers = $this->pdo->query("SELECT COUNT(id) AS Numbers FROM $this->table ")->fetchColumn();
        return $numbers;
    }
    public function selectCommentByUser($id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM $this->table WHERE user_id=:id ORDER BY date DESC");
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->className);
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function deleteComment(int $id): int
    {
        $statement = $this->pdo->prepare("DELETE FROM comment WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}

