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
	else
		{
			$user_id=$_SESSION['id'];
			
			$newdate = date("Y-m-d",strtotime('-1 month', strtotime (date("Y-m-d"))));
			$date=new DateTime($newdate);
			$date->modify('first day of this month');
			$firstday= $date->format('Y-m-d');
			$date->modify('last day of this month');
			$lastday= $date->format('Y-m-d');
				
		//pobranie z bazy wydatkow i kategori z obecnego miesiaca
		$_SESSION['expenses']=$polaczenie->query("SELECT SUM(expenses.amount) AS sum, expenses_category_assigned_to_users.name AS expense_name FROM expenses INNER JOIN expenses_category_assigned_to_users ON(expenses.expense_category_assigned_to_user_id=expenses_category_assigned_to_users.id AND expenses.user_id=$user_id AND expenses.date_of_expense>='$firstday' AND expenses.date_of_expense<='$lastday' ) GROUP BY expense_name ORDER BY sum;");
		
		//utworzenie tablicy sum wydatkow i ich kategorii
		$sum_all_expenses=(float)0;
		$_SESSION['num_rows_expenses']=$_SESSION['expenses']->num_rows;
		for($i =0; $i < $_SESSION['num_rows_expenses']; $i++)
		{
		$wiersz1=$_SESSION['expenses']->fetch_assoc();
		$_SESSION['expense_sum2'][$i]=$wiersz1['sum'];
		$_SESSION['expense_name2'][$i]=$wiersz1['expense_name'];
		$sum_all_expenses+=$wiersz1['sum'];
		}
		
		//pobranie danych o przychodach i kategoriach z obecnego miesiaca
		$_SESSION['incomes']=$polaczenie->query("SELECT SUM(incomes.amount) AS sum, incomes_category_assigned_to_users.name AS income_name FROM incomes INNER JOIN incomes_category_assigned_to_users ON(incomes.income_category_assigned_to_user_id=incomes_category_assigned_to_users.id AND incomes.user_id=$user_id AND incomes.date_of_income>='{$_SESSION['firstday']}' AND incomes.date_of_income<='$lastday') GROUP BY incomes_category_assigned_to_users.name ORDER BY incomes.amount;");
		
		//utworzenie tablicy sum przychodow i ich kategorii
		$sum_all_incomes=(float)0;
		$_SESSION['num_rows_incomes']=$_SESSION['incomes']->num_rows;
		for($i =0; $i < $_SESSION['num_rows_incomes']; $i++)
		{
		$wiersz1=$_SESSION['incomes']->fetch_assoc();
		$_SESSION['income_sum2'][$i]=$wiersz1['sum'];
		$_SESSION['income_name2'][$i]=$wiersz1['income_name'];
		$sum_all_incomes+=$wiersz1['sum'];
		}
	
		
		$polaczenie->close();
		}
	

?>

<!DOCTYPE html>
<html lang="pl">
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<title>Przeglądaj bilans</title>
	<meta name="description" content="bilans miesięczny bilans roczny">
	<meta name="keywords" content="bilans miesięczny bilans roczny">
	<meta name="author" content="Karol Jęczmionka">
	<meta http-equiv="X-Ua-Compatible" content="IE=edge">
	
	<link rel="stylesheet" href="css_bootstramp/bootstrap.min.css">
	<link rel="stylesheet" href="style_bootstamp.css">
	
	
	<link rel="stylesheet" href="css/fontello.css">
     
	 <link rel="preconnect" href="https://fonts.gstatic.com">
	 <link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Stint+Ultra+Condensed&display=swap" rel="stylesheet">
	 <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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
	
				document.getElementById('wprowadzDate').value = rok+"-"+miesiac+"-"+dzien;
				document.getElementById('wprowadzDate2').value = rok+"-"+miesiac+"-"+dzien;
         }  
	</script>  
	<script type="text/javascript">
// Load google charts
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

// Draw the chart and set the chart values

