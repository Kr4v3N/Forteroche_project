<?php

namespace App;

/**
 * Class session
 *
 * @package \App
 */
class Session{
    //Allows you to start session-start
    public function __construct(){
        session_start();
    }
}
