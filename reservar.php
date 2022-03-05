<?php
	require_once('server/recaptchalib.php');
	include('server/key_captcha.php');
	require ('server/conexion.php');
	require ('server/cadena.php');
	require ('server/hosting.php');
	/* VALIDACION DE RECAPTCHA*/
	// tu clave secreta
	$secret = $privatekey;	 
	// respuesta vacía
	$response = null;	 
	// comprueba la clave secreta
	$reCaptcha = new ReCaptcha($secret);
	// si se detecta la respuesta como enviada
	if ($_POST["g-recaptcha-response"]) {
	$response = $reCaptcha->verifyResponse(
	        $_SERVER["REMOTE_ADDR"],
	        $_POST["g-recaptcha-response"]
	    );
	}
	if ($response != null && $response->success) {		    
		$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
		$con2 = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con2));
		$con3 = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con3));
		if(isset($_POST['fechao']) && !empty($_POST['fechao'])&&
			isset($_POST['horao']) && !empty($_POST['horao'])&&
			isset($_POST['cancha']) && !empty($_POST['cancha'])&&
			isset($_POST['client']) && !empty($_POST['client'])&&
			isset($_POST['precio']) && !empty($_POST['precio']))
		{
			// VERIFICAR SI TIENE RESERVAS EN OTRO ESTABLECIMIENTO A LA MISMA FECHA Y HORA
			$res = $con3 -> query("SELECT * FROM reservas WHERE idCliente = '$_POST[client]' AND fechaIni = '$_POST[fechao]' 
				AND hora = '$_POST[horao]' AND estado != '3'")
				or die("Error en la consulta" . mysqli_error($result));
			if ($res -> num_rows != 0){
				// Insertar registro
				echo '<script type="text/javascript">
		        alert("El usuario ya tiene apartada otra cancha en ese horario");
		        if (window.history.length>1){
		          window.history.go(-1)
		        }else window.location="'.$hosting.'/inisesion.php"</script>';
			}
			else{
				// Selecciona todos los registros en la tabla establecimiento
				$result  = $con2 -> query("SELECT idCancha, fechaIni, hora FROM reservas 
					WHERE idCancha = '$_POST[cancha]' AND fechaIni = '$_POST[fechao]' AND hora = '$_POST[horao]' AND estado != '0' AND estado != '3'")
					or die("Error en la consulta" . mysqli_error($result));
				// Enviar datos al array
				if ($result -> num_rows == 0){
					// Insertar registro
					$con->query("INSERT INTO reservas(idCancha,idCliente,fechaIni,hora,valor,estado,tipoPago,fechaRes,reservaPara,emailR,telR)
						VALUES('$_POST[cancha]','$_POST[client]','$_POST[fechao]','$_POST[horao]','$_POST[precio]','1','$_POST[optPagos]','$_POST[horactual]','','','')")
						or die ("<br/>Error al insertar... ". mysqli_error($con));
					//ENVIAR NOTIFICACIONES
					//AL CLIENTE--------------
					$nombres = $_POST['nomusuario'];
					$apellidos = $_POST['apeusuario'];
					$destinatariocl = $_POST['mailusuario'];
					$destinatarioem = $_POST['mailestable'];
					$lugar = $_POST['nomestable'];
					$canchares = $_POST['nomcancha'];
					$fechareser = $_POST['fecha'];
					$horares = $_POST['horadia'];
					$codcliente = $_POST['client'];
					$codestable = $_POST['codestableci'];
					$codcancha = $_POST['cancha'];
					$preciores = $_POST['precior'];
					$asunto = "Pre-reserva lista";
					$cuerpo = ' 
					<html> 
					<head> 
					   <title>Has apartado una cancha</title> 
					</head> 
					<body> 
					<h1>Hola '.$nombres.' '.$apellidos.'!</h1> 
					<p> 
					Tu reserva acaba de ser realizada.<br/>
					Lugar: <b>'.$lugar.'</b>.<br/>
					Email: <b>'.$destinatarioem.'</b>.<br/>
					Cancha: <b>'.$canchares.'</b>.<br/>
					Fecha: <b>'.$fechareser.'</b>.<br/>
					Hora: <b>'.$horares.'</b>.<br/>
					Precio: <b>'.$preciores.'</b>.<br/>
					</p>
					<p>Puedes revisar todas tus reservas en tu <a href="'.$hosting.'/perfil.php"><b>Perfil</b></a><br/>
					o ingresar al perfil de <a href="'.$hosting.'/reserva.php?idneg='.$codestable.'&idcan='.$codcancha.'"><b>'.$lugar.'</b></a>.
					</p> 
					<p>
					Appartala... la que tu quieras
					</p>
					</body> 
					</html> 
					';
					//PARA EL NEGOCIO
					$cuerpo2 = ' 
					<html> 
					<head> 
					   <title>Se ha apartado una de tus canchas</title> 
					</head> 
					<body> 
					<h1>Hola '.$lugar.'!</h1> 
					<p> 
					Se ha reservado una de tus canchas.<br/>
					Cliente: <b>'.$nombres.' '.$apellidos.'</b>.<br/>
					Email: <b>'.$destinatariocl.'</b>.<br/>
					Lugar: <b>'.$lugar.'</b>.<br/>
					Cancha: <b>'.$canchares.'</b>.<br/>
					Fecha: <b>'.$fechareser.'</b>.<br/>
					Hora: <b>'.$horares.'</b>.<br/>
					Precio: <b>'.$preciores.'</b>.<br/>
					</p>
					<p>
					Puedes ingresar a tu <a href="'.$hosting.'/estable.php?em='.$codestable.'"><b>Perfil</b></a>.
					</p> 
					<p>
					Appartala... la que tu quieras
					</p>
					</body> 
					</html> 
					';
					// 
					//para el envío en formato HTML 
					$headers = "MIME-Version: 1.0\r\n"; 
					$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
					//dirección del remitente 
					$headers .= "From: Appartala <".$mailpri.">\r\n"; 
					//dirección de respuesta, si queremos que sea distinta que la del remitente 
					$headers .= "Reply-To: donotreply@gmail.com\r\n"; 
					//ruta del mensaje desde origen a destino 
					$headers .= "Return-path: ".$mailpri."\r\n"; 
					//direcciones que recibián copia 
					//$headers .= "Cc: roger_lozada@hotmail.com\r\n"; 
					//direcciones que recibirán copia oculta 
					//$headers .= "Bcc: roglozada@gmail.com\r\n";  
					if(mail($destinatariocl,$asunto,$cuerpo,$headers)){
						echo '<script type="text/javascript">
				        alert("Mensaje Enviado al cliente");
				        if (window.history.length>1){
				          window.history.go(-1)
				        }else window.location="'.$hosting.'/inisesion.php"</script>';
					}else{
						echo '<script type="text/javascript">
				        alert("No se pudo enviar el mensaje al cliente");
				        if (window.history.length>1){
				          window.history.go(-1)
				        }else window.location="'.$hosting.'/inisesion.php"</script>';
					}
					//DUEÑO DE NEGOCIO
					if(mail($destinatarioem,$asunto,$cuerpo2,$headers)){
						echo '<script type="text/javascript">
				        alert("Mensaje Enviado al establecimiento");
				        if (window.history.length>1){
				          window.history.go(-1)
				        }else window.location="'.$hosting.'/inisesion.php"</script>';
					}else{
						echo '<script type="text/javascript">
				        alert("No se pudo enviar el mensaje establecimiento");
				        if (window.history.length>1){
				          window.history.go(-1)
				        }else window.location="'.$hosting.'/inisesion.php"</script>';
					}			
					//Y AL DUEÑO DEL NEGOCIO
					}
				else{
					echo '<script type="text/javascript">
			        alert("Esta hora ya fue apartada, probablemente por otro usuario.'.$_POST['fecha'].' - '.$_POST['horadia'].'");
			        if (window.history.length>1){
			          window.history.go(-1)
			        }else window.location="'.$hosting.'/inisesion.php"</script>';
				}
				
			}
		}	
		else{
			echo '<script type="text/javascript">
	        alert("Debe llenar todos los campos");
	        if (window.history.length>1){
	          window.history.go(-1)
	        }else window.location="'.$hosting.'/inisesion.php"</script>';
		}
	} else {
		// What happens when the CAPTCHA was entered incorrectly
		echo '<script type="text/javascript">
	        alert("reCAPTCHA no fue ingresado correctamente. Regresa e intentalo de nuevo.");
	        if (window.history.length>1){
	          window.history.go(-1)
	        }else window.location="'.$hosting.'/inisesion.php"</script>';
	}	
?>