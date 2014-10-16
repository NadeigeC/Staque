<?php

session_start();
include("db.php");
include("inc/functions.php");


    //déclaration des variables du formulaire
    $email      = "";

    $errors = array();

    //formulaire soumis ?
    if (!empty($_POST)){

        $email= $_POST['email'];

        if (empty($email)){
            $errors[] = "Merci d'entrer votre email !";
        }
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors[] = "Votre email n'est pâs valide !";
        }

        //vérifier que l'email existe bel et bien
        $sql = "SELECT email, username, token FROM users
                WHERE email = :email";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(":email", $email);
        $stmt->execute();
        $user = $stmt->fetch();

        if (!empty($user)){
            //envoyer un message

            include("phpMailer/PHPMailerAutoload.php"); //chargera les fichiers nécessaires

            $mail = new PHPMailer();        //Crée un nouveau message (Objet PHPMailer)
            $mail->CharSet = 'UTF-8';       //Encodage en utf8

            //INFOS DE CONNEXION
            $mail->isSMTP();                                    //On utilise SMTP
            $mail->Username = "nadeige.couthon@orange.fr"; //nom d'utilisateur
            $mail->Password = "SUphwXNvhAEaSF1-G3020Q";         //mot de passe
            $mail->Host = 'smtp.mandrillapp.com';               //smtp.gmail.com pour gmail
            $mail->Port = 587;                                  //Le numéro de port
            $mail->SMTPAuth = true;                             //On utilise l'authentification SMTP ?
            //$mail->SMTPSecure = 'tls';                        //décommenter pour gmail

    //CONFIGURATION DES PERSONNES
    $mail->setFrom('account@bestmovies.com', 'Best Mooooovies !');  //qui envoie ce message ? (email, noms)
    $mail->addReplyTo('account@bestmovies.com', 'Best Mooooovies !'); //à qui répondre si on clique sur "reply" (email, noms)
    $mail->addAddress($user['email'], $user['username']);   //destinataire
            

    //CONFIGURATION DU MESSAGE
    $mail->isHTML(true);                                // Contenu du message au format HTML
    $mail->Subject = 'Password reset BEST MOOOOOVIES !!!!'; //le sujet
    
    $resetUrl = "http://localhost/8-movies_2/Movies/password_reset_2.php?email="
             . urlencode($email) . '&token=' . urlencode($user['token']);

    //le message

    $mail->Body = 'Cliquez sur le lien suivant pour réinitialiser votre mot de passe :<br /><a href="'.$resetUrl.'">'.$resetUrl.'</a>';
                    
    //envoie le message
    if (!$mail->send()) {
        $errors[] = "Erreur lors de l'envoi du message";
    }
    else {
        $errors[] = "Message envoyé !"; //$mailSent = true;
        }
    }
}

    include("inc/top.php");
?>
<div class="container">
    <form action="password_reset_1.php" id="password_reset_1" method="POST" novalidate >
    <!-- peut servir pour détecter facilement QUEL formulaire a été soumis -->
    <input type="hidden" name="form_name" value="password_reset_1" />

            <h3>OUBLI DU MOT DE PASSE !</h3>

    <div class="field_container">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="" />
    </div>
    <?php
        if (!empty($errors)){
            echo '<ul class="errors">';
            foreach($errors as $error){
                echo '<li>'.$error.'</li>';
            }
            echo '</ul>';
        }
    ?>
    <input type="submit" value="REINITIALISER VOTRE MOT DE PASSE" class="submit"/>

</form>
</div>
<?php   include("inc/bottom.php"); ?>
