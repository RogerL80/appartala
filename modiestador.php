<?php
	require ('server/hosting.php');
	require ('server/conexion.php');
	require ('server/cadena.php');
	    
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));

	if(isset($_POST['idreser']) && !empty($_POST['idreser'])&&
		isset($_POST['estad']) && !empty($_POST['estad']))
	{
		// Modificar Registro
		$con->query("UPDATE reservas SET estado='$_POST[estad]' WHERE idReservas='$_POST[idreser]'")
			or die ("<br/>Error al Actualizar... ". mysqli_error($con));

		echo '<script>window.history.go(-1)</script>';
		//ENVIAR NOTIFICACIONES
		//AL CLIENTE
		//Y AL DUEÑO DEL NEGOCIO
	}	
	else{
		echo '<script type="text/javascript">
	        alert("Debe llenar los campos para cambiar el estado de la reserva");
	        if (window.history.length>1){
	          window.history.go(-1)
	        }else window.location="'.$hosting.'/inisesion.php"</script>';
	}
?>