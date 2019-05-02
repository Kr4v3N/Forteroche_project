<?php

namespace Model;

/**
 * Class AuthManager
 *
 * @package \Model
 */
class AuthManager extends AbstractManager
{
    const TABLE = 'user';
    public function __construct(\PDO $pdo)
    {
        parent::__construct(self::TABLE, $pdo);
    }

    public function login($username, $password)
    {
        // Hachage du mot de passe
        $pass_hash = sha1($password);

        // request cherche l user en particuler
        // select where name = $username and password = $password and droit =
        $statement = $this->pdo->prepare("SELECT * FROM $this->table WHERE lastname=:username AND pass=:password AND status='admin'");
        $statement->bindValue(':username', $username, \PDO::PARAM_STR);
        $statement->bindValue(':password', $pass_hash, \PDO::PARAM_STR);
        $statement->execute();
        // fetch
        $statement->setFetchMode(\PDO::FETCH_CLASS, 'Model\User');
        return $statement->fetch();

    }
}
