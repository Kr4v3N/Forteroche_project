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

    /**
     * UserManager constructor.
     *
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        parent::__construct(self::TABLE, $pdo);
    }

    /**
     * @return array
     */
    public function selectAllUsers(): array
    {
        $this->pdo->query("SET lc_time_names = 'fr_FR'");
        return $this->pdo->query('SELECT id, firstname, lastname, email, DATE_FORMAT(registered, "%e %M %Y") 
        AS registered, status 
        FROM user ORDER BY user.registered DESC', \PDO::FETCH_CLASS, $this->className)->fetchAll();
    }

    /**
     * @param int $id
     *
     * @return int
     */
    public function userDelete(int $id): int
    {
        $statement = $this->pdo->prepare("DELETE FROM user WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return header('Location: /admin/users');
    }

    /**
     * @param \Model\User $user
     *
     * @return bool
     */
    public function suscribe(User $user)
    {
        $addUser = $this->pdo->prepare("INSERT INTO $this->table (firstname, lastname, email, pass, 
        registered, status) VALUES (:firstname,:lastname, :email, :password, NOW(),'user')");
        $addUser->bindValue(':firstname', $user->getFirstname(), \PDO::PARAM_STR);
        $addUser->bindValue(':lastname', $user->getLastname(), \PDO::PARAM_STR);
        $addUser->bindValue(':email', $user->getEmail(), \PDO::PARAM_STR);
        $addUser->bindValue(':password', password_hash($user->getPass(), PASSWORD_DEFAULT),
        \PDO::PARAM_STR);
        return $addUser->execute();
    }

    /**
     * @param \Model\User $user
     *
     * @return bool
     */
    public function userAdd(User $user)
    {
        $statement = $this->pdo->prepare("INSERT INTO $this->table (firstname, lastname, email, pass, 
        registered, status) VALUES (:firstname, :lastname, :email, :pass, DATE(NOW()), :status)");
        $statement->bindValue(':firstname', $user->getFirstname(),\PDO::PARAM_STR);
        $statement->bindValue(':lastname', $user->getLastname(), \PDO::PARAM_STR);
        $statement->bindValue(':email', $user->getEmail(), \PDO::PARAM_STR);
        $statement->bindValue(':pass', password_hash($user->getPass(), PASSWORD_DEFAULT),
        \PDO::PARAM_STR);
        $statement->bindValue(':status', $user->getStatus(), \PDO::PARAM_STR);
        return $statement->execute();
    }

    /**
     * @return mixed
     */
    public function count()
    {
        $numbersUsers = $this->pdo->query("SELECT COUNT(id) AS Numbers FROM $this->table")->fetchColumn();
        return $numbersUsers;
    }

    /**
     * @param $email
     *
     * @return mixed
     */
    public function existUser($email) {
        $query = $this->pdo->prepare("SELECT * FROM $this->table WHERE email = :email");
        $query->execute(array(':email' => $email));
        $query->setFetchMode(\PDO::FETCH_CLASS, 'Model\User');
        $res =  $query->fetch();
        return $res;
    }

    /**
     * @param $email
     *
     * @return mixed
     */
    public function loginUser($email)
    {
        $reqUser = $this->pdo->prepare("SELECT * FROM $this->table WHERE email = :email");
        $reqUser->execute(array(':email' => $email));
        $reqUser->setFetchMode(\PDO::FETCH_CLASS, 'Model\User');
        $res =  $reqUser->fetch();
        return $res;
    }

    /**
     * @return array
     */
    public function selectUsersForIndex(): array
    {
        $this->pdo->query("SET lc_time_names = 'fr_FR'");
        return $this->pdo->query('SELECT id, firstname, lastname, email, DATE_FORMAT(registered, "%e %M %Y") 
        AS registered, status FROM user ORDER BY user.registered DESC LIMIT 3',
        \PDO::FETCH_CLASS, $this->className)->fetchAll();
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function selectUserById(int $id): array
    {
        $this->pdo->query("SET lc_time_names = 'fr_FR'");
        // prepared request
        $statement = $this->pdo->prepare("SELECT id, firstname, lastname, email, 
        DATE_FORMAT(registered, \"%e %M %Y\") AS registered,  status FROM $this->table WHERE id=:id");
        $statement->setFetchMode(\PDO::FETCH_CLASS, 'Model');
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch();
    }

}
