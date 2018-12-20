<?php
	session_start();
	require_once('classes/class.php');
	check_login('index.php',false);
	
	if($_SESSION['room']=='global'&&$_SESSION['switch']=='file'){
		$_SESSION['switch']='chat';
		header('Refresh:0');
	}
	

	if(isset($_POST['paste'])&&strlen($_POST['topic'])<=30&&trim($_POST['paste'])!='' && trim($_POST['topic'])!=''){

		$text=htmlspecialchars(addslashes($_POST['paste']),ENT_QUOTES,"UTF-8");
		$topic=htmlspecialchars($_POST['topic'],ENT_QUOTES,"UTF-8");
	
		$ourFileName = $topic.'-'.next_id('file');
		$ourFileHandle = fopen('files/'.$ourFileName.'.zgs', 'w') or die("can't open file");
		fwrite($ourFileHandle, $text);
		$filesize=filesize('files/'.$ourFileName.'.zgs');
		fclose($ourFileHandle);
		$pdo->query("INSERT INTO file (`id_author`,`file`,`filename`,`size`,`time`,`school`,`class`,`room`) VALUES ('".$_SESSION['id']."','".$ourFileName.".zgs','".$topic."','$filesize','".date("d.m")."','".$_SESSION['school']."','".$_SESSION['class']."','".$_SESSION['room']."'  ) ");
		$pdo->query("INSERT INTO chat (`id_author`,`text`,`time`,`school`,`class`,`room`) VALUES ('-1','".$user->user_info()[0]." ".$user->user_info()[1]." dodał nowy plik (wewnętrzny) ','".date("H:i D")."','".$_SESSION['school']."','".$_SESSION['class']."','".$_SESSION['room']."'  ) ");

		header('Refresh:0');

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
		<script defer src="https://use.fontawesome.com/releases/v5.6.1/js/all.js" integrity="sha384-R5JkiUweZpJjELPWqttAYmYM1P3SNEJRM6ecTQF05pFFtxmCO+Y1CiUhvuDzgSVZ" crossorigin="anonymous"></script>



		<!--<script src="main.js"></script>-->
		
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-130672518-1"></script>
		<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-130672518-1');
		</script>


		<!-- sorki sa syf  wez to potem gdzies wsadz :) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script>
			function query(query,value) {
			
				var json = null;
				
				$.ajax({
					
					type: "POST",
					async: false,
					url: "classes/ajax.php",
					data: { query : query,value:value },
					success: function(response) {
						json = response;
						
					}	
			
				});	

				return json;
			
			}
			
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			
		</script>
		
	</head>
	<body class="body body--chatroom" onload='query("check_login")'  >
		<div class="chatroom">
			<div class="chatroom__left">
				<div class="chatroom__time">
					<p class="chatroom__time__date"></p>
					<p class="chatroom__time__clock"></p>
				</div>
				<div class="chatroom__current-channel">
					<?=$user->actual_room(); ?>
					<br />
					Aktualnie Online: <span style="font-weight:bold;" class="online"></span>
				</div>
				<div class="channels">
					<div class="channels__category" id="<?=$_SESSION['class'];  ?>">
						<a class="channels__heading" href="#<?=$_SESSION['class'];  ?>">
							<?=$_SESSION['class'];  ?>
						</a>
						<a href="chatroom.php" onclick='query("global");' >
							<div class="channels__dropdown">
								Globalny &nbsp  <span class="online_gl"></span> 
							</div>
						</a>
						<a href="chatroom.php" onclick='query("sites");' >
							<div class="channels__dropdown">
								Witryny &nbsp <span class="online_st"></span> 
							</div>
						</a>
						<a  href="chatroom.php" onclick='query("database");' >
							<div class="channels__dropdown">
								Bazy danych &nbsp <span class="online_db"></span> 
						</div>
						</a>
						<a href="chatroom.php" onclick='query("informatics");' >
							<div class="channels__dropdown">
								Informatyka &nbsp <span class="online_it"></span> 
							</div>
						</a>

					</div>
				</div>

			</div>
			<?php
				$cudzyslow='"';
				if($_SESSION['room']!="global")
					$switch_type='<a href="chatroom.php"'." onclick='query({$cudzyslow}file_switch{$cudzyslow})';  ".'><button class="chat-form__switch">
					<ion-icon name="copy" ></ion-icon>
					<ion-icon name="arrow-forward"></ion-icon>
				</button></a>';
				
				
				if($_SESSION['switch']=="chat") {
					echo '<div class="chatbox" id="chat"></div>
					<div class="chat-form">
						<form id="add_text" >
						<!--	 <textarea placeholder="Napisz wiadomość!" class="chat-form__text" id="tresc" name="message" required></textarea>  -->
						 <input type="text" placeholder="Napisz wiadomość!" class="chat-form__text" id="tresc" name="message" required>
							<input type="submit" class="chat-form__submit">
						</form>
					'.$switch_type.'
					</div>';
				} else
				echo '<div class="file-browser" id="file" ></div>
			<div class="file-form">
				<form id="add_text" >
					<input type="file" name="file" id="submit_file" class="file-form__file-button" data-multiple-caption="{count} files selected" >
					<label for="submit_file" class="file-form__file-submit">
						<ion-icon name="cloud-upload" ></ion-icon>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Prześlij plik 
					</label>
					<input type="submit" class="file-form__submit">
					</form>
					<button  name="direct" class="file-form__directInput">Direct</button>
				<a href="chatroom.php"'." onclick='query({$cudzyslow}chat_switch{$cudzyslow})';  ".'><button class="file-form__switch">
					<ion-icon name="chatboxes" ></ion-icon>
					<ion-icon name="arrow-forward"></ion-icon>
				</button></a>
			</div>';
			?>
		</div>
		<div class="version-control">
			<?= VERSION //teraz wersje zmienasz tutaj - /classes/config.php ?>
		</div>
		<div class="logout" onclick='query("logout");location.reload();' title="Wyloguj się">
			<ion-icon name="log-out"></ion-icon>
		</div>
		<?php include("directPaste.php") ?>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://unpkg.com/ionicons@4.2.2/dist/ionicons.js"></script>
		<script src="js/chatroom.js"></script>
		
		<script>	
		
			$( "#add_text" ).on('submit', function(e) {
				
				var selector = (typeof($(this).attr('id')) !== 'undefined' || $(this).attr('id') !== null) ? '#' + $(this).attr('id') :  '.' + $(this).attr('class');
				$(selector +" button").prop('disabled', true);
				$(selector +" .loading_dot" ).show(500);
				e.preventDefault();
				
				var form = new FormData($("#add_text")[0]);	
			
				$.ajax({
					
					url: 'classes/ajax.php',
					method: "POST",
					dataType: 'json',
					data: form,
					processData: false,
					contentType: false,
					success: function (received) {
					if(received.type=='success'){
						$(':input[id=tresc]').val('');		
						$(':input[id=submit_file]').val('');		
						setScrollToBottom();
					}
					else{
						alert(received);					
					}
					$(selector +" button").prop('disabled', false);
					},
					error: function (request, status, error) {
						alert(error);
						$(selector +" .loading_dot" ).hide(500);
						$(selector +"button").prop('disabled', false);
					}
											
				});
			});
			
			function add_chat() {
		
				var comments = $("#<?=$_SESSION['switch']  ?>");
				
				var liczenie = document.getElementById('<?=$_SESSION['switch']  ?>');
				var finish = liczenie.getElementsByTagName('div');
			
				if(query("<?=$_SESSION['switch']  ?>_count")!=finish.length||document.getElementById('<?=$_SESSION['switch']  ?>').innerHTML==''){

					var data= query("<?=$_SESSION['switch']  ?>");
					document.getElementById('<?=$_SESSION['switch']  ?>').innerHTML='';
				
					for(var i=0 ; i < data.length; i++){
						
						comments.append(data[i]['text']);

					}
					
					
				}
				
		
			};

			function check_how_many_logged(){

				const online_db_HTML=document.querySelector("span.online_db");
				const online_it_HTML=document.querySelector("span.online_it");
				const online_gl_HTML=document.querySelector("span.online_gl");
				const online_st_HTML=document.querySelector("span.online_st");

				const online_HTML=document.querySelector("span.online");

				const link='<svg class="svg-inline--fa fa-users fa-w-20" aria-hidden="true" data-prefix="fas" data-icon="users" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" data-fa-i2svg=""><path fill="currentColor" d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6 40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32 208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z"></path></svg><!-- <i class="fas fa-users"></i> -->  &nbsp;';

				var online=query('how_many_online');

				var online_all=online[0]['db']+online[0]['it']+online[0]['gl']+online[0]['st'];

				if(online_HTML.innerHTML!=online_all)
					online_HTML.innerHTML=online_all;


				if(online_db_HTML.innerHTML!=link+online[0]['db'] && online[0]['db']!=0 )
					online_db_HTML.innerHTML=link+online[0]['db'];
				else if(online[0]['db']==0)
					online_db_HTML.innerHTML='';

				if(online_it_HTML.innerHTML!=link+online[0]['it'] && online[0]['it']!=0 )
					online_it_HTML.innerHTML=link+online[0]['it'];
				else if(online[0]['it']==0)
					online_it_HTML.innerHTML='';

				if(online_gl_HTML.innerHTML!=link+online[0]['gl'] && online[0]['gl']!=0 )
					online_gl_HTML.innerHTML=link+online[0]['gl'];	
				else if(online[0]['gl']==0)
					online_gl_HTML.innerHTML='';

				if(online_st_HTML.innerHTML!=link+online[0]['st'] && online[0]['st']!=0 )
					online_st_HTML.innerHTML=link+online[0]['st'];	
				else if(online[0]['st']==0)
					online_st_HTML.innerHTML='';
				
			}
			check_how_many_logged();
			add_chat();
			setInterval('add_chat()',1e3);
			setInterval('check_how_many_logged()',1e3);
			setInterval('query("check_login")',60 * 1000);
			
		</script>
		
		
		
	</body>
</html>