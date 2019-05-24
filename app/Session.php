<?php

namespace App;

/**
 * Class Session
 *
 * @package \App
 */
class Session{
    //Allows you to start session-start
    public function __construct(){
        session_start();
    }
}
