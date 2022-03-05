<?php
	require ('server/hosting.php');
	require ('server/conexion.php');
	sleep(1);
	$con1 = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con1));
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	// Insertar registro
	if(isset($_POST['idcan'])){
		$longitud = $_POST['longitud'];
		$valor = $_POST['valor'];
		$result  = $con1->query("SELECT * FROM tarifas WHERE idCancha='$_POST[idcan]'")
			or die ("<br/>Error al Consultar... ". mysqli_error($con1));
		if ($result -> num_rows != 0){
			// Convertimos el objeto
			$con->query("UPDATE tarifas SET idCancha='$_POST[idcan]',idDia='$_POST[idDia]',hora='$_POST[hora]',valor='$valor' 
				WHERE idCancha='$_POST[idcan]' AND idDia='$_POST[idDia]' AND hora='$_POST[hora]'")
				or die ("<br/>Error al actualizar... ". mysqli_error($con));
			echo 1;					    	    	
		}else{
			echo 0;
		}
	}
?>