<?php

	session_start();
		
		if (!isset($_SESSION['zalogowany']))
	{
		header('Location: index.php');
		exit();
	}
	
?>

<!DOCTYPE html>
<html lang="pl">
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<title>Menu Główne</title>
	<meta name="description" content="Aplikacja prowadzenia budżetu">
	<meta name="keywords" content="skoki narciarskie, skoczkowie, wyniki">
	<meta name="author" content="Jan Kowalski">
	<meta http-equiv="X-Ua-Compatible" content="IE=edge">
	
	<link rel="stylesheet" href="css_bootstramp/bootstrap.min.css">
	<link rel="stylesheet" href="style_bootstamp.css">
	<link rel="stylesheet" href="css/fontello.css">
	<link href="https://fonts.googleapis.com/css2?family=Lora&display=swap" rel="stylesheet">
	
	<!--[if lt IE 9]>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
	<![endif]-->
	
</head>

<body>

	<header>
	
		<nav class="navbar navbar-dark bg-jumpers navbar-expand-lg">
		
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainmenu" aria-controls="mainmenu" aria-expanded="false" aria-label="Przełącznik nawigacji">
				<span class="navbar-toggler-icon"></span>
			</button>
		
			<div class="collapse navbar-collapse" id="mainmenu">
				
				<div class="navbar-nav mr-auto">
					
						<h2><?php echo "Hello ".$_SESSION['user'];  ?></h2>	
				</div>
			
				<form class="form-inline">
					<a href="logout.php"><button class="btn btn-outline-secondary mr-1" type="button" >Wyloguj</button></a>
				</form>
			</div>
	</nav>
	
	</header>
	
	<main>
		
		<section class="jumpers">
		
			<div class="container ">
				<div class="row">
				
					<div class="col-md-6  p-2 ">
					
						<figure >
						<a  href="dodaj_przychod.php">
							<i class="demo-icon icon-calendar-plus-o" ></i>
							<p>Add income</p>
						</a>
						</figure>
					
					</div>
					
					<div class="col-md-6 p-2">
					
						<figure>
							<a  href="dodaj_wydatek.php"><i class="demo-icon icon-calendar-minus-o"></i>
							<p>Add expense</p>
							</a>
						</figure>
					
					
					</div>
					
					<div class="col-md-6  p-2">
					
						<figure>
						<a   href="przegladaj_bilans.php">
							<i class="demo-icon icon-money" ></i> 
							<p>Your balance</p>
						</a>
						</figure>
					
					</div>

					<div class="col-md-6  p-2">
					
						<figure>
						<a  href="#">
							<i class="demo-icon icon-wrench" ></i> 
							<p>Settings</p>
						</a>	
						</figure>
					
					</div>
	
				</div>
				
			</div>	
			
				
		</section>
		
	</main>
	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	
	<script src="js/bootstrap.min.js"></script>
	
</body>
</html>