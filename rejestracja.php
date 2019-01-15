<?php

	session_start();

	if (isset($_POST['email']))
	{
		//Udana walidacja..
		$walidacja=true;
		
		//sprawdzenie loginu
		$nick = $_POST['nick'];

		//sprawdzenie długościu loginu
		if ((strlen($nick)<4 || strlen($nick)>20)) 
		{
			$walidacja=false;
			$_SESSION['e_nick']="Login musi posiadać od 4 do 20 znaków.";
		}

		// sprawdzanie poprawności adresu email
		$email=$_POST['email'];
		$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

		if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
		{
			$walidacja=false;
			$_SESSION['e_email']="Błędny adres e-mail.";
		}

		//sprawdzanie poprawności hasła
		$haslo1 = $_POST['password1'];
		$haslo2  = $_POST['password2'];

		if ((strlen($haslo1)<8) || (strlen($haslo1) > 20))
		{
			$walidacja=false;
			$_SESSION['e_haslo']="Hasło musi posiadać od 8 do 20 znaków.";
		}

		//sprawdzenie czy hasła są takie same
		if ($haslo1 != $haslo2)
		{
			$walidacja=false;
			$_SESSION['e_spojnosc']="Wpisane hasła nie są takie same.";
		}

		$haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);

		//sprawdzenie czy został zaakceptowany regulamin
		if (!isset($_POST['regulamin'])) 
		{
			$walidacja = false;
			$_SESSION['e_regulamin']="Nie zaakceptowano regulaminu";
		}

		//łączenie z serwerem
		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);


		try 
		{
			$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
			if ($polaczenie->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{
		//sprawdzenie czy podany email nie wystepuje w bazie danych
				$rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE email='$email'");
				if (!$rezultat) throw new Exception($polaczenie->error);

				$ile_maili=$rezultat->num_rows;
				if($ile_maili>0)
				{
					$walidacja=false;
					$_SESSION['e_email']="Istnieje już konto z podanym adresem e-mail.";
				}

		//sprawdzenie czy podany login nie wystepuje w bazie danych
				$rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE login='$nick'");
				if (!$rezultat) throw new Exception($polaczenie->error);

				$ile_loginów=$rezultat->num_rows;
				if($ile_loginów>0)
				{
					$walidacja=false;
					$_SESSION['e_nick']="Istnieje już konto o podanym loginie.";
				}

				//uzytkownik spelnil wszystkie zalozenia rejestracji, mozna go dodac do bazy
				if ($walidacja==true)
				{
					if ($polaczenie->query("INSERT INTO uzytkownicy VALUES (NULL, '$nick', '$haslo_hash', '$email', '5000')")) 
					{
						$_SESSION['udana_rejestracja']=true;
						header('Location: gra.php');
					}
					else
					{
						throw new Exception($polaczenie->error);
					}
				}

				$polaczenie->close();
			}

		}
		catch(Exception $e)
		{
			echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym czasie.</span>';
			echo '<br />Informacja developerska: '.$e;
		}

	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Rejestracja nowego konta</title>
	<link rel="stylesheet" type="text/css" href="css.css">
	<meta charset="utf-8">
</head>

<body>
<a href="index.php"><img class="r_logo" src="img/777.png" width="314" height="156"></a>
<div id="r_center">
<form method="post">

<br /> <input id="text" class="r_text" type="text" placeholder="login" name="nick"><br />
<div id="alert">
<?php 
	if (isset($_SESSION['e_nick']))
	{
		echo '<div class="error">'.$_SESSION['e_nick'].'</div>';
		unset($_SESSION['e_nick']);
	}
?>
</div>
<br /><input id="text" class="r_text" id="r_text" placeholder="e-mail" type="text" name="email"><br />
<div id="alert">
<?php 
	if (isset($_SESSION['e_email']))
	{
		echo '<div class="error">'.$_SESSION['e_email'].'</div>';
		unset($_SESSION['e_email']);
	}
?>
</div>
<br /><input id="text" class="r_text" type="password" placeholder="hasło" name="password1"><br />
<div id="alert">
<?php 
	if (isset($_SESSION['e_haslo']))
	{
		echo '<div class="error">'.$_SESSION['e_haslo'].'</div>';
		unset($_SESSION['e_haslo']);
	}
?>
</div>
<br /><input id="text" class="r_text" placeholder="powtórz hasło" type="password" name="password2"><br />
<div id="alert">
<?php 
	if (isset($_SESSION['e_spojnosc']))
	{
		echo '<div class="error">'.$_SESSION['e_spojnosc'].'</div>';
		unset($_SESSION['e_spojnosc']);
	}
?>
</div>
	
<label class="container">Akceptuje regulamin
  <input type="checkbox" name="regulamin" checked="checked">
  <span class="checkmark"></span>
</label>
	
	
<div id="alert">
<?php 
	if (isset($_SESSION['e_regulamin']))
	{
		echo '<div class="error">'.$_SESSION['e_regulamin'].'</div>';
		unset($_SESSION['e_regulamin']);
	}
?>
</div>	
<input id="submit" class="r_submit" type="submit" value="Zarejestruj się">
</div>
</form>
</body>
</html>