<?php

    session_start();

    include("db.php");
    include("inc/functions.php");

    $username       = "";
    $password       = "";

    $errors = array();


//formulaire soumis ?
    if (!empty($_POST)){

        //on écrase les valeurs définies ci-dessus, tout en se protegeant
        //pas de strip tags sur la password par contre (si la personne veut mettre des balises dans son pw, c'est son affaire, et on le hache anyway)
        $username       = strip_tags($_POST['username']);
        $password       = $_POST['password'];

        //validation

        //username
        if (empty($username)){
            $errors[] = "Entrez un nom d'utilisateur !";
        }

        //password
        if (empty($password)){
            $errors[] = "Entrez un mot de passe !";
        }

        //form valide ?
        if (empty($errors)){

            //recherche l'utilisateur en bdd par son username (ou email)
            $sql = "SELECT * FROM users
                    WHERE username = :login OR email = :login
                    LIMIT 1";

            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(":login", $username);
            $stmt->execute();

            $user = $stmt->fetch();

            $hashedPassword = hashPassword($password, $user['salt']);
            
            if ($hashedPassword === $user['password']){
                $_SESSION['user'] = $user;
                header("Location: index.php");
                die();
            }
        }
    }
?>

<?php include("inc/top.php"); ?>
<div class="container">
<?php include("inc/login_form.php"); ?>
</div>
<?php include("inc/bottom.php"); ?>


