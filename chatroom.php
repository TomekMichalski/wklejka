<?php
	session_start();
	require_once('classes/class.php');
	check_login('index.php',false);
	
	if($_SESSION['room']=='global'&&$_SESSION['switch']=='file'){
		$_SESSION['switch']='chat';
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
		<!--<script src="main.js"></script>-->
		
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
	<body class="body body--chatroom">
		<div class="chatroom">
			<div class="chatroom__left">
				<div class="chatroom__time">
					<p class="chatroom__time__date"></p>
					<p class="chatroom__time__clock"></p>
				</div>
				<div class="chatroom__current-channel">
					<?=$user->actual_room(); ?>
				</div>
				<div class="channels">
					<div class="channels__category" id="<?=$_SESSION['class'];  ?>">
						<a class="channels__heading" href="#<?=$_SESSION['class'];  ?>">
							<?=$_SESSION['class'];  ?>
						</a>
						<a href="chatroom.php" onclick='query("global");' >
							<div class="channels__dropdown">
								Globalny
							</div>
						</a>
						<a href="chatroom.php" onclick='query("sites");' >
							<div class="channels__dropdown">
								Witryny
							</div>
						</a>
						<a  href="chatroom.php" onclick='query("database");' >
							<div class="channels__dropdown">
								Bazy danych
						</div>
						</a>
						<a href="chatroom.php" onclick='query("informatics");' >
							<div class="channels__dropdown">
								Informatyka
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
							<textarea placeholder="Napisz wiadomość!" class="chat-form__text" id="tresc" name="message" required></textarea>
							<!-- <input type="text" placeholder="Napisz wiadomość!" class="chat-form__text" id="tresc" name="message" required> -->
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

				<a href="chatroom.php"'." onclick='query({$cudzyslow}chat_switch{$cudzyslow})';  ".'><button class="file-form__switch">
					<ion-icon name="chatboxes" ></ion-icon>
					<ion-icon name="arrow-forward"></ion-icon>
				</button></a>
			</div>';
			?>
		</div>
		<div class="version-control">
			v.0.2beta
		</div>
		<div class="logout" onclick='query("logout");location.reload();' title="Wyloguj się">
			<ion-icon name="log-out"></ion-icon>
		</div>
		
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
			
			add_chat();
			setInterval('add_chat()',1e3);
			
		</script>
		
		
		
	</body>
</html>