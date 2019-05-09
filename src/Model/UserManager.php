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

    public function userAdd(User $user)
    {
        $statement = $this->pdo->prepare("INSERT INTO $this->table (firstname, lastname, email, pass, registered, status)

        VALUES (:firstname, :lastname, :email, :pass, DATE(NOW()), :status)");
        $statement->bindValue(':firstname', $user->getFirstname(),\PDO::PARAM_STR);
        $statement->bindValue(':lastname', $user->getLastname(), \PDO::PARAM_STR);
        $statement->bindValue(':email', $user->getEmail(), \PDO::PARAM_STR);
        $statement->bindValue(':pass', password_hash($user->getPass(), PASSWORD_DEFAULT), \PDO::PARAM_STR);
        $statement->bindValue(':status', $user->getStatus(), \PDO::PARAM_STR);

        var_dump($user);

        return $statement->execute();
    }

    public function count()
    {
        $numbersUsers = $this->pdo->query("SELECT COUNT(id) AS Numbers FROM $this->table")->fetchColumn();
        return $numbersUsers;
    }

    public function existUser($email) {
        $query = $this->pdo->prepare("SELECT * FROM $this->table WHERE email = :email");
        $query->execute(array(':email' => $email));
        $query->setFetchMode(\PDO::FETCH_CLASS, 'Model\User');
        $res =  $query->fetch();
        return $res;
    }

    public function suscribe(User $user)
    {
        $addUser = $this->pdo->prepare("INSERT INTO $this->table (firstname, lastname, email, pass, registered, status) VALUES (:firstname,:lastname, :email, :password, NOW(),'user')");
        $addUser->bindValue(':firstname', $user->getFirstname(), \PDO::PARAM_STR);
        $addUser->bindValue(':lastname', $user->getLastname(), \PDO::PARAM_STR);
        $addUser->bindValue(':email', $user->getEmail(),\PDO::PARAM_STR);
        $addUser->bindValue(':password', password_hash($user->getPass(), PASSWORD_DEFAULT), \PDO::PARAM_STR);
        return $addUser->execute();
    }
}
