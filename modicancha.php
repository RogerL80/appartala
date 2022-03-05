<?php
	require ('server/hosting.php');
	require ('server/conexion.php');
	require ('server/cadena.php');
	    
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));

	if(isset($_POST['nombre1']) && !empty($_POST['nombre1'])&&
		isset($_POST['descripcion1']) && !empty($_POST['descripcion1'])&&
		isset($_POST['idest1']) && !empty($_POST['idest1'])&&
		isset($_POST['idcan']) && !empty($_POST['idcan'])&&
		isset($_POST['largo1']) && !empty($_POST['largo1'])&&
		isset($_POST['ancho1']) && !empty($_POST['ancho1'])&&
		isset($_POST['jugadores1']) && !empty($_POST['jugadores1']))
	{

		$nom = utf8_decode($_POST['nombre1']);
		$descripcion = utf8_decode($_POST['descripcion1']);
		$descripcion = sanear_string($descripcion);
		$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
		$reemplazar=array(" ", " ", " ", " ");
		$descripcion=str_ireplace($buscar,$reemplazar,$descripcion);
		// Insertar registro
		$con->query("UPDATE canchas SET nombreCancha='$nom',descriCancha='$descripcion',idEstablecimiento='$_POST[idest1]',
			largo='$_POST[largo1]',ancho='$_POST[ancho1]',jugadores='$_POST[jugadores1]',idSuperficie='$_POST[superficie1]'
			WHERE idCancha='$_POST[idcan]'")
			or die ("<br/>Error al insertar... ". mysqli_error($con));

		echo '<script>window.history.go(-1)</script>';
	}	
	else{
		echo '<script type="text/javascript">
        alert("Debes llenar todos los campos para registar la sede");
        if (window.history.length>1){
          window.history.go(-1)
        }else window.location="'.$hosting.'/inisesion.php"</script>';
	}
?>