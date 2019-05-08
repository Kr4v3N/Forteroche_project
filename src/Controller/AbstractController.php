<?php

namespace Controller;

use Twig_Loader_Filesystem;
use Twig_Environment;

use App\Connection;


abstract class AbstractController
{
    /**
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        $loader = new Twig_Loader_Filesystem(APP_VIEW_PATH);
        $this->twig = new Twig_Environment(
            $loader,
            [
                'cache' => !APP_DEV,
                'debug' => APP_DEV,
            ]
        );
        $this->twig->addExtension(new \Twig_Extension_Debug());
        $this->twig->addExtension(new \Twig_Extensions_Extension_Text());
        $connection = new Connection();
        $this->pdo = $connection->getPdoConnection();
    }

    /**
     * @return \PDO
     */
    public function getPdo(): \PDO
    {
        return $this->pdo;
    }

    public function verifyAdmin() {
        if(!isset($_SESSION['admin'])){
            header('Location: /admin/logAdmin');
            exit();
        }
    }
}
