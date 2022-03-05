<?php 
	session_start();
	require ('hosting.php');
	include('conexion.php'); 
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	$res = $con->query("SELECT * FROM establecimiento WHERE email = '$_POST[email]'")
	or die ("Error en la consulta... ". mysqli_error($con));
	$sesion = mysqli_fetch_array($res);
 	if($_POST['pw'] == $sesion["pw"]) {
		$_SESSION['username'] = $_POST['email'];
		$_SESSION['user'] = $sesion["nombre"];
		$_SESSION['tiposesion'] = 1;/* Tipo de sesion = Establecimiento*/
		$_SESSION['codigo'] = $sesion["idEstablecimiento"];
		//echo($_SESSION['codigo']);
		header('Location: ../estable.php');

	}else{
		echo '<script type="text/javascript">
			alert("Correo o password incorrectos. intentalo de nuevo.")
	        if (window.history.length>1){
	          window.history.go(-1)
	        }else window.location="'.$hosting.'/inisesion.php"</script>';
	}
?>