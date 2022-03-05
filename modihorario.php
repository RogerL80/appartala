<?php
	require ('server/hosting.php');
	require ('server/conexion.php');
	require ('server/cadena.php');	    
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	if(isset($_POST['ini1']) && !empty($_POST['ini1'])&&
		isset($_POST['fin1']) && !empty($_POST['fin1'])&&
		isset($_POST['ini2']) && !empty($_POST['ini2'])&&
		isset($_POST['fin2']) && !empty($_POST['fin2'])&&
		isset($_POST['ini3']) && !empty($_POST['ini3'])&&
		isset($_POST['fin3']) && !empty($_POST['fin3'])&&
		isset($_POST['ini4']) && !empty($_POST['ini4'])&&
		isset($_POST['fin4']) && !empty($_POST['fin4'])&&
		isset($_POST['ini5']) && !empty($_POST['ini5'])&&
		isset($_POST['fin5']) && !empty($_POST['fin5'])&&
		isset($_POST['ini6']) && !empty($_POST['ini6'])&&
		isset($_POST['fin6']) && !empty($_POST['fin6'])&&
		isset($_POST['ini7']) && !empty($_POST['ini7'])&&
		isset($_POST['fin7']) && !empty($_POST['fin7']))
	{
		for ($i=0; $i < 7; $i++) { 
			$ini = "ini".($i+1);
			$fin = "fin".($i+1);
			$con->query("UPDATE atencion SET horaIni='$_POST[$ini]',horaFin='$_POST[$fin]' 
				WHERE idEstablecimiento='$_POST[idestableh]' AND idDia='".$i."'")
				or die ("<br/>Error al insertar... ". mysqli_error($con));
		}
		echo '<script>window.history.go(-1)</script>';
	}	
	else{
		echo '<script type="text/javascript">
	        alert("Debe llenar los campos para modificar el horario");
	        if (window.history.length>1){
	          window.history.go(-1)
	        }else window.location="'.$hosting.'/inisesion.php"</script>';		
	}
?>