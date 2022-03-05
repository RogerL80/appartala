<?php
	require ('server/hosting.php');
	require ('server/conexion.php');
	require ('server/cadena.php');
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	$con2 = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con2));
	$con3 = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con3));
	if(isset($_POST['fechao']) && !empty($_POST['fechao'])&&
		isset($_POST['horao']) && !empty($_POST['horao'])&&
		isset($_POST['cancha']) && !empty($_POST['cancha'])&&
		isset($_POST['client']) && !empty($_POST['client'])&&
		isset($_POST['descrip']) && !empty($_POST['descrip'])&&
		isset($_POST['precio']) && !empty($_POST['precio']))
	{		
		// Selecciona si ya hay reservas en esa hora
		$cadena = '';
		$result  = $con2 -> query("SELECT idCancha, fechaIni, hora FROM reservas 
			WHERE idCancha = '$_POST[cancha]' AND fechaIni = '$_POST[fechao]' AND hora = '$_POST[horao]' AND estado != '0' AND estado != '3'")
			or die("Error en la consulta" . mysqli_error($result));
		// Enviar datos al array
		if ($result -> num_rows == 0){
			// Insertar registro
			$con->query("INSERT INTO reservas(idCancha,idCliente,fechaIni,hora,valor,estado,tipoPago,fechaRes,reservaPara,emailR,telR)
				VALUES('$_POST[cancha]','$_POST[client]','$_POST[fechao]','$_POST[horao]','$_POST[precio]','1',
					'0','$_POST[horactual]','$_POST[descrip]','$_POST[email]','$_POST[tel]')")
				or die ("<br/>Error al insertar... ". mysqli_error($con));
		}
		else{
			$cadena = 'Hora '.$_POST['horadia'].' del dia '.$_POST['fechao'].', se encuentra apartada para otro usuario.\n';
		}
		if(isset($_POST['repetir']) && !empty($_POST['repetir'])){
			$fechamovil = $_POST['fechao'];
			$maxima = $_POST['fecham'];
			$fechamovil = strtotime ( '+7 day' , strtotime ( $fechamovil ) ) ;
			$maxima = strtotime($maxima);
			while ($fechamovil <= $maxima){
				$fechamovil = date('Y-m-d',$fechamovil);
				//echo ("<br/>La fecha es ".$fechamovil);
	        	// Selecciona si ya hay reservas en esa hora
				$result  = $con2 -> query("SELECT idCancha, fechaIni, hora FROM reservas 
					WHERE idCancha = '$_POST[cancha]' AND fechaIni = '$fechamovil' AND hora = '$_POST[horao]' AND estado != '0' AND estado != '3'")
					or die("Error en la consulta" . mysqli_error($result));
				// Enviar datos al array
				if ($result -> num_rows == 0){
					// Insertar registro
					$con->query("INSERT INTO reservas(idCancha,idCliente,fechaIni,hora,valor,estado,tipoPago,fechaRes,reservaPara,emailR,telR)
						VALUES('$_POST[cancha]','$_POST[client]','$fechamovil','$_POST[horao]','$_POST[precio]','1',
							'0','$_POST[horactual]','$_POST[descrip]','$_POST[email]','$_POST[tel]')")
						or die ("<br/>Error al insertar... ". mysqli_error($con));
				}
				else{
					$cadena .= 'Hora '.$_POST['horadia'].' del dia '.$fechamovil.', se encuentra apartada para otro usuario.\n';
				}
	        	//
	        	$fechamovil = strtotime('+7 day',strtotime($fechamovil));
			}
		}
		if($cadena!=''){
			echo '<script type="text/javascript">
	        alert("'.$cadena.'");
	        if (window.history.length>1){
	          window.history.go(-1)
	        }else window.location="'.$hosting.'/inisesion.php"</script>';
		}
		else{
			echo '<script type="text/javascript">
	        if (window.history.length>1){
	          window.history.go(-1)
	        }else window.location="'.$hosting.'/inisesion.php"</script>';	
		}//echo '<script>window.history.go(-1)</script>';
		//ENVIAR NOTIFICACIONES
		//AL CLIENTE
		//Y AL DUEÑO DEL NEGOCIO
	}	
	else{
		echo '<script type="text/javascript">
        alert("Debe llenar todos los campos");
        if (window.history.length>1){
          window.history.go(-1)
        }else window.location="'.$hosting.'/inisesion.php"</script>';
	}	
?>