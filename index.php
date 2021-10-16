<?php
		session_start();
	
	if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
	{
		header('Location: menu_glowne.php');
		exit();
	}

?>


<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Logowanie</title>
	<meta name="description" content="formularz logowania" />
	<meta name="keywords" content="formularz logowania" />
	
	<meta http-equiv="X-Ua-Compatible" content="IE=edge">
		
	<link rel="stylesheet" href="css_bootstramp/bootstrap.min.css">
	<link rel="stylesheet" href="style_bootstamp.css">
	<link rel="stylesheet" href="css/fontello.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
	
</head>

<body>

		 
        
        <div class="container mt-5">
            
			<div class="col-md-5 mx-auto">
			
				<div class="myform form ">
					 <div class="logo mb-3">
						 <div class="col-md-12 text-center">
							<h1>Logowanie</h1>
						 </div>
					</div>
                   <form  name="login" action="logowanie.php" method="post">
                           <div class="form-group">
                              <label for="email" >Adres email</label>
                              <input type="email" name="email" id="email"  class="form-control" placeholder="Wprowadź email">
                           </div>
                           <div class="form-group">
                              <label for="password"  >Hasło</label>
                              <input type="password" id="password" name="password"  class="form-control" placeholder="Wprowadź hasło">
                           </div>
                           <div class="form-group">
                              
                           </div>
                           <div class="col-md-12 text-center ">
                              <button type="submit" class=" btn btn-block mybtn btn-primary tx-tfm">Zaloguj się</button>
                           </div>

                           
                           <div class="form-group">
                              <p class="text-center">Nie masz konta? <a href="#" id="signup">Zarekjestruj się.</a></p>
                           </div>
                     </form>
					 
									<?php
							if(isset($_SESSION['blad']))	echo $_SESSION['blad'];
							
							//echo $_SESSION['zalogowany'];
							
						?>
						
                 
				</div>
			

			
		</div>
		</div>
			
			
			<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	
	<script src="js/bootstrap.min.js"></script>
	
</body>
</html>