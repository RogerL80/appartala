<?php 
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	require ('./server/hosting.php');
	include("./server/conexion.php");

	$idf = $_GET['idF'];
	$ruta = $_GET['rt'];
	/*session_start();
	if(isset ($_SESSION['username'])) {
	}else {
		header('Location: ./inisesion.php?error=2');
	}*/
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	
	$con -> query("DELETE  FROM foto WHERE idFoto= '$idf'")
	or die("Error al Eliminar" . mysqli_error($con));
	unlink($ruta);
	echo '<script>window.history.go(-1)</script>';
?>