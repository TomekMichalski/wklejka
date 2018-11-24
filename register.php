<?php
	
			require_once('classes/db.php');
			
			if($_GET['hash']=='67fsdg9sd65fb568'){
					$school='Mechanik';
					$class='4Ti';
			}
			
			if($_COOKIE["IP"]=='')
				$_COOKIE["IP"]='empty';
	
			$stmt = $pdo->query("SELECT verifi FROM register WHERE last_ip='".$_SERVER['REMOTE_ADDR']."' OR last_ip='".$_COOKIE["IP"]."' ");
			$stmt2 = $pdo->query("SELECT id FROM users WHERE last_ip='".$_SERVER['REMOTE_ADDR']."' OR last_ip='".$_COOKIE["IP"]."' ");
			
			if($stmt2->rowCount()>0)
				header('Location:index.php');
				
				
			if($stmt->rowCount()>0){
					
					$row=$stmt->fetch();
					if($row['verifi']==0){
						echo 'Twoje konto czeka na weryfikacje przez administratora';
						exit;
					
					}else{
						echo 'Gratulacje twoje konto zostalo zweryfikowane.';
						exit;
						
					}
	
			}
			
			if(!isset($school)&&$stmt->rowCount()==0){
				
				echo 'Nieprawidlowy kod.<br /> Za chwile zostaniesz przekierowany na strone glowna.';
				header("refresh:5;url=https://zonegames.pl/wklej/wklejka");
				exit;
				
			}
			
			if(isset($_POST['name'])){
				
				$stmt = $pdo->query("SELECT * FROM register ");
				$row=$stmt->fetchAll();
				
				$stmt1 = $pdo->query("SELECT * FROM users ");
				$row1=$stmt1->fetchAll();
				
				$nick=mb_strtolower(htmlspecialchars($_POST['login'],ENT_QUOTES,"UTF-8"));
				$name=htmlspecialchars($_POST['name'],ENT_QUOTES,"UTF-8");
				$surname=htmlspecialchars($_POST['surname'],ENT_QUOTES,"UTF-8");
				$password=htmlspecialchars($_POST['passwd'],ENT_QUOTES,"UTF-8");
				$email=mb_strtolower(htmlspecialchars($_POST['email'],ENT_QUOTES,"UTF-8"));
				
				if($nick==mb_strtolower($row[0]['nick'])||$nick==mb_strtolower($row1[0]['nick']))
					$error_login='<span style="color:red">Ten login jest juz zajety </span><br />';
				
				if($email==mb_strtolower($row[0]['email'])||$email==mb_strtolower($row1[0]['email']))
					$error_email='<span style="color:red">Ten email jest juz zajety </span><br />';
				
				if($error_email==''&&$error_login==''){
						
					$haslo_hash = password_hash($password, PASSWORD_DEFAULT);
						
					$pdo->query("INSERT INTO register (`nick`,`password`,`name`,`surname`,`email`,`school`,`class`,`date_register`,`last_ip`) VALUES (
								'$nick','$haslo_hash','$name','$surname','$email','$school','$class','".date("Y-m-d H:i")."','".$_SERVER['REMOTE_ADDR']."') ");
					setcookie ('IP',$_SERVER['REMOTE_ADDR'], time()+2678400);
					header('Refresh:0');
					
					
				}
				
			}
			

?>

<head>
	<title>Rejestracja</title>
	
	 
</head>
<body >
<form method="POST">
	Nick (bedzie slużyl do logowania): <input type="text" name="login"  required /><br /> <?=$error_login ?>
	Imie: <input type="text" name="name"  required /><br />
	Nazwisko: <input type="text" name="surname"  required /><br />
	Haslo: <input type="password" name="passwd"  required /><br />
	Email: (bedzie służyc do przypomnienia hasla oraz aktywacji konta): <input type="email" name="email"  required /><br /><?=$error_email ?>
	<input type="submit" />
</form>
</body>