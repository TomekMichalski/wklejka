<?php
session_start();
require_once('classes/db.php');
require_once('classes/class.php');

check_login('chatroom.php',true);

if(isset($_POST['login'])){
	
	$login=$_POST['login'];
	$password=$_POST['password'];
	$error='';
	
	$login = htmlentities($login, ENT_QUOTES, "UTF-8");
	$password = htmlentities($password, ENT_QUOTES, "UTF-8");

	//$sql = "SELECT * FROM users WHERE nick=? AND password=? ";
	//$query = $pdo->prepare($sql);
	$query=$pdo->query("SELECT * FROM users WHERE nick='$login' OR email='$login' ");
	$result = $query->fetchAll();
	
	if($query->rowCount() == 1 && password_verify($password, $result[0]['password']) ) {
		
		$_SESSION['logged']=true;
		$_SESSION['id']=$result[0]['id'];
		$_SESSION['name']=$result[0]['name'];
		$_SESSION['surname']=$result[0]['surname'];
		$_SESSION['school']=$result[0]['school'];
		$_SESSION['class']=$result[0]['class'];
		$_SESSION['room']='global';
		$_SESSION['switch']='chat';
		
		$pdo->query("UPDATE users SET last_login='".date("Y-m-d H:i")."' , last_ip='".$_SERVER['REMOTE_ADDR']."' WHERE id='".$_SESSION['id']."' ");
		
		unset($error);
		header('Location:chatroom.php');
		
	}	
	else {
		$error = '<p class="login__incorrect-data">Nieprawidłowe hasło lub login!</p>';
	}
	
}
		
	

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Wklej!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css" />
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
	<link rel="icon" href="img/logo.png" sizes="32x32"/>
    <!--<script src="main.js"></script>-->
</head>
<body class="body body--login">
    <div class="login">
        <form action="#" method="POST">
            <h1 class="heading__primary heading__primary--login">Zaloguj się</h1>
            <ion-icon class="login__icon login__icon--login" name="person"></ion-icon>
            <input type="text" class="login__input login__input--login" placeholder="login lub email" name="login">
            <ion-icon class="login__icon login__icon--password" name="lock"></ion-icon>
            <input type="password" class="login__input login__input--password" placeholder="hasło" name="password">
            <input type="submit" class="login__btn" value="zaloguj">
            <a href="forgot_pass.php">
                <p class="login__forgot">Zapomniałeś hasła?</p>
            </a>
		 <?=$error ?>
        </form>
    </div>

    <script src="https://unpkg.com/ionicons@4.2.2/dist/ionicons.js"></script>

</body>
</html>