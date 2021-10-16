<?php
	
	session_start();
	if(isset($_POST['email']))
	{
		$wszystko_OK=true;
		
		$login=$_POST['login'];
		
		if((strlen($login)<3)||(strlen($login)>20))
		{
			$wszystko_OK=false;
			$_SESSION['e_login']="nick musi posiadac od 3 do 20 znakow";
		}
		
		if(ctype_alnum($login)==false)
		{
				$wszystko_OK=false;
				$_SESSION['e_login']="nick musi skladac sie z liter i cyfr";
		}
		
		$email=$_POST['email'];
		$emailB=filter_var($email,FILTER_SANITIZE_EMAIL);
		
		if((filter_var($email,FILTER_SANITIZE_EMAIL)==false)||($emailB!=$email))
		{
			$wszystko_OK=false;
			$_SESSION['e_email']="podaj poprawny email";
		}
		
				$haslo1=$_POST['haslo1'];
				$haslo2=$_POST['haslo2'];
				
			if((strlen($haslo1))<8||(strlen($haslo1)>20))
			{
				$wszystko_OK=false;
				$_SESSION['e_haslo']="haslo musi posiadac od 8 do 20 znakow";
			}
			
			
			
			if($haslo1!=$haslo2)
			{
				$wszystko_OK=false;
				$_SESSION['e_haslo']="hasla nie sa identyczne";
			}
			
		$haslo_hash=password_hash($haslo1,PASSWORD_DEFAULT);	
		
		$sekret="6LcUD3ccAAAAABwU8tWoFF4aZsVlTwajjHhsFa7f";
		
		$sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$sekret.'&response='.$_POST['g-recaptcha-response']);
		
		$odpowiedz=json_decode($sprawdz);
		
		if($odpowiedz->success==false)
		{
			$wszystko_OK=false;
			$_SESSION['e_bot']="Potwierdz, że nie jestes botem";
		}

		require_once "connect.php";
		
		mysqli_report(MYSQLI_REPORT_STRICT);
		
		try 
		{
			$polaczenie =  @new mysqli($host, $db_user, $db_password, $db_name);
			if($polaczenie->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
				
			}
			else
			{
				$rezultat=$polaczenie->query("SELECT id FROM users WHERE email='$email'");
				
				if(!$rezultat) throw new Exception($polaczenie->error);
				
				$ile_takich_maili=$rezultat->num_rows;
				if($ile_takich_maili>0)
				{
					$wszystko_OK=false;
					$_SESSION['e_email']="istnieje juz konto z takim email";
				}
				$rezultat=$polaczenie->query("SELECT id FROM users WHERE username='$login'");
				
				if(!$rezultat) throw new Exception($polaczenie->error);
				
				$ile_takich_nickow=$rezultat->num_rows;
				if($ile_takich_nickow>0)
				{
					$wszystko_OK=false;
					$_SESSION['e_nick']="istnieje juz konto z takim loginem";
				}
							if($wszystko_OK==true)
							{
								
								if ($polaczenie->query("INSERT INTO users VALUES (NULL, '$login', '$haslo_hash', '$email')"))
								{
									
									$new_u=$polaczenie->query("SELECT id FROM users WHERE username='$login'");
									$new_user_row=$new_u->fetch_assoc();
									$new_user_id=$new_user_row['id'];
									
									//dodanie metod platnosci
									$pay_methods=$polaczenie->query("SELECT name FROM payment_methods_default");
									while($pay_array=$pay_methods->fetch_assoc())
									{
										$pay_method=$pay_array['name'];
										$sql_pay_methods=$polaczenie->query("INSERT INTO payment_methods_assigned_to_users VALUES(NULL,$new_user_id,'$pay_method')");
									}
									
									
									
									//dodanie kategori do tabli z wydatkami
									$category_names=$polaczenie->query("SELECT name FROM expenses_category_default");
									
									while($categorys_array=$category_names->fetch_assoc())
									{
										$category_name=$categorys_array['name'];
										$sql_category=$polaczenie->query("INSERT INTO expenses_category_assigned_to_users VALUES(NULL,$new_user_id,'$category_name')");
									}
									
									//dodanie kategori do tabeli z przychodami
									$category_names=$polaczenie->query("SELECT name FROM incomes_category_default");
									
									while($categorys_array=$category_names->fetch_assoc())
									{
										$category_name=$categorys_array['name'];
										$sql_category=$polaczenie->query("INSERT INTO incomes_category_assigned_to_users VALUES(NULL,$new_user_id,'$category_name')");
									}
									
									$_SESSION['udanarejestracja']=true;
									header('Location:logowanie.php');
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
			echo "blad serwera prosimy spróbować pózniej";
			echo 'info:'.$e;
		}
	}
	
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Rejstracja</title>
	<meta name="description" content="Rejestracja" />
	<meta name="keywords" content="Rejestracja" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
		
		
	<link rel="stylesheet" href="css_bootstramp/bootstrap.min.css">
	<link rel="stylesheet"  href="style_bootstamp.css">
	
	<link rel="stylesheet" href="css/fontello.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
	

	
</head>

<body>

		 
        
        <div class="container mt-5">
            
			<div class="col-md-5 mx-auto">
			
				<div class="myform form ">
					 <div class="logo mb-3">
						 <div class="col-md-12 text-center">
							<h1>Rejestracja</h1>
						 </div>
					</div>
                   <form   method="post" >
                           <div class="form-group">
                              <label for="email">Adres email</label>
                              <input  name="email" type="text"  class="form-control" id="email"  placeholder="Wprowadź email">
                           </div>
						   

								<?php

									if(isset($_SESSION['e_email']))
									{
										echo'<div class="error">'.$_SESSION['e_email'].'</div>';
										unset($_SESSION['e_email']);
									}
								?>
						   
						   <div class="form-group">
                              <label for="Login">Login</label>
                              <input type="login" name="login" id="Login"  class="form-control"   placeholder="Wprowadź Login">
                           </div>
								<?php
									if(isset($_SESSION['e_login']))
									{
										echo'<div class="error">'.$_SESSION['e_login'].'</div>';
										unset($_SESSION['e_login']);
									}
								?>
								
                           <div class="form-group">
                              <label for="haslo1">Hasło</label>
                              <input type="password" name="haslo1" id="haslo1" class="form-control"  placeholder="Wprowadź hasło">
                           </div>
						   
								<?php
									if(isset($_SESSION['e_haslo']))
									{
										echo'<div class="error">'.$_SESSION['e_haslo'].'</div>';
										unset($_SESSION['e_haslo']);
									}
								?>

						   <div class="form-group">
                              <label for="haslo2">Powtórz hasło</label>
                              <input type="password" name="haslo2" id="Hasło"  class="form-control"  placeholder="Wprowadź hasło">
                           </div>
						   
						   <div class="form-group">
                           <div  class="g-recaptcha" data-sitekey="6LcUD3ccAAAAAKq6Q7aa8SmZCItjRPxv3-an2Sjz">
                              
                           </div>
						   </div>
								<?php
									if(isset($_SESSION['e_bot']))
									{
										echo'<div class="error">'.$_SESSION['e_bot'].'</div>';
										unset($_SESSION['e_bot']);
									}
								?>
						   
						   
                           <div class="col-md-12 text-center ">
                              <button type="submit" class=" btn btn-block mybtn btn-primary tx-tfm">Zarejestruj się</button>
                           </div>

                           
                           <div class="form-group">
                              <p class="text-center">Masz już konto? <a href="logowanie.php" id="signup">Zaluguj się.</a></p>
                           </div>
                        </form>
                 
				</div>
			

			
		</div>
		</div>
			
			
			<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	
	<script src="js/bootstrap.min.js"></script>
	
</body>
</html>