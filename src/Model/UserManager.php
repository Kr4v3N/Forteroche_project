<?php

namespace Model;

/**
 * Class UserManager
 *
 * @package \Model
 */
class UserManager extends AbstractManager
{
    const TABLE = 'user';
    public function __construct(\PDO $pdo)
    {
        parent::__construct(self::TABLE, $pdo);
    }


    public function selectAllUsers(): array
    {
        return $this->pdo->query('SELECT * FROM user ORDER BY lastname', \PDO::FETCH_CLASS, $this->className)->fetchAll();
    }

    public function userDelete(int $id): int
    {
        $statement = $this->pdo->prepare("DELETE FROM user WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

}
