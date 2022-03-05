<?php 
	include("server/conexion.php");
	require ('server/hosting.php');
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	$res = $con->query("SELECT * FROM cliente WHERE email = '$_POST[email]' AND idEstablecimiento IS NULL")
	or die ("Error en la consulta... ". mysqli_error($con));
	$cli = mysqli_fetch_array($res);
	if ($cli['email']) {		
		//Importamos las variables del formulario de contacto
		$nombres = $cli['nombres'];
		$apellidos = $cli['apellidos'];
		$destinatario = $cli['email'];
		$asunto = "Recuperar password";
		$pw = $cli['pw'];		
		$cuerpo = ' 
		<html> 
		<head> 
		   <title>Recuperar password</title> 
		</head> 
		<body> 
		<h1>Hola '.$nombres.' '.$apellidos.'!</h1> 
		<p> 
		<b>Has solicitado la recuperacion de tu password</b>.<br/>Tu password actual es: <b>'.$pw.'</b>.<br/>
		ingresa a nuestro sitio <a href="'.$hosting.'">Appartala</a> y sigue disfrutando de la cancha que tu quieras...
		</p> 
		</body> 
		</html> 
		'; 
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
		if(mail($destinatario,$asunto,$cuerpo,$headers)){
			echo '<script type="text/javascript">
			alert("Hemos enviado el password a tu correo, revisalo e intenta iniciar sesion nuevamente.");
	        if (window.history.length>1){
	          window.history.go(-1)
	        }else window.location="'.$hosting.'"</script>';				
		}else{
			echo '<script type="text/javascript">
			alert("Hubo un error al enviar el password al correo. Intentalo nuevamente.");
	        if (window.history.length>1){
	          window.history.go(-1)
	        }else window.location="'.$hosting.'"</script>';
		}
	}else{
		echo '<script>alert("Usuario no existe en Appartala");if (window.history.length>1){
	          window.history.go(-1)
	        }else window.location="'.$hosting.'"</script>';
	}
?>