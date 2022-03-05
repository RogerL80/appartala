<?php 
	require ('server/hosting.php');
	require ('server/conexion.php');
	require ('server/cadena.php');
	    
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));

	if(isset($_POST['nombre']) && !empty($_POST['nombre'])&&
		isset($_POST['descripcion']) && !empty($_POST['descripcion'])&&
		isset($_POST['idest']) && !empty($_POST['idest'])&&
		isset($_POST['largo']) && !empty($_POST['largo'])&&
		isset($_POST['ancho']) && !empty($_POST['ancho'])&&
		isset($_POST['jugadores']) && !empty($_POST['jugadores']))
	{

		$nom = utf8_decode($_POST['nombre']);
		$descripcion = utf8_decode($_POST['descripcion']);
		$descripcion = sanear_string($descripcion);
		/*echo "<br/>Nomb: ".$nom;
		echo "<br/>descripcion: ".$descripcion;
		echo "<br/>idest: ".$_POST['idest'];
		echo "<br/>largo: ".$_POST['largo'];
		echo "<br/>ancho: ".$_POST['ancho'];
		echo "<br/>jugadores: ".$_POST['jugadores'];
		echo "<br/>superficie: ".$_POST['superficie'];
		*/
		$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
		$reemplazar=array(" ", " ", " ", " ");
		$descripcion=str_ireplace($buscar,$reemplazar,$descripcion);
		// Insertar registro
		$con->query("INSERT INTO canchas(nombreCancha,descriCancha,idEstablecimiento,largo,ancho,jugadores,idSuperficie,activa)
			VALUES('$nom','$descripcion','$_POST[idest]','$_POST[largo]','$_POST[ancho]','$_POST[jugadores]','$_POST[superficie]','1')")
			or die ("<br/>Error al insertar... ". mysqli_error($con));
		
		//header('Location: ./estable.php?em='.$_POST['idest']);
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