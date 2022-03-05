<?php 
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	include("./server/conexion.php");
	/*session_start();
	if(isset ($_SESSION['username'])) {
	}else {
		header('Location: ./inisesion.php?error=2');
	}*/
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	
	$con->query("UPDATE canchas SET activa=0 WHERE idCancha='$_POST[idcan1]'")
			or die ("<br/>Error al actualizar... ". mysqli_error($con));

	echo '<script>window.history.go(-1)</script>';
?>