function drawChart() {

var dataArray = [['Year','Total Average']];

				var expense_names =<?php echo json_encode($_SESSION['expense_name2']); ?>;
				var expense_sums =<?php echo json_encode($_SESSION['expense_sum2']); ?>;
				
				
				var length=expense_names.length;
				
				
			
			for(i=0; i<length; i++)
			{
				
			dataArray.push([expense_names[i],parseInt(expense_sums[i])]);
			}
	

var data = new google.visualization.arrayToDataTable(dataArray);
  

  



  // Optional; add a title and set the width and height of the chart
  var options = {'title':'My expenses', 'width':550, 'height':400, backgroundColor: '#404040'  };

  // Display the chart inside the <div> element with id="piechart"
  var chart = new google.visualization.PieChart(document.getElementById('piechart'));
  chart.draw(data, options);




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
					
						<h2> <?php echo "Last month" ?> </h2>
					
					
				</div>
			
				<form class="form-inline" action="bilans_your_select.php" method="post">
					
					<a href="menu_glowne.php"><button class="btn btn-outline-secondary mr-1" type="button">Main menu</button></a>
					<a href="logout.php"><button class="btn btn-outline-secondary mr-1" type="button">Logout</button></a>
					
					<div class="nav-item dropdown" name="Chose_peroid_dropdown">
					  <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
						Select interval
					  </a>
					  <div class="dropdown-menu bg-secondary">
						
						<a class="dropdown-item" href="przegladaj_bilans.php">This month</a>
						<a class="dropdown-item" href="bilans_last_month.php">previous month</a>
						<a class="dropdown-item" href="bilans_this_year.php">This year</a>
						<a class="dropdown-item" href="bilans_past_year.php">Previous year</a>
						<div data-toggle="modal" data-target="#exampleModalLong"><a class="dropdown-item" href="#">Yours select</a></div>
					  </div>
					</div>
					
					
					
						<!-- Button trigger modal -->


<!-- Modal -->
					<div class="modal fade text-dark" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
					  <div class="modal-dialog" role="document">
						<div class="modal-content">
						  <div class="modal-header">
							<h5 class="modal-title" id="exampleModalLongTitle">Wiebierz okres</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							  <span aria-hidden="true">&times;</span>
							</button>
						  </div>
							<div class="modal-body">
										<div class="container-fluid">
											<div class="row">
												<div class="col-sm-6">
												<label class="d-flex justify-content-start" for="wprowadzDate">Od:</label>
												<input  type="date" name="start_date" id="wprowadzDate" min="2018-01-01" >
												</div>
													  
												<div class="col-sm-6">  
												<label class="d-flex justify-content-start"  for="wprowadzDate2">Do:</label>
												<input  type="date" name="end_date" id="wprowadzDate2" min="2018-01-01" >
												</div>
												
											</div>
										</div>
							</div>

								<div class="modal-footer">
							<a href="bilans_your_select.php">
							<button type="submit" class="btn btn-primary">OK</button>
							</a>
						  </div>
						</div>
					  </div>
					</div>
								
								
								
					
				</form>
			</div>
		</nav>
	</header>
	<main>
		<div class="container">
			<div class="row">
					<div class="col-md-4 p-2">
						<table class="table table-bordered  ">
							<caption>Expenses</caption>
						   <thead>		
								<tr>
									  <th scope="col">Amount</th>
									  <th scope="col">Category</th>

								</tr>
							  
						   </thead>
						   <tbody>
						   <?php
						   for($i =0; $i < $_SESSION['num_rows_expenses']; $i++)
						   {
						   echo
							  '<tr>
								 <th>'.$_SESSION['expense_sum2'][$i].'</th><td>'.$_SESSION['expense_name2'][$i].'</td> 
							  </tr>';
						   }
						   echo '<tr><th>'.number_format((float)$sum_all_expenses, 2, '.', '').'</th><td>'."sum of expenses".'</td></tr>';
							?>

							</tbody>
						</table>

					</div>
					<div class="col-md-4 p-2">
						<table class="table table-bordered  ">
							<caption>Incomes</caption>
						   <thead>
								
								<tr>
									  <th scope="col">Amount</th>
									  <th scope="col">Category</th>

								</tr>
								 
							  
						   </thead>
						   <tbody>
						<?php
						   for($i =0; $i < $_SESSION['num_rows_incomes']; $i++)
						   {
						   echo
							  '<tr>
								 <th>'.$_SESSION['income_sum2'][$i].'</th><td>'.$_SESSION['income_name2'][$i].'</td> 
							  </tr>';
						   }
						  echo '<tr><th>'.number_format((float)$sum_all_incomes, 2, '.', '').'</th><td>'."sum of incomes".'</td></tr>';
							?>
						   </tbody>
						</table>
					</div>
					<div class="col-lg-4 p-1">
						<div id="piechart">
							
						</div>
									<?php
									if($sum_all_incomes>$sum_all_expenses)
									{
									echo '<div style="color:green; ">Your balance for this time is: '.number_format((float)$sum_all_incomes-$sum_all_expenses, 2, '.', '').' </br>
												Great you manage very well!</div>'	;
									}
									else if($sum_all_expenses>$sum_all_incomes)
									{
										echo '<div style="color:red; ">Your balance for this time is: '.number_format((float)$sum_all_incomes-$sum_all_expenses, 2, '.', '').' </br>
											attention you fall into debt!</div>'	;
									}
									else
									{
										echo "Your balance equals 0";
									}
									?>
						
					</div>
			</div>

		</div>
	</main>



	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	
	<script src="js/bootstrap.min.js"></script>
	
	
</body>
	</html>