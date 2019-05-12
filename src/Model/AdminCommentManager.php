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

    //TODO insert comment if user is logged
    public function insert(Comment $comment): int
    {
        var_dump(intval($_SESSION['user']['id']));
        var_dump($comment);
        //die();
        $statement = $this->pdo->prepare("INSERT INTO $this->table (date, content, article_id, user_id)
        VALUES (DATE(NOW()), :content, :article_id, :user_id)");
        $statement->bindValue(':content', $comment->getContent(), \PDO::PARAM_STR);
        $statement->bindValue(':article_id', $comment->getArticleId(), \PDO::PARAM_INT);
        $statement->bindValue('user_id', $comment->getUserId(), \PDO::PARAM_STR);

        if ($statement->execute()) {
            return $this->pdo->lastInsertId();
        }
    }

    public function ShowAllComments(int $id){

        // prepared request
        $statement = $this->pdo->prepare("SELECT comment.id, comment.content, comment.date, user.lastname FROM $this->table 
        INNER JOIN user ON comment.user_id=user.id WHERE article_id=:id ORDER BY date DESC");
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->className);
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function selectAllComments(): array
    {
        return $this->pdo->query("SELECT comment.id, comment.content, article.title, user.lastname  FROM comment 
        INNER JOIN article ON article.id = comment.article_id INNER JOIN user ON user.id=comment.user_id;", \PDO::FETCH_CLASS, $this->className)->fetchAll();
    }

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

    //Allows to take into account only the comments reported, taking into account a flag signal in the table commen
    public function countSignal()
    {
        $signals = $this->pdo->query("SELECT COUNT(signale) AS Signals FROM $this->table  WHERE signale !=0 ")->fetchColumn();
        return $signals;
    }
    //Selection of all reported comments
    public function showSignal()
    {
        return $this->pdo->query('SELECT comment.id, comment.signale, DATE_FORMAT(comment.date, "%e %M %Y à %Hh %i") 
        AS date, comment.content, comment.user_id, comment.article_id, user.firstname AS userFirstname, user.lastname, article.title FROM comment 
        INNER JOIN user ON user.id=comment.user_id INNER JOIN article ON article.id=comment.article_id WHERE comment.signale != 0 
        ORDER BY date DESC', \PDO::FETCH_CLASS, $this->className)->fetchAll();
    }
    //Adding a comment with increment if the comment has already been reported
    public function addSignal(int $id)
    {
        $statement = $this->pdo->prepare("UPDATE $this->table SET signale = signale+1 WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
    //delete a report
    public function resetSignal(int $id)
    {
        $statement = $this->pdo->prepare("UPDATE $this->table SET signale = 0 WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}

