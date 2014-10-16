<?php

  //connexion
    include("db.php");
    include("inc/functions.php");

    $email = "";
    $username = "";
    $password = "";
    $password_bis = "";

    $errors = array();

    //formulaire soumis ?
    if (!empty($_POST)){
        //on écrase les valeurs définies ci-dessus, tout en se protegeant
        //pas de strip tags sur la password par contre (si la personne veut mettre des balises dans son pw, c'est son affaire, et on le hache anyway)
        $email          = strip_tags($_POST['email']);
        $username       = strip_tags($_POST['username']);
        $password       = $_POST['password'];
        $password_bis   = $_POST['password_bis'];

        //validation

        //email
        if (empty($email)){
            $errors[] = "Merci d'entrer votre email !";
        }
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors[] = "Votre email n'est pâs valide !";
        }
        elseif (emailExists($email)){
            $errors[] = "Cet email existe déjà !";
        }

        //username
        if (empty($username)){
            $errors[] = "Merci d'entrer votre nom d'utilisateur !";
        }
        //@guillaume: todo vérifier si username est présent en bdd

        //vérifie si username est présent en bdd
        elseif (usernameExists($username)){
            $errors[] = "Vous êtes déjà enregistré !";
        }

        //password
        if (empty($password)){
            $errors[] = "Choisissez un Mot de passe !";
        }
        elseif (empty($password_bis)){
            $errors[] = "Merci de confirmer votre mot de passe !";
        }
        elseif ($password_bis != $password){
            $errors[] = "Les deux mots de passe ne correspondent pas !";
        }
        elseif (strlen($password) < 7){
            $errors[] = "Votre mot de passe doit contebnir au moins 7 caractères !";
        }

        //form valide ?
        if (empty($errors)){
            //prépare les données pour l'insertion en base
            $salt = randomString();

            $hashedPassword = hashPassword($password, $salt);
            $token = randomString();
            $sql = "INSERT INTO users(email, username, password, salt, token, dateRegistered, dateModified)
                    VALUES (:email, :username, :password, :salt, :token, NOW(), NOW())";

                    $stmt = $dbh->prepare($sql);
                    $stmt->bindValue(":email", $email);
                    $stmt->bindValue(":username", $username);
                    $stmt->bindValue(":password", $hashedPassword);
                    $stmt->bindValue(":salt", $salt);
                    $stmt->bindValue(":token", $token);

                    $stmt->execute();
                    header("Location: index.php");

           
        }
    }
?>

<?php include("inc/top.php"); ?>
<div class="container">
<?php include("inc/register_form.php"); ?>
</div>
<?php include("inc/bottom.php"); ?>


