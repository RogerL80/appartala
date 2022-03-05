<?php 
	require ('server/hosting.php');
	require ('server/conexion.php');
	require ('server/cadena.php');
    sleep(2);
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	$con2 = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con2));
	if(isset($_POST['nombre']) && !empty($_POST['nombre'])&&
		isset($_POST['dir']) && !empty($_POST['dir'])&&
		isset($_POST['nit']) && !empty($_POST['nit'])&&
		isset($_POST['codpais']) && !empty($_POST['codpais'])&&
		isset($_POST['tel']) && !empty($_POST['tel'])&&
		isset($_POST['email']) && !empty($_POST['email'])&&
		isset($_POST['pw']) && !empty($_POST['pw'])&&
		isset($_POST['pw2']) && !empty($_POST['pw2'])&&
		isset($_POST['lat']) && !empty($_POST['lat'])&&
		isset($_POST['creado']) && !empty($_POST['creado'])&&
		isset($_POST['usuario']) && !empty($_POST['usuario'])&&
		isset($_POST['lng']) && !empty($_POST['lng']))
	{
		$username = $_POST['usuario'];
		$email = $_POST['email'];
		//$idesta = $_POST['idest'];

    	$dire = utf8_decode($_POST['dir']);
		$nom = utf8_decode($_POST['nombre']);
		$ciu = utf8_decode($_POST['ciudad']);
		$esta = utf8_decode($_POST['estado']);
		$descripcion = sanear_string($_POST['descripcion']);
		$pais = utf8_decode($_POST['pais']);
		$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
		$reemplazar=array(" ", " ", " ", " ");
		$descripcion=str_ireplace($buscar,$reemplazar,$descripcion);
		$creado = $_POST['creado'];

		// Insertar registro sin foto
		$ruta = "";
		$tipoimg= "";
		$facebook = "";
		$twitter = "";
		$instagram = "";
		$con -> query("INSERT INTO establecimiento(nombre, nit, email, pw, direccion, tel, isopais, ciudad, estado, 
		descripcion, lat, lng, foto, fechafin, activo, iduser, facebook, twitter, instagram, fechaCreado)VALUES('$nom','$_POST[nit]','$_POST[email]','$_POST[pw]','$dire','$_POST[tel]','$_POST[codpais]','$ciu','$esta','$descripcion','$_POST[lat]','$_POST[lng]','$ruta','$_POST[fechamax]','0','$username','$facebook','$twitter','$instagram','$creado')")
		or die ("Error al insertar negocio... ". mysqli_error($con));
		//insertar codigo para guardar imagen en carpeta imagenest si hay foto		
		if ($_FILES['foto']['tmp_name']){
			$result  = $con -> query("SELECT idEstablecimiento FROM establecimiento WHERE email='$_POST[email]'")
			or die("Error en la consulta" . mysqli_error($result));
			$row= $result->fetch_assoc();
			$ruta = "imagenest";
			$nombreArchivo = $_FILES['foto']['name'];
			$archivo = $_FILES['foto']['tmp_name'];
			$ext = explode(".", $_FILES['foto']['name']);
			$nuevonombre = $row['idEstablecimiento'].".".$ext[1];
			//$nuevonombre = 'res_'.$nombreArchivo;
			move_uploaded_file($archivo, $ruta."/".$nombreArchivo);
			resizeImagen($ruta.'/', $nombreArchivo, 500, 500,$nuevonombre,$ext[1]);
        	unlink($ruta.'/'.$nombreArchivo);    
			$ruta = $ruta."/".$nuevonombre;
			
			$con -> query("UPDATE establecimiento SET foto ='$ruta' WHERE idEstablecimiento = '$row[idEstablecimiento]'") 
			or die("Error al Actualizar" . mysqli_error($con));

			$result2  = $con2 -> query("SELECT idCliente FROM cliente WHERE email='$_POST[email]'")
			or die("Error en la consulta" . mysqli_error($result2));
			$row2= $result2->fetch_assoc();

			$con2 -> query("UPDATE cliente SET fotoc ='$ruta' WHERE idCliente = '$row2[idCliente]'") 
			or die("Error al Actualizar" . mysqli_error($con2));
		}

		$result  = $con -> query("SELECT idEstablecimiento FROM establecimiento WHERE email='$_POST[email]'")
			or die("Error en la consulta" . mysqli_error($result));
			$row= $result->fetch_assoc();
		//INSERTAR EN TABLA USUARIO
		$con2 -> query("INSERT INTO cliente(nombres, apellidos, fechaNac, email, pw, telefono, idEstablecimiento, fotoc, idUsuario, facebook, twitter, instagram, fechaReg)
			VALUES('$nom','','0000-00-00','$_POST[email]','$_POST[pw]','$_POST[tel]','$row[idEstablecimiento]','$ruta','$username','$facebook','$twitter','$instagram','$creado')") or die ("Error al insertar en tabla cliente... ". mysqli_error($con2));
		// Insertar Horarios basicos de atencion
		for ($i=0; $i < 7; $i++) { 
			$con->query("INSERT INTO atencion(idEstablecimiento, idDia, horaIni, horaFin)
			VALUES('$row[idEstablecimiento]','".$i."','8','20')")
			or die ("Error al insertar horarios de atencion... ". mysqli_error($con));
		}
		//ENVIAR NOTIFICACIONES
		//Importamos las variables del formulario de contacto
		$nombre = $nom;
		$destinatario = $_POST['email'];
		$asunto = "Bienvenido a Appartala";
		$pw = $_POST['pw'];			
		$cuerpo = ' 
		<html> 
		<head> 
		   <title>Bienvenido a Appartala</title> 
		</head> 
		<body> 
		<h1>Hola '.$nombre.'!</h1> 
		<p> 
		<b>Bienvenido a Appartala</b>; a partir de este momento podras disfrutar de un sistema en linea para reservar tus canchas de futbol.<br/>Tu contrase&ntilde;a es: <b>'.$pw.'</b>.
		<br/>ingresa a nuestro sitio <a href="'.$hosting.'/inisesion.php">appartala.com</a><br/> y comienza a configurar tu perfil.<br/>Te recomendamos:<br/>
			<ul>
			1. Configurar los horarios de atención al publico. Hemos puesto un horario basico de atención de 8 am a 10 pm, pero puedes modificarlos cuando quieras en tu perfil<br/>
	        2. Agregar, modificar o eliminar las canchas que van a reservar tus clientes.<br/>
	        3. Establecer las tarifas de cada cancha por dia y hora que pagarán los clientes al reservar.<br/>
	        4. Si deseas puedes hacer reservas que se repiten semanalmente, para q los clientes no se crucen en esas horas.<br/>
	        5. Agrega fotos a la galeria para que los clientes reconozcan las canchas.<br/>
	        6. Al finalizar de configurar tu negocio puedes poner tu perfil en linea para que los clientes puedan encontrarla en Appartala.<br/>
			</ul>
			<br/>
			Esperamos que tu negocio tenga grandes beneficios a partir de ahora.
		</p> 
		</body> 
		</html>
		';
		//para el envío en formato HTML 
		$headers = "MIME-Version: 1.0\r\n"; 
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
		//dirección del remitente 
		$headers .= "From: Appartala <".$mailpri.">\r\n"; 
		//dirección de respuesta, si queremos que sea distinta que la del remitente 
		$headers .= "Reply-To: donotreply@appartala.com\r\n"; 
		//ruta del mensaje desde origen a destino 
		$headers .= "Return-path: ".$mailpri."\r\n"; 
		//direcciones que recibián copia 
		//$headers .= "Cc: roger_lozada@hotmail.com\r\n"; 
		//direcciones que recibirán copia oculta 
		//$headers .= "Bcc: roglozada@gmail.com\r\n"; 
		if(mail($destinatario,$asunto,$cuerpo,$headers)){
			echo 4;
		}else{
			echo 5;
		}
		//A APPARTALA
		$nombre = $nom;
		$destinatario = 'appartala@gmail.com';
		$asunto = "Hay un nuevo negocio en Appartala";		
		$cuerpo = ' 
		<html> 
		<head> 
		   <title>Hay un nuevo negocio en Appartala</title> 
		</head> 
		<body> 
		<h1>El negocio '.$nombre.' se ha registrado en Appartala!</h1> 
		<p> 
		<b>Ciudad: </b>'.$ciu.'<br/>.
		<b>Estado: </b>'.$esta.'<br/>.
		<b>Pais: </b>'.$pais.'<br/>.
		<b>Usuario: </b>'.$username.'<br/>.
		<b>Email: </b>'.$email.'<br/>.
		</p>
		</body> 
		</html>
		';
		//para el envío en formato HTML 
		$headers = "MIME-Version: 1.0\r\n"; 
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
		//dirección del remitente 
		$headers .= "From: Appartala <".$mailpri.">\r\n"; 
		//dirección de respuesta, si queremos que sea distinta que la del remitente 
		$headers .= "Reply-To: donotreply@appartala.com\r\n"; 
		//ruta del mensaje desde origen a destino 
		$headers .= "Return-path: ".$mailpri."\r\n"; 
		//direcciones que recibián copia 
		$headers .= "Cc: ".$mailpri."\r\n"; 
		//direcciones que recibirán copia oculta 
		//$headers .= "Bcc: roglozada@gmail.com\r\n"; 
		mail($destinatario,$asunto,$cuerpo,$headers);
			
	}
	else{
		echo 3;
	}
	
	function resizeImagen($ruta, $nombre, $alto, $ancho,$nombreN,$extension){
	    $rutaImagenOriginal = $ruta.$nombre;
	    if($extension == 'GIF' || $extension == 'gif'){
	    	$img_original = imagecreatefromgif($rutaImagenOriginal);
	    }
	    if($extension == 'jpg' || $extension == 'JPG'){
	    	$img_original = imagecreatefromjpeg($rutaImagenOriginal);
	    }
	    if($extension == 'jpeg' || $extension == 'JPEG'){
	    	$img_original = imagecreatefromjpeg($rutaImagenOriginal);
	    }
	    if($extension == 'bmp' || $extension == 'BMP'){
	    	$img_original = imagecreatefrombmp($rutaImagenOriginal);
	    }
	    if($extension == 'png' || $extension == 'PNG'){
	    	$img_original = imagecreatefrompng($rutaImagenOriginal);
	    }
	    $max_ancho = $ancho;
	    $max_alto = $alto;
	    list($ancho,$alto)=getimagesize($rutaImagenOriginal);
	    $x_ratio = $max_ancho / $ancho;
	    $y_ratio = $max_alto / $alto;
	    if( ($ancho <= $max_ancho) && ($alto <= $max_alto) ){//Si ancho 
	  		$ancho_final = $ancho;
			$alto_final = $alto;
		} elseif (($x_ratio * $alto) < $max_alto){
			$alto_final = ceil($x_ratio * $alto);
			$ancho_final = $max_ancho;
		} else{
			$ancho_final = ceil($y_ratio * $ancho);
			$alto_final = $max_alto;
		}
	    $tmp=imagecreatetruecolor($ancho_final,$alto_final);
	    imagecopyresampled($tmp,$img_original,0,0,0,0,$ancho_final, $alto_final,$ancho,$alto);
	    imagedestroy($img_original);
	    $calidad=70;
	    imagejpeg($tmp,$ruta.$nombreN,$calidad);
	    
	}

	function imagecreatefrombmp($filename) {
		$f = fopen($filename, "rb");
		
	    //read header    
	    $header = fread($f, 54);
	    $header = unpack('c2identifier/Vfile_size/Vreserved/Vbitmap_data/Vheader_size/'.
	    'Vwidth/Vheight/vplanes/vbits_per_pixel/Vcompression/Vdata_size/'.
	    'Vh_resolution/Vv_resolution/Vcolors/Vimportant_colors', $header);

		if ($header['identifier1'] != 66 or $header['identifier2'] != 77)
			return false;
			
		if ($header['bits_per_pixel'] != 24)
			return false;
		
		$wid2 = ceil((3 * $header['width']) / 4) * 4;
		
		$wid = $header['width'];
		$hei = $header['height'];

		$img = imagecreatetruecolor($header['width'], $header['height']);
		
		//read pixels
		for ($y = $hei - 1; $y >= 0; $y--) {
			$row = fread($f, $wid2);
			$pixels = str_split($row, 3);
			
			for ($x = 0; $x < $wid; $x++) {
				imagesetpixel($img, $x, $y, dwordize($pixels[$x]));
			}
		}
		fclose($f);
		return $img;
	}

	function dwordize($str) {
		$a = ord($str[0]);
		$b = ord($str[1]);
		$c = ord($str[2]);
		return $c * 256 * 256 + $b * 256 + $a;
	}

 ?>