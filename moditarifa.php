<?php
	require ('server/hosting.php');
	require ('server/conexion.php');
	sleep(2);
	$con1 = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con1));
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	if(isset($_POST['idcan']) && !empty($_POST['idcan'])&&
		isset($_POST['horaini']) && !empty($_POST['horaini'])&&
		isset($_POST['horafin']) && !empty($_POST['horafin']))
	{
		// Insertar registro
		for ($i = $_POST['horaini']; $i <= $_POST['horafin']; $i++) { 
			for ($j=0; $j < 7; $j++) { 
				$id = $j."-".$i;
				if(isset($_POST[$id])){
					$valor = $_POST[$id];
					$result  = $con1->query("SELECT * FROM tarifas WHERE idCancha='$_POST[idcan]' AND idDia='$j' AND hora='$i'")
						or die ("<br/>Error al Consultar... ". mysqli_error($con1));
					if ($result -> num_rows !=0){
						// Convertimos el objeto
						$con->query("UPDATE tarifas SET idCancha='$_POST[idcan]',idDia='$j',hora='$i',valor='$valor' 
							WHERE idCancha='$_POST[idcan]' AND idDia='$j' AND hora='$i'")
							or die ("<br/>Error al actualizar... ". mysqli_error($con));					    	    	
					}else{
						$con->query("INSERT INTO tarifas (idCancha,idDia,hora,valor) VALUES ('$_POST[idcan]','$j','$i','$valor')")
						or die ("<br/>Error al insertar... ". mysqli_error($con));
					}
				}						
			}
		}
		echo 1;
	}	
	else{
		echo 0;
	}
?>