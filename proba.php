<?php
	
	session_start();
	if (!isset($_SESSION['zalogowany']))
	{
		header('Location: index.php');
		exit();
	}
	require_once "connect.php";
	$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
	
	if ($polaczenie->connect_errno!=0)
	{
		echo "Error: ".$polaczenie->connect_errno;
	}
	if($rezultat = @$polaczenie->query(
		"SELECT * FROM expenses WHERE user_id=4"))
		{
			$user_id=$_SESSION['id'];
			$wiersz = $rezultat->fetch_assoc();
			$user_id=$wiersz['id'];
			

					
					
			
		}
		else{echo "fault";}
	

	
	
		//$arr = array(1, 2, 3, 4);
		//foreach ($user_data_expense_row as &$value) {
		//$value = $value * 2;
		//echo " $value";}

	
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>test</title>
	
	<link rel="stylesheet" href="css_bootstramp/bootstrap.min.css">
	<link rel="stylesheet" href="style_bootstamp.css">
	<link rel="stylesheet" href="css/fontello.css">
	
	<link href="https://fonts.googleapis.com/css2?family=Lora&display=swap" rel="stylesheet">

	 
</head>


<body>

	
		<form method="post">
		<div class="col-sm-12">
		<fieldset>
		<legend class="d-flex justify-content-start">Wybierz kategorie:</legend>
	<?php

			$imie=$_SESSION['id'];
			echo $imie;
			
			$dup= $_SESSION['PAY_methods'][0];
			$dup2= $_SESSION['PAY_methods'][1];
			$dup3= $_SESSION['PAY_methods'][2];
			echo "$dup</br>";
			echo "$dup2</br>";
			echo "$dup3</br>";
	//$rodzaje_platnosci=$_SESSION['rodzaje_platnosci']->fetch_assoc();
	
		

	?>



		</fieldset>
		</div>
		
		</form>
	
	
	
	
</body>
</html>