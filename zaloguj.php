<?php
	session_start();

	if ((!isset($_POST['login'])) || (!isset($_POST['haslo'])))
	{
		header('Location: index.php');
		$_SESSION['blad'] = 'Nieprawidłowy login lub hasło!';
		exit();
	}

	require_once "connect.php";

	$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
	
	if ($polaczenie->connect_errno!=0)
	{
		echo "Error: ".$polaczenie->connect_errno;
	}
	else
	{
		$login = $_POST['login'];
		$haslo = $_POST['haslo'];
		$login = htmlentities($login, ENT_QUOTES, "UTF-8");
		
	
		if ($rezultat = @$polaczenie->query(
		sprintf("SELECT * FROM uzytkownicy WHERE login='%s'",
		mysqli_real_escape_string($polaczenie,$login))))
		{
			$ilu_userow = $rezultat->num_rows;
			if($ilu_userow>0)
			{
				$wiersz = $rezultat->fetch_assoc();

				if (password_verify($haslo, $wiersz['haslo']))
				{

					$_SESSION['zalogowano'] = true;
					$_SESSION['login'] = $wiersz['login'];
					$_SESSION['punkty'] = $wiersz['punkty'];
					unset($_SESSION['blad']);
					$rezultat->free_result();
					header('Location: gra.php');
				}
				else 
				{
					$_SESSION['blad'] = 'Nieprawidłowy login lub hasło!';
					header('Location: index.php');

				}
				
			} 
			else 
			{
				
				$_SESSION['blad'] = 'Nieprwidłowy login lub hasło!';
				header('Location: index.php');
				
			}
			
		}
		
		$polaczenie->close();
	}
	
?>