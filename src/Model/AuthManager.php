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

    /**
     * AuthManager constructor.
     *
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        parent::__construct(self::TABLE, $pdo);
    }

    /**
     * @param $email
     *
     * @return mixed
     */
    public function login($email)
    {
        // select where name = $username and password = $password and droit =
        $statement = $this->pdo->prepare("SELECT * FROM $this->table WHERE email=:email  AND status='admin'");
        $statement->bindValue(':email', $email, \PDO::PARAM_STR);
        $statement->execute();
        // fetch
        $statement->setFetchMode(\PDO::FETCH_CLASS, 'Model\User');

        return $statement->fetch();
    }
}
