<?php

namespace App;

/**
 * Class session
 *
 * @package \App
 */
class Session{
    // Permet de démarrer session-start
    public function __construct(){
        session_start();
    }

    // Fonction qui permet de définir un message de notification avec 2 paramètres
    public function setFlash($message,$type = 'error'){
        $_SESSION['flash'] = array(
            'message' => $message,
            'type'	  => $type
        );
    }

    // Fonction qui détecte s'il y a un message dans la vue
    public function flash(){
        if(isset($_SESSION['flash'])){
            ?>
            <div id="alert" class="alert alert-<?php echo $_SESSION['flash']['type']; ?>">
                <a class="close">x</a>
                <?php echo $_SESSION['flash']['message']; ?>
            </div>
            <?php
            // Pour que le message ne reste pas en session tout le temps
            unset($_SESSION['flash']);
        }
    }

}
