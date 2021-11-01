<?php
	session_start();
	
	if ((!isset($_POST['email'])) || (!isset($_POST['password'])))
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
		
		$email = $_POST['email'];
		$haslo = $_POST['password'];
		
		$email = filter_var($email, FILTER_SANITIZE_EMAIL);
		$haslo = htmlentities($haslo, ENT_QUOTES, "UTF-8");
		
		if($rezultat = @$polaczenie->query(
		"SELECT * FROM users WHERE email='$email'"))
		{
			$ilu_userow = $rezultat->num_rows;
			
			if($ilu_userow>0)
			{
				//$_SESSION['selected_peroid']="last_month";
			
				
				$wiersz = $rezultat->fetch_assoc();			
				if(password_verify($haslo, $wiersz['password']))
				{
					$_SESSION['zalogowany'] = true;
					
					$user_id=$wiersz["id"];
					//pobranie danych o wydatkach i kategoriach
					//$_SESSION['user_data_expense']=$polaczenie->query("SELECT SUM(expenses.amount) AS sum, expenses_category_assigned_to_users.name AS expense_name FROM expenses INNER JOIN expenses_category_assigned_to_users ON(expenses.expense_category_assigned_to_user_id=expenses_category_assigned_to_users.id AND expenses.user_id=$user_id) GROUP BY expenses_category_assigned_to_users.name ORDER BY sum;");
					
					//pobranie danych o przychodach i kategoriach 
					//$_SESSION['user_data_incomes']=$polaczenie->query("SELECT SUM(incomes.amount), incomes_category_assigned_to_users.name AS income_name FROM incomes INNER JOIN incomes_category_assigned_to_users ON(incomes.income_category_assigned_to_user_id=incomes_category_assigned_to_users.id AND incomes.user_id=$user_id) GROUP BY incomes_category_assigned_to_users.name ORDER BY incomes.amount;");
					
					
					
					$_SESSION['NUM_ROWS_EXPENSE_DATES']=$_SESSION['dates_of_expenses']->num_rows;
					for($i=0; $i<$_SESSION['NUM_ROWS_EXPENSE_DATES']; $i++)
					{
					$wiersz1=$_SESSION['dates_of_expenses']->fetch_assoc();
					$_SESSION['expense_dates'][$i]=$wiersz1['date_of_expense'];
					}
					
					
					//utworzenie tablicy sum wydatkow i ich kategorii
					//$_SESSION['num_rows_expenses']=$_SESSION['user_data_expense']->num_rows;
					//for($i =0; $i < $_SESSION['num_rows_expenses']; $i++)
					//{
					//$wiersz1=$_SESSION['user_data_expense']->fetch_assoc();
					//$_SESSION['expense_sum'][$i]=$wiersz1['sum'];
					//$_SESSION['expense_name'][$i]=$wiersz1['expense_name'];
					//}
					
					//utworzenie tablicy sum przychodow i ich kategorii
					//$_SESSION['num_rows_incomes']=$_SESSION['user_data_incomes']->num_rows;
					//for($i =0; $i < $_SESSION['num_rows_incomes']; $i++)
					//{
					//$wiersz1=$_SESSION['user_data_incomes']->fetch_assoc();
					//$_SESSION['income_sum'][$i]=$wiersz1['SUM(incomes.amount)'];
					//$_SESSION['income_name'][$i]=$wiersz1['income_name'];
					//}
					
					
					
					//utworzenie tablicy metod platniczych uzytkownia
					$PAY_methods=$polaczenie->query("SELECT * FROM payment_methods_assigned_to_users WHERE user_id=$user_id");
					$_SESSION['num_rows_pay_methods']=$PAY_methods->num_rows;
					
					for($i =0; $i < $_SESSION['num_rows_pay_methods']; $i++)
					{
					$wiersz1=$PAY_methods->fetch_assoc();
					$_SESSION['PAY_methods'][$i]=$wiersz1['name'];
					}
					
					
					
					//utworzenie tablicy nazw katergori wydatkow uzytkownika
					$category_names=$polaczenie->query("SELECT name FROM expenses_category_assigned_to_users WHERE user_id=$user_id");
					$_SESSION['num_rows_expense_category_names']=$category_names->num_rows;
					
					for($i =0; $i < $_SESSION['num_rows_expense_category_names']; $i++)
					{
					$wiersz1=$category_names->fetch_assoc();
					$_SESSION['expense_category_names'][$i]=$wiersz1['name'];
					}
					
					//utworzenie tablicy nazw katergori przychodow uzytkownika
					$category_names=$polaczenie->query("SELECT name FROM incomes_category_assigned_to_users WHERE user_id=$user_id");
					$_SESSION['num_rows_income_category_names']=$category_names->num_rows;
					
					for($i =0; $i < $_SESSION['num_rows_income_category_names']; $i++)
					{
					$wiersz1=$category_names->fetch_assoc();
					$_SESSION['income_category_names'][$i]=$wiersz1['name'];
					}
					
					
					
					
					//$user_data_expense_row=$user_data_expense->fetch_assoc();
					
				
					$_SESSION['id'] = $wiersz['id'];
					$_SESSION['user'] = $wiersz['username'];
					//$_SESSION['expense_amount'] = $user_data_expense_row['expenses.amount'];
					//_SESSION['expense_date'] = $user_data_expense_row['expenses.date_of_expense'];
					//$_SESSION['expense_coment'] = $user_data_expense_row['expenses.expense_comment'];
					//$_SESSION['expense_category'] = $user_data_expense_row['expenses_category_assigned_to_users.name'];
					//$_SESSION['rodzaje_platnosci']=$tablica_rodzajow_platnosci['name'];
					
					

					
					
					unset($_SESSION['blad']);
					$rezultat->free_result();
					header('Location: menu_glowne.php');
				}
			else
				{
				$_SESSION['blad'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
				header('Location: index.php');		
				}
			} else {
				
				$_SESSION['blad'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
				header('Location: index.php');
				
			}
			$polaczenie->close();
		}
?>
