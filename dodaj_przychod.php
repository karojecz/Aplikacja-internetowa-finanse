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
		$wszystko_ok=true;
			$user_id=$_SESSION['id'];
			$amount=$_POST['amount'];
			$category=$_POST['kategoria'];
			$date_of_income=$_POST['date'];
			$income_comment=$_POST['comment'];
			
			$income_comment = htmlentities($income_comment, ENT_QUOTES, "UTF-8");
			//$date_of_income = preg_replace("([^0-9-])", "", $_POST['date']);
			//$amount=htmlentities($amount, ENT_QUOTES, "UTF-8");
			
			if(!is_numeric($amount))
			{
				$_SESSION['e_amount']="You need enter amount here";
				$wszystko_ok=false;
			}
			$test_arr = explode('-', $date_of_income);
			if ((count($test_arr) == 3) && (!empty($test_arr[2]))){
				if ( ($test_arr[0]<2018 || $test_arr[0]>date('Y')) || (!checkdate($test_arr[1], $test_arr[2], $test_arr[0])))  
				{
				$_SESSION['e_date']="Wrong date";
				$wszystko_ok=false;
				}
			}
			else{
								$_SESSION['e_date']="Enter date in format yyyy-mm-dd";
				$wszystko_ok=false;
			}
			
			

			
			if($wszystko_ok==true)	
			{
		
		$wiersz=$polaczenie->query("SELECT * FROM incomes_category_assigned_to_users WHERE name='$category' AND user_id=$user_id LIMIT 1");
		
		$tablica_z_danymi=$wiersz->fetch_assoc();
		$income_category_assigned_to_user_id=$tablica_z_danymi["id"];
		

			
		$_sql=$polaczenie->query("INSERT INTO incomes VALUES(NULL,$user_id,$income_category_assigned_to_user_id,$amount,'$date_of_income','$income_comment')");
		
		$_SESSION['sucess']="Your item has been added";
		
			}
		
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
	
	<title>Dodaj przychod</title>
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
					
						<h2> Add income   </h2>
					
					
				</div>
			
				<form class="form-inline">
					<a href="menu_glowne.php">
					<button class="btn btn-outline-secondary mr-1" type="button">Main menu</button>
					</a>
					<a href="logout.php">
					<button class="btn btn-outline-secondary mr-1" type="button">logout</button>
					</a>
				</form>
			</div>
	</nav>
	
	</header>
	

	
	<main>

		
		<section>
			
			<div class="container text-light" >
							
	<form method="post">		
	<div class="row " >
	<?php if(isset($_SESSION['sucess'])){
	
	echo '<script>alert("Your item has been added")</script>';
	unset($_SESSION['sucess']);
	} ?>
	<div class="col-sm-12">
		<fieldset>
		<legend class="d-flex justify-content-start">Categorys:</legend>
		<?php
		
			for($i =0; $i < $_SESSION['num_rows_income_category_names']; $i++)
			{
				echo '<div class="col-md-6  float-sm-left">
				<div class="d-flex justify-content-start form-check">
				<input  class="form-check-input" type="radio" id="'.$_SESSION['income_category_names'][$i].'" name="kategoria" value="'.$_SESSION['income_category_names'][$i].'" checked>
				<label class="form-check-label" for="'.$_SESSION['income_category_names'][$i].'">'.$_SESSION['income_category_names'][$i].'</label></div></div>';
			}
		?>
		</fieldset>
		</div>
			
		<div class="col-md-4 p-4 " >
							
							
							
								 <div class="form-group ">
								 <label class="d-flex justify-content-start"  for="kwota">Amount:</label>
								 <input class=" d-flex justify-content-start " type="text" name="amount" id="kwota">
								 </div>
								<?php

									if(isset($_SESSION['e_amount']))
									{
										echo'<div class="error">'.$_SESSION['e_amount'].'</div>';
										unset($_SESSION['e_amount']);
									}
								?>
								
					
								 <div class="form-group ">
								 <label class="d-flex justify-content-start" for="wprowadzDate">Date:</label>
								  <input class="d-flex justify-content-start" id="wprowadzDate" type="text" name="date" onfocus="this.placeholder='data'" onblur="this.placeholder='data'" required name="wprowadzDate"> 
								  </div>	
								<?php

									if(isset($_SESSION['e_date']))
									{
										echo'<div class="error">'.$_SESSION['e_date'].'</div>';
										unset($_SESSION['e_date']);
									}
								?>


		</div>
					
					<div class="col-md-8 p-4">
						
							<div class="form-group">
							<label for="exampleFormControlTextarea2">Comment:</label>
							<textarea name='comment'class="form-control rounded-0" id="exampleFormControlTextarea2" rows="5"></textarea>
							</div>
					
					</div>

					<div class="col-sm-12 p-2">
					
							<button type="submit" class="btn btn-success btn-lg">Add income</button>
						<button type="reset" class="btn btn-danger btn-lg">cancel</button>
					
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
