<?php
    session_start();
	require_once('classes/class.php');
    check_login('index.php',false);
    
    $_GET['name']=htmlspecialchars($_GET['name'],ENT_QUOTES,"UTF-8");
    $sql= $pdo->query("SELECT id_author FROM file WHERE file='".$_GET['name']."' ");
    $results=$sql->fetch();

    $name_author=$user->user_info($results['id_author'])[0];
	
?>

<html>
<head>

        <meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Wklejka użytkownika <?=$name_author?> </title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="img/logo.png" sizes="32x32"/>

        <!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-130672518-1"></script>
		<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-130672518-1');
		</script>

</head>
<body>
    <a href="chatroom.php"> Wróć </a>

    <?php

        if (file_exists("files/".$_GET['name']))
        {
    
            $dane = fread(fopen("files/".$_GET['name'], "r"), filesize("files/".$_GET['name']));
            
            echo '<pre>'.nl2br(stripslashes($dane)).'</pre>';

            
    
        }else{
            
            header('Location: chatroom.php');
    
        }


    ?>

</body>
</html>