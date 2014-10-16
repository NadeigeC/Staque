<?php

	session_start();

	include("db.php");
	include("inc/functions.php");

	$email = "";
	if (!empty($_GET['email'])){
		$email = $_GET['email'];
	}		
	$token = "";
	if (!empty($_GET['token'])){
		$token = $_GET['token'];
	}

	if ($token && $email){
		//vérifier que les données dans l'url sont valides
		$sql = "SELECT * FROM users
				WHERE email = :email AND token = :token";
		$stmt = $dbh->prepare($sql);
		$stmt->bindValue(":email", $email);
		$stmt->bindValue(":token", $token);
		$stmt->execute();
		$foundUser = $stmt->fetch();
	}

	if (empty($foundUser)){
		die("oops");
	}

	//déclaration des variables du formulaire
	$password 		= "";
	$password_bis 	= "";

	$errors = array();

	//formulaire soumis ?
	if (!empty($_POST)){

		$password 		= $_POST["password"];
		$email 		= $_POST["email"];
		$token 		= $_POST["token"];
		$password_bis 	= $_POST['password_bis'];


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

		if (empty($errors)){
			
			$sql = "UPDATE users 
					SET password = :password,
						dateModified = NOW()
					WHERE email = :email 
					AND token = :token";

			$stmt = $dbh->prepare($sql);
			$stmt->bindValue(":password", hashPassword($password, $foundUser['salt']));
			$stmt->bindValue(":email", $email);
			$stmt->bindValue(":token", $token);
			if ($stmt->execute()){
				$sql = "UPDATE users 
						SET token = :token,
							dateModified = NOW()
						WHERE email = :email";

				$stmt = $dbh->prepare($sql);
				$stmt->bindValue(":token", randomString());
				$stmt->bindValue(":email", $email);
				if ($stmt->execute()){
					//log the user automatically
					$_SESSION['user'] = $foundUser;
					header("Location: index.php");
					die();
				}
			}
		}
	}

	include("inc/top.php");
?>
<div class="container">
<form name="yo" action="password_reset_2.php?<?php echo $_SERVER['QUERY_STRING']; ?>" id="password_reset_2" method="POST" novalidate>

	<!-- peut servir pour détecter facilement QUEL formulaire a été soumis -->
	<input type="hidden" name="form_name" value="password_reset_2" />
	<input type="hidden" name="token" value="<?= $token ?>" />
	<input type="hidden" name="email" value="<?= $email ?>" />

	<h3>FORGOT YOUR PASSWORD ?</h3>

	<div class="field_container">
		<label for="password">Nouveau Mot de passe</label>
		<input type="password" id="password" name="password" value="" />
	</div>
	<div class="field_container">
		<label for="password_bis">Confirmez</label>
		<input type="password" id="password_bis" name="password_bis" value="" />
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
</div>
<?php 	include("inc/bottom.php"); ?>