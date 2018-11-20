<?php
session_start();
require_once("db_website.php");
header('Content-type: application/json');
$query = $_POST['query'];


if(isset($_POST['comment'])&&strlen($_POST['comment'])<=100&&ltrim($_POST['comment'])!=''){
	$sql= $pdo_website->query
	("INSERT INTO czat (`nick`,`tresc`,`data`) VALUES ('".$_SESSION['nick']."','".htmlspecialchars(addslashes($_POST['comment']))."','".date("H:i")."') ");
	$resluts['type'] = 'success';
	
}

if($query == "czat"){
	
	
	$sql= $pdo_website->query("SELECT * FROM czat ORDER BY id DESC");
	$count=$sql->rowCount();

	$komentarze = $sql->fetchAll();
		
	$resluts = [];
			
	foreach($komentarze as $list){
				
		$data['nick_autor']=$list['nick'];
		$data['message']=$list['tresc'];
		$data['time']=$list['data'];
		
		array_push($resluts,$data);
				
				
	}
	
}

if($query == "helper_chat"){
	
	$resluts=file_get_contents('last_id_chat');
	
}

if($query == "_helper_chat"){
	
	$sql= $pdo_website->query("SELECT id FROM czat ORDER BY id DESC");
	$count=$sql->rowCount();
	file_put_contents('last_id_chat',$count);
	$resluts='Zaktulizwano czat';
	
}

if($query=='lista'){
	
	$sql= $pdo_website->query("SELECT * FROM wklejka ORDER BY id DESC ");
	$lista = $sql->fetchAll();
	
	$resluts = [];
	$number=1;
	foreach($lista as $list){
		
		$data['numer']=$number;
		$data['id']=$list['id'];
		$data['temat']=$list['temat'];
		$data['time']=$list['time'];
	
		array_push($resluts,$data);
		
		$number++;
	}
	
}

if($query == "helper_list"){
	
	$resluts=file_get_contents('last_id_list');
	
}

if($query == "_helper_list"){
	
	$sql= $pdo_website->query("SELECT id FROM wklejka ORDER BY id DESC");
	$count=$sql->rowCount();
	file_put_contents('last_id_list',$count);
	$resluts='Zaktulizowano liste';
	
}



if($query == "logout"){
	
	session_destroy();
}


if($resluts==null)
	$resluts='Wypierdalaj stad';
	
	

echo json_encode($resluts);