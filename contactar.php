<?php 
	sleep(2);
	require ('server/hosting.php');
	$nombres = $_POST['nombre'];
	$email = $_POST['email'];
	$destinatario = 'info@appartala.com';
	$asunto = $_POST['asunto'];
	$mensaje = $_POST['mensaje'];
	$cuerpo = ' 
		<html> 
		<head> 
		   <title>Mensaje de Contacto</title> 
		</head> 
		<body> 
		<p> 
		Nombre: <b>'.$nombres.' </b><br/> 
		Email: <b>'.$email.'</b>.<br/>
		Mensaje: '.$mensaje.'<br/>
		</p> 
		</body> 
		</html> 
		';

	if(empty($_POST['submit'])){
		//para el envío en formato HTML 
		$headers = "MIME-Version: 1.0\r\n"; 
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
		//dirección del remitente 
		$headers .= "From: <".$email.">\r\n"; 
		if(mail($destinatario,$asunto,$cuerpo,$headers)){
			echo 0;				
		}else{
			echo 1;
		}
	}else{
		echo 3;
	}
?>