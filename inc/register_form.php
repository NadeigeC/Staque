
<form action="register.php" method="POST" id="register_form">

                <h3>CREATION DU COMPTE UTILISATEUR</h3>

                <div class="field_container">
                        <label for="username">Votre nom</label>
                        <input type="text" name="username" id="username" value="<?php echo $username; ?>" />
                </div>
                <div class="field_container">
                        <label for="email">Votre email</label>
                        <input type="email" name="email" id="email" value="<?php echo $email; ?>" />
                </div>
                <div class="field_container">
                        <label for="password">Mot de passe</label>
                        <input type="password" name="password" id="password" value="<?php echo $password; ?>" />
                </div>
                <div class="field_container">
                        <label for="password_bis">Retaper le mot de passe</label>
                        <input type="password" name="password_bis" id="password_bis" value="<?php echo $password_bis; ?>" />
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
    <input type="submit" value="SAVE !" class="submit"/>

</form>






