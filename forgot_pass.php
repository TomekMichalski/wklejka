<?php
	require_once('classes/db.php');
	require_once('classes/class.php');
	
	check_login('chatroom.php',true);
	
	if(isset($_POST['email'])){
		
		
		$_POST['email']=htmlspecialchars($_POST['email'],ENT_QUOTES,"UTF-8");
		
		$sql= $pdo->query("SELECT id FROM users  WHERE email='".$_POST['email']."' ");
		if($sql->rowCount()==1){
			
			function generateRandomString($length = 30) {
				$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$charactersLength = strlen($characters);
				$randomString = '';
				for ($i = 0; $i < $length; $i++) {
					$randomString .= $characters[rand(0, $charactersLength - 1)];
				}
				return $randomString;
			
			}
			
			do{
				
				$hash=generateRandomString();
				$ifexist= $pdo->query("SELECT id FROM forgot_pass  WHERE hash='$hash' ");
					
			}while($ifexist->rowCount()!=0);
			
			$sql= $pdo->query("SELECT nick FROM users  WHERE email='".$_POST['email']."' ");
			$user=$sql->fetch();
			$pdo->query("INSERT INTO forgot_pass (`hash`,`email`) VALUES ('$hash','".$_POST['email']."') ");
			
			send_email('Przypomnienie hasla','Login: '.$user['nick'].'<br /> Link do zmiany hasła: <a href="https://zonegames.pl/wklejka/forgot_pass.php?secure_key='.$hash.'">Klik</a>',$_POST['email']);
			echo 'Na twoj email została wyslana wiadomosc z linkiem do zmiany hasla.';
			
		}else
			echo 'W naszej bazie nie ma tego emaila';
		
	}
	
	if(isset($_GET['secure_key'])){
		
		
		$_GET['secure_key']=htmlspecialchars($_GET['secure_key'],ENT_QUOTES,"UTF-8");
		
		$sql= $pdo->query("SELECT email FROM forgot_pass  WHERE hash='".$_GET['secure_key']."' ");
		if($sql->rowCount()==1&&!isset($_POST['new_password'])){
			
			echo '<form method="POST">
				<input type="password" name="new_password" placeholder="Podaj nowe haslo"/><input type="submit"/>
				</form>';
				exit;
			
		}
		
	}
	
	if(isset($_POST['new_password'])){
		
		$_POST['new_password']=htmlspecialchars($_POST['new_password'],ENT_QUOTES,"UTF-8");
		
		$sql= $pdo->query("SELECT email FROM forgot_pass  WHERE hash='".$_GET['secure_key']."' ");
		if($sql->rowCount()==1){
			
			$user_email=$sql->fetch();
			$sql= $pdo->query("SELECT * FROM users  WHERE email='".$user_email['email']."' ");
			$user=$sql->fetch();
			$password=password_hash($_POST['new_password'], PASSWORD_DEFAULT);
			$sql= $pdo->query("UPDATE users  SET password='$password' WHERE id='".$user['id']."' ");
			$pdo->query("DELETE from forgot_pass WHERE hash='".$_GET['secure_key']."' ");
			echo 'Twoje hasło zostalo zmienione. Za chwile zostaniesz przekierowany na strone glowna.';
			header('Refresh:5;url=https://zonegames.pl/wklejka');
			exit;
			
		}
		
	}

?>
<form method="POST">
<input type="email" name="email" placeholder="Podaj email"/><input type="submit"/>
</form>
