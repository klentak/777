<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="css.css">
<title>777</title>
<script src="sidebar.js"></script>
</head>
<body>
<?php
	session_start();
	
	if (!isset($_SESSION['zalogowano']))
	{
		header('Location: index.php');
		exit();
	}
?>
<!------------------------------------------------------------------------->
	
<div id="sidebar">
	<ul>
	<li>HISTORIA</li>
	<li>MNOŻNIK</li>
	<li>WYLOGUJ</li>
	</ul>

	
	<div class="button" onClick="toggle()">
	<span></span>
	<span></span>
	<span></span>
	</div>
		
</div>	


<!----------------------------LOGO--------------------------------------->
<img class="r_logo" src="img/777.png" width="314" height="156">
<!------------------------------------------------------------------------->
<div id="g_center">
<div class="number">
	<?php
	if (isset($_SESSION['a']))
	{
		echo $_SESSION['a'];
	}
	else
	{
		echo '$';
	}
	?>
</div>
<div class="number">
	<?php
	if (isset($_SESSION['b']))
	{
		echo $_SESSION['b'];
	}
	else
	{
		echo '$';
	}
	?>
</div>
<div class="number">
	<?php
	if (isset($_SESSION['c']))
	{
		echo $_SESSION['c'];
	}
	else
	{
		echo '$';
	}
	?>
</div>
	
<form method="POST" action="">
<input id="zagraj" type="submit" name="ZAGRAJ" value="ZAGRAJ">
</form>
<div class="pkt">
<?php
	if ($_SESSION['punkty']<=0)
	{
		echo "Nie masz wystarczającej liczby punktów abu zagrać";
	}
	else 
	{
		if (isset($_SESSION['wygrana']))
		{
			echo $_SESSION['wygrana'];
		}
	}
?>
</div>
</div>
	
<!--------------------------------DÓŁ----------------------------------------->
<div id="dol">
<div id="g_login">
<?php
echo $_SESSION['login'];	
?>
</div>
<div id="g_punkty">
<?php
echo 'Punkty <strong id="bold">'.$_SESSION['punkty']."</strong>";	
?>
</div>
</div>
	
<!-------------------------------WYLOGUJ------------------------------------------>
<form action="logout.php" method="post">
<input id="submit" class="g_wyloguj" type="submit" value="wyloguj"/>
</form>

<!------------------------------------------------------------------------->

<?php
	require('php.php');
	if ($_SESSION['punkty']>=10 && isset($_POST['ZAGRAJ']))
	{
		losuj();
	}
	
?>
</body>
</html>