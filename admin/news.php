<?php
require_once('../classes/db.php');
require_once('../classes/class.php');


$sql= $pdo->query("SELECT * FROM users ");
$row = $sql->fetchAll();

foreach($row as $result){
	
	//send_email('Informacja','To znowu ja :V<br /> Zrobilem system do resetowanie hasła ale oczywiście nie bylbym sobą gdybym czegoś nie spierdolil.<br /> Niestety wyjebalo wasze hasla i musiecie je ustawic od nowa <a href="https://zonegames.pl/wklejka/forgot_pass.php">Tutaj</a>.<br /> Sorki :(',$result['email']);
	
	
}



