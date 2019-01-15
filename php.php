<?php
function losuj()
{
for ($i=0; $i<=2; $i++)
	{
		$liczby[$i] = rand(0, 4);
	}
	$_SESSION['a'] = $liczby[0];
	$_SESSION['b'] = $liczby[1];
	$_SESSION['c'] = $liczby[2];
	
	
	if ($liczby[0]==$liczby[1] && $liczby[0]==$liczby[2] && $liczby[1]==$liczby[2])
	{
		$_SESSION['wygrana']="WYGRAŁEŚ";
	}
	else
	{
		$_SESSION['wygrana']="PRZEGRAŁEŚ";
	}
	require_once('connect.php');
	$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
	
	if ($polaczenie->connect_errno!=0)
	{
		echo "Error: ".$polaczenie->connect_errno;
	}
	else
	{
		if ($rezultat = @$polaczenie->query(
		sprintf("SELECT * FROM uzytkownicy WHERE login='%s'",
		mysqli_real_escape_string($polaczenie,$login))))
		{
		$login = $_SESSION['login'];
		$rezultat = $polaczenie->query("SELECT * FROM uzytkownicy WHERE login='$login'");
			
			if ($_SESSION['wygrana']=="PRZEGRAŁEŚ")
			{
				$punkty = $_SESSION['punkty']-10;
			}
			else if ($_SESSION['wygrana']="WYGRAŁEŚ")
			{
				$punkty = $_SESSION['punkty']+50;
			}
			$_SESSION['punkty'] =  $punkty;
		
			$rezultat = $polaczenie->query("UPDATE uzytkownicy SET punkty='$punkty' where login='$login'");	
			header("Refresh:0");
		
			
		}
		
		$polaczenie->close();
	}
}
?>