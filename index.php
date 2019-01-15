<!doctype html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<title>777</title>
<link rel="stylesheet" type="text/css" href="css.css">
</head>
<body>
<?php
session_start();

if ((isset($_SESSION['zalogowano'])) )
{
	header('Location: gra.php');
	exit();
}
?>
<div id="center">
	<a href="index.php"><img id="logo_index" src="img/777.png"></a>
	<!------------------------------------------------------------------------------------->
	<form action="zaloguj.php" method="post">
		<input id="text" class="i_text" type="text" name="login" placeholder="login"/>
		<input id="text" class="i_text" type="password" name="haslo" placeholder="hasło"/>
			<div id="zle_haslo">
			<?php
			if(isset($_SESSION['blad']))	
			echo $_SESSION['blad'];
			unset($_SESSION['blad']);
			?>
			</div>
		<input id="submit" class="i_submit" type="submit" value="zaloguj"/>
	</form>
	<!------------------------------------------------------------------------------------->
	<!-- link rejestarcji -->
	<form action="rejestracja.php" method="post">
		<input id="submit" class="i_submit" type="submit" value="utwórz konto">
	</form>
</div>
</body>
</html>
