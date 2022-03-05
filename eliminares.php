<?php
	require ('server/hosting.php');
	require ('server/conexion.php');
	require ('server/cadena.php');    
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	$con1 = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con1));	
	if(($_POST["idcan1"]) && !empty($_POST["idcan1"]) &&
		($_POST["nomest"]) && !empty($_POST["nomest"]) &&
		($_POST["mailest"]) && !empty($_POST["mailest"]) &&
		($_POST["nomcancha"]) && !empty($_POST["nomcancha"]) &&
		($_POST["fechareser"]) && !empty($_POST["fechareser"]) &&
		($_POST["horareser"]) && !empty($_POST["horareser"]) &&
		($_POST["nomclient"]) && !empty($_POST["nomclient"]) &&
		($_POST["sancion"]) && !empty($_POST["sancion"]) &&
		($_POST["mailcli"]) && !empty($_POST["mailcli"]))
	{
		//DETERMINAR LA FECHA QUE EL USUARIO NO PODRA RESERVAR POR HABER CANCELADO
		$con->query("UPDATE cliente SET bloqueado='$_POST[sancion]'	WHERE idCliente='$_POST[codigo]'")
			or die ("<br/>Error al actualizar... ". mysqli_error($con));
		////
		// CAMBIAR ESTADO DE RESERVA A CANCELADA
		$con1->query("UPDATE reservas SET estado='3' WHERE idReservas='$_POST[idcan1]'")
			or die ("<br/>Error al actualizar... ". mysqli_error($con1));					    	    	
		//ENVIAR NOTIFICACIONES
		//AL CLIENTE--------------
		$nombres = $_POST['nomclient'];
		$destinatariocl = $_POST['mailcli'];
		$destinatarioem = $_POST['mailest'];
		$lugar = $_POST['nomest'];
		$canchares = $_POST['nomcancha'];
		$fechareser = $_POST['fechareser'];
		$horares = $_POST['horareser'];
		//$codcliente = $_POST['client'];
		$codestable = $_POST['idesta'];
		$nomcancha = $_POST['nomcancha'];
		//$preciores = $_POST['precior'];
		$asunto = "Reserva cancelada";
		$cuerpo = ' 
		<html> 
		<head> 
		   <title>Has cancelado una reserva</title> 
		</head> 
		<body> 
		<h1>Hola '.$nombres.'!</h1> 
		<p> 
		Acabas de cancelar tu reserva.<br/>
		Lugar: <b>'.$lugar.'</b>.<br/>
		Email: <b>'.$destinatarioem.'</b>.<br/>
		Cancha: <b>'.$canchares.'</b>.<br/>
		Fecha: <b>'.$fechareser.'</b>.<br/>
		Hora: <b>'.$horares.'</b>.<br/>
		</p>
		<p>Al cancelar una reserva, no podras realizar mas reservas durante 5 dias
		</p>
		<p>Puedes revisar todas tus reservas en tu <a href="'.$hosting.'/perfil.php"><b>Perfil</b></a><br/>
		o ingresar al perfil de <a href="'.$hosting.'/perfilest.php?em='.$codestable.'"><b>'.$lugar.'</b></a>.
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
		   <title>Reserva Cancelada</title> 
		</head> 
		<body> 
		<h1>Hola '.$lugar.'!</h1> 
		<p> 
		Se ha cancelado una reserva.<br/>
		Cliente: <b>'.$nombres.'</b>.<br/>
		Email: <b>'.$destinatariocl.'</b>.<br/>
		Lugar: <b>'.$lugar.'</b>.<br/>
		Cancha: <b>'.$canchares.'</b>.<br/>
		Fecha: <b>'.$fechareser.'</b>.<br/>
		Hora: <b>'.$horares.'</b>.<br/>
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
			echo "Mensaje Enviado al cliente";					
		}else{
			echo "No se pudo enviar el mensaje al cliente";
		}
		//DUEÑO DE NEGOCIO
		if(mail($destinatarioem,$asunto,$cuerpo2,$headers)){
			echo "Mensaje Enviado al establecimiento";
		}else{
			echo "No se pudo enviar el mensaje establecimiento";
		}			
		//Y AL DUEÑO DEL NEGOCIO
		//header('Location: ./estable.php?em='.$_POST['idest']);
		echo '<script>window.history.go(-1)</script>';
	}	
	else{
		echo('<script type="text/javascript">
        alert("Debes llenar todos los campos");
        if (window.history.length>1){
          window.history.go(-1)
        }else window.location="'.$hosting.'/inisesion.php"</script>');
		//echo "<br/>IdCancha: ".$_POST['idcan']." HoraIni ".$_POST['horaini']." HoraFin ".$_POST['horafin'];
	}
?>