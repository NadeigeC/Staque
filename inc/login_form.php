<form action="login.php" method="POST" id="login_form">

                <h3>CONNECTEZ-VOUS !</h3>

                <div class="field_container">
                        <label for="username">Votre nom</label>
                        <input type="text" value="<?php echo $username; ?>" name="username" id="username" />
                </div>

                <div class="field_container">
                        <label for="password">Mot de passe</label>
                        <input type="password" value="<?php echo $password; ?>" name="password" id="password" />
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
    <input type="submit" value="CONNEXION !" class="submit"/>
    <?php echo "<br>"; ?>
    <?php echo "<br>"; ?>

</form>
<a href="password_reset_1.php" title="oubli du mot de passe">OUBLI DU MOT DE PASSE</a>
