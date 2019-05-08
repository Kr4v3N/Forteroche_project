<?php

namespace Model;

/**
 * Class ArticleManager
 *
 * @package \Model
 */
class ArticleManager extends AbstractManager
{
    const TABLE = 'article';
    public function __construct(\PDO $pdo)
    {
        parent::__construct(self::TABLE, $pdo);
    }

    public function insert(Article $article): int
    {
        $statement = $this->pdo->prepare("INSERT INTO $this->table (date, title, content, picture, user_id, category_id)

        VALUES (DATE(NOW()), :title, :content, :picture, :user_id, :category)");
        $statement->bindValue(':title', $article->getTitle(), \PDO::PARAM_STR);
        $statement->bindValue(':content', $article->getContent(),\PDO::PARAM_STR);
        $statement->bindValue(':picture', $article->getPicture(), \PDO::PARAM_STR);
        $statement->bindValue(':user_id', 1, \PDO::PARAM_STR);
        $statement->bindValue(':category', $article->getCategoryId(), \PDO::PARAM_STR);


        if ($statement->execute()) {
            return $this->pdo->lastInsertId();
        }
    }

    public function selectAllArticles(): array
    {
        return $this->pdo->query('SELECT * FROM article ORDER BY date DESC', \PDO::FETCH_CLASS, $this->className)->fetchAll();
    }

    public function delete(int $id): int
    {
        $statement = $this->pdo->prepare("DELETE FROM article WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    public function update(Article $article): int
    {
        $statement = $this->pdo->prepare("UPDATE $this->table SET title = :title, content = :content WHERE id=:id");
        $statement->bindValue('title', $article->getTitle(), \PDO::PARAM_STR);
        $statement->bindValue('content', $article->getContent(), \PDO::PARAM_STR);
        $statement->bindValue('id', $article->getId(), \PDO::PARAM_INT);

        return $statement->execute();

    }

    public function selectAllArticlesAndCategory(): array
    {
        return $this->pdo->query('SELECT article.id, article.title, category.name, article.content FROM article INNER JOIN category ON category.id = article.user_id;;', \PDO::FETCH_CLASS, $this->className)->fetchAll();}
}
