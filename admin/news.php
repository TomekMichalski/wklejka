<?php
require_once('../classes/db.php');
require_once('../classes/class.php');

$sql= $pdo->query("SELECT * FROM users ");
$row = $sql->fetchAll();

$msg='';

foreach($row as $result){
	
	//send_email('Informacja',$msg,$result['email']);
	
	
}

