<?php 
	session_start();
	require ('hosting.php');
	include('conexion.php'); 
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	$res = $con->query("SELECT * FROM cliente WHERE email = '$_POST[email]' AND idEstablecimiento IS NULL")
	or die ("Error en la consulta... ". mysqli_error($con));
	$sesion = mysqli_fetch_array($res);
 	if($_POST['pw'] == $sesion["pw"]) {	
		$_SESSION['username'] = $_POST['email'];
		$_SESSION['user'] = $sesion["nombres"];
		$_SESSION['apellidos'] = $sesion["apellidos"];
		$_SESSION['tiposesion'] = 3; /* Tipo de sesion = Cliente*/
		$_SESSION['idclient'] = $sesion["idCliente"];
		//header("Location:".$_SERVER['HTTP_REFERER']);
		//echo "<script>window.history.back()</script>";
		echo '<script>window.history.go(-1)</script>';	
	}else{
		echo '<script type="text/javascript">
			alert("Correo o password incorrectos. intentalo de nuevo.")
	        if (window.history.length>1){
	          window.history.go(-1)
	        }else window.location="'.$hosting.'/inisesion.php"</script>';
	}

?>