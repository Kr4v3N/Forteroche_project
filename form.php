<?php
$nameErr = $prenomErr = $numberErr = $emailErr = $subjectErr = $messageErr = "";
$name = $prenom = $number = $email = $subject = $message = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if (empty($_POST["name"])) {
        $nameErr = "Nom requis";
    }
    else {
        if (preg_match('#[a-zA-Z]+#', $_POST["name"])){
            $name = $_POST["name"];
        }else{
            $nameErr = "le nom doit comporter que des lettres !";
        }
    }
    if (empty($_POST["prenom"])) {
        $prenomErr = "Prénom requis";
    }
    else {
        if (preg_match('#[a-zA-Z]+#', $_POST["prenom"])){
            $prenom = $_POST["prenom"];
        }else{
            $prenomErr = "le prénom doit comporter que des lettres !";
        }
    }
    if (empty($_POST["number"])) {
        $numberErr = "Numéro de téléphone requis";
    }
    else {
        $number = $_POST["number"];
    }
    if (empty($_POST["email"]))  {
        $emailErr = "email requis";
    }
    else {
        if (preg_match('#^[\w.-]+@[\w.-]+\.[a-z]{2,5}$#i', $_POST["email"])){
            $email = $_POST["email"];
        }else{
            $emailErr = "Erreur dans votre e-mail";
        }
    }
    if (empty($_POST["subject"])) {
        $subjectErr = "Veuillez choisir une option";
    }
    else {
        $subject = $_POST["subject"];
    }
    if (empty($_POST["message"])) {
        $messageErr = "Veuillez écrire votre message";
    }
    else {
        $message = $_POST["message"];
    }
}
if ($name and $number and $email and $message and $prenom) {
    if(isset($_POST)){
        echo 'Merci pour votre message';
    }
}
?>

<html>
<head>
    <meta charset="utf-8">
    <title>My test page</title>
    <link rel="stylesheet" href="style.css">
    <link rel="php" href="formulaire.php">
</head>
<body>
<p>This is my page</p>
<form action="" method="POST">
    <div>
        <label for="name">Nom :</label>
        <input type="text" id="nom" name="name" value="<?php
        if (isset($_POST["name"])){
            echo $_POST["name"];} ?>" required>
        <span class="error"><?php echo $nameErr;?></span>
    </div>

    <div>
        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" value="<?php
        if (isset($_POST["prenom"])){
            echo $_POST["prenom"];} ?>" required>
        <span class="error"><?php echo $prenomErr;?></span>
    </div>

    <div>
        <label for="number">Téléphone :</label>
        <input type="tel" pattern="[0-9]{10}" id="number" name="number" value="<?php
        if (isset($_POST["number"])){
            echo $_POST["number"];
        } ?>" required>
        <span class="error"><?php echo $numberErr;?></span>
    </div>

    <div>
        <label for="courriel">Email :</label>
        <input type="email" id="courriel" name="email" value="<?php if (isset($_POST["email"])){
            echo $_POST["email"];
        } ?>" required>
        <span class="error"><?php echo $emailErr;?></span>
    </div>

    <div>
        <label for="subject">Votre véhicle</label><br />
        <select name="subject" id="subject">
            <option value="volvo">Volvo</option>
            <option value="saab">Saab</option>
            <option value="fiat">Fiat</option>
            <option value="audi">Audi</option>
        </select>
    </div>
    <div>
        <label for="message">Message :</label>
        <textarea id="message" name="message" required ><?php
            if(isset($message)) echo $message;
            ?></textarea>
        <span class="error"><?php
            echo $messageErr;
            ?></span>
    </div>

    <div class="button">
        <button type="submit">Envoyer votre message</button>
    </div>
</form>
</body>
</html>

