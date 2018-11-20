<?php

$host='localhost';
$database='wklejka';
$user='wklejka';
$password='wklejka0987!';

try {
	
	$pdo_website = new PDO("mysql:host=".$host.";dbname=".$database.";charset=utf8", $user, $password, [
		PDO::ATTR_EMULATE_PREPARES => false, 
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
	]);
	
} catch (PDOException $error) {
	
	echo $error->getMessage();
	exit('Database error');
	
}

