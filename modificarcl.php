<?php 
	require ('server/hosting.php');
	require_once('server/recaptchalib.php');
	include('server/key_captcha.php');
	require ('server/conexion.php');
	require ('server/cadena.php');
	/* VALIDACION DE RECAPTCHA*/
	// tu clave secreta
	$secret = $privatekey;	 
	// respuesta vacía
	$response = null;	 
	// comprueba la clave secreta
	$reCaptcha = new ReCaptcha($secret);
	// si se detecta la respuesta como enviada
	if ($_POST["g-recaptcha-response"]) {
	$response = $reCaptcha->verifyResponse(
	        $_SERVER["REMOTE_ADDR"],
	        $_POST["g-recaptcha-response"]
	    );
	}
	if ($response != null && $response->success) {
    // Your code here to handle a successful verification	    
		$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
		if(isset($_POST['nombres']) && !empty($_POST['nombres'])&&
			isset($_POST['apellidos']) && !empty($_POST['apellidos'])&&
			isset($_POST['usuario']) && !empty($_POST['usuario'])&&
			isset($_POST['nacimiento']) && !empty($_POST['nacimiento'])&&
			isset($_POST['email']) && !empty($_POST['email'])&&
			isset($_POST['pw']) && !empty($_POST['pw'])&&
			isset($_POST['pw2']) && !empty($_POST['pw2'])&&
			isset($_POST['idcli']) && !empty($_POST['idcli'])&&
			isset($_POST['tel']) && !empty($_POST['tel']))
		{
			$nom = ucwords(strtolower(utf8_decode($_POST['nombres'])));
			$apell = ucwords(strtolower(utf8_decode($_POST['apellidos'])));
			
			$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
			$reemplazar=array(" ", " ", " ", " ");
			//Si el campo foto viene con archivo Actualizar con foto
			if ($_FILES['foto']['tmp_name']) {
				$fotoant = $_POST['fotoant'];
				if ($fotoant != '' && $fotoant != 'img/user.png') {
					unlink($fotoant);
				}
				$ruta = "imagenc";
				$nombreArchivo = $_FILES['foto']['name'];
				$archivo = $_FILES['foto']['tmp_name'];
				$ext = explode(".", $_FILES['foto']['name']);
				$nuevonombre = $_POST['idcli'].".".$ext[1];
				move_uploaded_file($archivo, $ruta."/".$nombreArchivo);
				resizeImagen($ruta.'/', $nombreArchivo, 400, 400,$nuevonombre,$ext[1]);
				unlink($ruta.'/'.$nombreArchivo);
				$ruta = $ruta."/".$nuevonombre;
				$con->query("UPDATE cliente SET nombres = '$nom', apellidos = '$apell', idUsuario = '$_POST[usuario]', fechaNac = '$_POST[nacimiento]', email = '$_POST[email]', pw = '$_POST[pw]', telefono = '$_POST[tel]', fotoc = '$ruta' 
					WHERE idCliente = '$_POST[idcli]'")
				or die ("Error al actualizar con foto... ". mysqli_error($con));
				//echo "Actualizado con foto <br/>";
			}else{
				// Actualizar registro sin foto
				$ruta="";
				$con->query("UPDATE cliente SET nombres = '$nom', apellidos = '$apell', idUsuario = '$_POST[usuario]', fechaNac = '$_POST[nacimiento]', email = '$_POST[email]', pw = '$_POST[pw]', telefono = '$_POST[tel]'
					WHERE idCliente = '$_POST[idcli]'")
					or die ("Error al actualizar sin foto... ". mysqli_error($con));
					echo '<script type="text/javascript">
				        if (window.history.length>1){
				          window.history.go(-1)
				        }else window.location="'.$hosting.'/inisesion.php"</script>';
				//echo "Actualizado sin foto <br/>";
			}
			echo '<script>window.location = "perfil.php";</script>';
		}else{
			echo("Verifica los campos para modificar la informacion del cliente'<br>");
		}
	} else {
		// What happens when the CAPTCHA was entered incorrectly
		die ("reCAPTCHA no fue ingresado correctamente. Regresa e intentalo de nuevo." .
		     "(reCAPTCHA dijo: " . $response . ")");
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