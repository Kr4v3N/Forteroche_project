<?php

namespace Model;

/**
 * Class Users
 *
 * @package \Model
 */
class User
{
    private $id;
    private $firstname;
    private $lastname;
    private $email;
    private $pass;
    private $registered;
    private $status;
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }
    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        if (!empty($_POST['firstname'] . $firstname)) {
            $this->firstname = $firstname;
        }else {
            die('Veuillez renseigner votre prénom');
        }
        if(strlen($_POST['firstname']. $firstname) >= 2 || strlen($_POST['firstname']). $firstname <= 30) {
            $this->firstname = $firstname;
        }else{
            die('Le prénom doit comporter entre 2 et 30 caractères');
        }
        if (preg_match("/^[a-zA-Z ]*$/",$_POST['firstname']. $firstname)) {
            $this->firstname = $firstname;
        }else{
            die ('Seul les lettres et espaces sont autorisés.') ;
        }
    }
    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }
    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        if (!empty($_POST['lastname'] . $lastname)) {
            $this->lastname = $lastname;
        }else {
            die('Veuillez renseigner votre nom');
        }
        if(strlen($_POST['lastname']. $lastname) >= 2 || strlen($_POST['lastname']). $lastname <= 30) {
            $this->lastname = $lastname;
        }else{
            die('Le nom doit comporter entre 2 et 30 caractères');
        }
        if (preg_match("/^[a-zA-Z ]*$/",$_POST['lastname']. $lastname)) {
            $this->lastname = $lastname;
        }else{
            die ('Seul les lettres et espaces sont autorisés.') ;
        }
    }
    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }
    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = $email;
        }else{
            die('Ton mail !!!');
        }
    }
    /**
     * @return mixed
     */
    public function getPass()
    {
        return $this->pass;
    }
    /**
     * @param mixed $pass
     */
    public function setPass($pass)
    {
        $this->pass = $pass;
    }
    /**
     * @return mixed
     */
    public function getRegistered()
    {
        return $this->registered;
    }
    /**
     * @param mixed $registered
     */
    public function setRegistered($registered)
    {
        $this->registered = $registered;
    }
    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }
    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
}
