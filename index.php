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
	$result = $query->fetch();
	
	if($query->rowCount() == 1 && password_verify($password, $result['password']) ) {

		$time=round((strtotime(date("Y-m-d H:i"))-strtotime($result['last_login']))); 

		if($time<=60&&!$result['logout']){
			$error = '<p class="login__incorrect-data">Aktualnie jest ktoś zalogowany na tym koncie!</p>';
			send_email($config['message_email']['notification_login']['topic'], str_replace('[name]', $result['name'] , $config['message_email']['notification_login']['message'])  ,$result['email']);
		}
		else {

			$_SESSION['logged']=true;
			$_SESSION['id']=$result['id'];
			$_SESSION['name']=$result['name'];
			$_SESSION['surname']=$result['surname'];
			$_SESSION['school']=$result['school'];
			$_SESSION['class']=$result['class'];
			$_SESSION['admin']=$result['admin'];
			$_SESSION['room']='global';
			$_SESSION['switch']='chat';
			
			$pdo->query("UPDATE users SET last_login='".date("Y-m-d H:i")."' , last_ip='".$_SERVER['REMOTE_ADDR']."', logout=0 WHERE id='".$_SESSION['id']."' ");
			
			unset($error);
			header('Location:chatroom.php');
		}	
			
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

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-130672518-1"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', 'UA-130672518-1');
	</script>

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