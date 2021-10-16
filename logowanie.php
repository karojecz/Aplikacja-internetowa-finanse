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
				
				$wiersz = $rezultat->fetch_assoc();
				
				if(password_verify($haslo, $wiersz['password']))
				{
					$_SESSION['zalogowany'] = true;
					
					$user_id=$wiersz["id"];
					
					$_SESSION['user_data_expense']=$polaczenie->query("SELECT expenses.amount, expenses.date_of_expense, expenses.expense_comment, expenses_category_assigned_to_users.name FROM expenses INNER JOIN expenses_category_assigned_to_users ON(expenses.expense_category_assigned_to_user_id=expenses_category_assigned_to_users.id AND expenses.user_id=$user_id) GROUP BY expenses_category_assigned_to_users.name ORDER BY expenses.amount;");
					
					
					
					//popbranie metod platniczych uzytkownia
					$PAY_methods=$polaczenie->query("SELECT * FROM payment_methods_assigned_to_users WHERE user_id=$user_id");
					$_SESSION['num_rows_pay_methods']=$PAY_methods->num_rows;
					
					for($i =0; $i <= $_SESSION['num_rows_pay_methods']; $i++)
					{
					$w1=$PAY_methods->fetch_assoc();
					$_SESSION['PAY_methods'][$i]=$w1['name'];
					}
					
					//pobranie nazw katergori uzytkownika
					$category_names=$polaczenie->query("SELECT name FROM expenses_category_assigned_to_users WHERE user_id=$user_id");
					$_SESSION['num_rows_category_names']=$category_names->num_rows;
					
					for($i =0; $i <= $_SESSION['num_rows_category_names']; $i++)
					{
					$w1=$category_names->fetch_assoc();
					$_SESSION['category_names'][$i]=$w1['name'];
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
