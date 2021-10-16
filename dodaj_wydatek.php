<?php
	session_start();
	
	if (!isset($_SESSION['zalogowany']))
	{
		header('Location: index.php');
		exit();
	}
	
	require_once "connect.php";
	mysqli_report(MYSQLI_REPORT_STRICT);
	try
	{	
	$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
	
	if ($polaczenie->connect_errno!=0)
	{
		throw new Exception(mysqli_connect_errno());
	}
	else
	{
		
	
	
	
	if(isset($_POST['date']))
	{
				$user_id=$_SESSION['id'];
			$amount=$_POST['amount'];
			$category=$_POST['kategoria'];
			$rodzaj_platnosc=$_POST['platnosc'];
			
			$date_of_expense=$_POST['date'];
			$expense_comment=$_POST['comment'];
			
			
			
			$wiersz=$polaczenie->query("SELECT * FROM payment_methods_assigned_to_users WHERE name='$rodzaj_platnosc' AND user_id=$user_id LIMIT 1");
		$tablica_z_danymi=$wiersz->fetch_assoc();
		$payment_method_assigned_to_user_id=$tablica_z_danymi["id"];
		
		
		$wiersz=$polaczenie->query("SELECT * FROM expenses_category_assigned_to_users WHERE name='$category' AND user_id=$user_id LIMIT 1");
		
		$tablica_z_danymi=$wiersz->fetch_assoc();
		$expense_category_assigned_to_user_id=$tablica_z_danymi["id"];
		

			
		$_sql=$polaczenie->query("INSERT INTO expenses VALUES(NULL,$user_id,$expense_category_assigned_to_user_id,$payment_method_assigned_to_user_id,$amount,'$date_of_expense','$expense_comment')");
		
					
	
			//header('Location: menu_glowne.php');
		//exit();
		
	$polaczenie->close();
	}
	}
	}
		catch(Exception $e)
		{
			echo "blad serwera prosimy spróbować pózniej";
			echo 'info:'.$e;
		}
	

	
	
	
?>

<!DOCTYPE html>
<html lang="pl">
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<title>Dodaj wydatek</title>
	<meta name="description" content="dodaj wydatek">
	<meta name="keywords" content="dodaj wydatek">
	<meta name="author" content="Karol Jęczmionka">
	<meta http-equiv="X-Ua-Compatible" content="IE=edge">
	
	<link rel="stylesheet" href="css_bootstramp/bootstrap.min.css">
	<link rel="stylesheet" href="style_bootstamp.css">
	<link rel="stylesheet" href="css/fontello.css">
	
	<link href="https://fonts.googleapis.com/css2?family=Lora&display=swap" rel="stylesheet">
	
	<!--[if lt IE 9]>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
	<![endif]-->
	<script type = "text/javascript">  
	function myfunction() { 
	var dzisiaj = new Date();
		
		var dzien = dzisiaj.getDate();
		if (dzien<10) dzien = "0"+dzien;
		var miesiac = dzisiaj.getMonth()+1;
		if (miesiac<10) miesiac = "0"+miesiac;
		var rok = dzisiaj.getFullYear();
	
	document.getElementById('wprowadzDate').value =rok+"-"+miesiac+"-"+dzien;
         }  
	</script>  
	
</head>


<body onload="myfunction();">


	<header>		
	<nav class="navbar navbar-dark bg-jumpers navbar-expand-lg">
		
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainmenu" aria-controls="mainmenu" aria-expanded="false" aria-label="Przełącznik nawigacji">
				<span class="navbar-toggler-icon"></span>
			</button>
		
			<div class="collapse navbar-collapse" id="mainmenu">
				
				<div class="navbar-nav mr-auto">
					
						<h2> Dodaj wydatek   </h2>
					
					
				</div>
			
				<form class="form-inline">
					
					<button class="btn btn-outline-secondary mr-1" type="button">Menu główne</button>
					<button class="btn btn-outline-secondary mr-1" type="button">Wyloguj</button>
				</form>
			</div>
	</nav>
	
	</header>
	

	
	<main>

		
		<section>
			
			<div class="container text-light" >
							
	<form method="post">		
	<div class="row " >

	<div class="col-sm-12">
		<fieldset>
		<legend class="d-flex justify-content-start">Wybierz kategorie:</legend>
		<?php
		
			for($i =0; $i < $_SESSION['num_rows_category_names']; $i++)
			{
				
				
				echo '<div class="col-sm-3 float-sm-left">
				<div class="d-flex justify-content-start form-check">
				<input  class="form-check-input" type="radio" id="'.$_SESSION['category_names'][$i].'" name="kategoria" value="'.$_SESSION['category_names'][$i].'" checked>
				<label class="form-check-label" for="'.$_SESSION['category_names'][$i].'">'.$_SESSION['category_names'][$i].'</label></div></div>';
			}
			
		
		?>
		</fieldset>
		</div>
	
					
		<div class="col-md-4 p-4 " >
							
							
							
								 <div class="form-group ">
								 <label class="d-flex justify-content-start"  for="kwota">Wprowadź kwotę:</label>
								 <input class=" d-flex justify-content-start " type="text" name="amount" id="kwota">
								 </div>
					
								 
								
								 <div class="form-group ">
								 <label class="d-flex justify-content-start" for="wprowadzDate">Wprowadź date:</label>
								  <input class="d-flex justify-content-start" id="wprowadzDate" type="text" name="date" onfocus="this.placeholder='data'" onblur="this.placeholder='data'" required name="wprowadzDate"> 
								  </div>
								  
					<fieldset>
					<legend class="d-flex justify-content-start"> Wybierz sposób płatności:</legend>
										
										
						<?php
						for($i = 0; $i < $_SESSION['num_rows_pay_methods']; $i++)
						{
							echo 
	
							'<div class="d-flex justify-content-start form-check">
							<input class="form-check-input" type="radio" id="'.$_SESSION['PAY_methods'][$i].'" name="platnosc" value="'.$_SESSION['PAY_methods'][$i].'" checked>
													
							<label class="form-check-label" for="'.$_SESSION['PAY_methods'][$i].'">'.$_SESSION['PAY_methods'][$i].'</label></div>';
						}
						?>
										
										
		  
					</fieldset>

					
		</div>
					
					<div class="col-md-8 p-4">
						
							<div class="form-group">
							<label for="exampleFormControlTextarea2">Uwagi:</label>
							<textarea name='comment'class="form-control rounded-0" id="exampleFormControlTextarea2" rows="5"></textarea>
							</div>
					
					</div>

					<div class="col-sm-12 p-2">
					
							<button type="submit" class="btn btn-success btn-lg">Dodaj wydatek</button>
						<button type="button" class="btn btn-danger btn-lg">anuluj</button>
					
					</div>
					

				
				</div>
				</form >
			</div>	
			
				
		</section>
		
	</main>
	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	
	<script src="js/bootstrap.min.js"></script>
	

		
</body>
</html>
