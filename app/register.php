<?php

// Add the first admin hard bd, password bcrypt

$pdo = new PDO('mysql:host=localhost;dbname=forteroche1', 'root', 'root');

$statement = $pdo->prepare("insert into user (firstname, lastname, email, pass, registered, status) VALUES ('admin', 'admin', 'super@root.com', :password, NOW(), 'admin' )");
$statement->bindValue('password', password_hash('password', PASSWORD_DEFAULT));
$statement->execute();
