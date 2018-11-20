<?php
session_start();
require_once('../classes/db.php');
require_once('../classes/class.php');

if(isset($_POST['submit_activ'])){
	
		$wiadomosc='
		Witaj <b>'.$_POST['nick'].'</b><br />
		Twoje konto zostało zweryfikowane. <br />
		<a href="https://zonegames.pl/wklejka">Klik</a> <br />
		Dziękujemy.';
		
		$sql= $pdo->query("UPDATE register SET verifi=1 WHERE id='".$_POST['id']."'");
		send_email('Weryfikacja',$wiadomosc,$_POST['email']);
}

if(isset($_POST['move_account'])){
	
	$sql= $pdo->query("SELECT * FROM register WHERE verifi=1 ");
	$row = $sql->fetchAll();
	
	foreach($row as $result){
		
				$pdo->query("INSERT INTO users (`nick`,`password`,`name`,`surname`,`email`,`school`,`class`,`date_register`,`last_ip`) VALUES (
				'".$result['nick']."','".$result['password']."','".$result['name']."','".$result['surname']."','".$result['email']."','".$result['school']."','".$result['class']."','".$result['date_register']."','".$result['last_ip']."' ) ");
				$pdo->query("DELETE FROM register WHERE id ='".$result['id']."' ");
				
	}
	
	
}
	
if($_SESSION['id']==1){
	
	/*$sql= $pdo->query("SELECT * FROM users ");
	$row = $sql->fetchAll();
	
	foreach($row as $result){
		
		$wiadomosc=' Witaj <b>'.$result['name'].' </b><br />
		Własnie została uruchomiona nowa wklejka.<br />
		Stara wklejka została usunieta.<br />
		Twoj login: <b>'.$result['nick'].'</b> <br />
		<a href="https://zonegames.pl/wklejka">Klik</>
		';
		
		send_email('Aktywacja',$wiadomosc,$result['email']);
						
	}
	
	*/
	
	

	
	
	$sql= $pdo->query("SELECT * FROM register WHERE verifi=0 ORDER BY id ASC");
	$row = $sql->fetchAll();
	
	if($sql->rowCount()==0)
		echo 'Brak kont';
	
	foreach($row as $result){
				echo '<b>'.$result['nick'].'</b>   '.$result['name'].'   <b>'.$result['surname'].'</b> '.$result['email'].' 
				<form method="POST"><input type="hidden" name="id" value="'.$result['id'].'" /> <input type="hidden" name="email" value="'.$result['email'].'" />
				<input type="hidden" name="nick" value="'.$result['nick'].'" /><input type="submit" name="submit_activ" value="Aktywuj" /> </form>
				<hr />';
		
	}
	
	echo '<form method="POST">
	Przerzuc wszystkie konta <input type="submit" name="move_account" />
	</form>';
	
	
	
}


