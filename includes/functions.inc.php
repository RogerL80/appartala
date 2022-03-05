<?php 
//Seleccionar todos los establecimientos
function lista_completa(){
	require('./server/conexion.php');
	$listaRegistro = array();
	// Establezco conexion
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	// Selecciona todos los registros en la tabla establecimiento
	$result  = $con -> query("SELECT * FROM establecimiento WHERE activo= '1'")
	or die("Error en la consulta" . mysqli_error($result));
	// Enviar datos al array
	if ($result -> num_rows !=0){
		// Convertimos el objeto
	    while ($row= $result->fetch_assoc()){
	    	$row['nombre'] = utf8_encode($row['nombre']);
	    	$row['direccion'] = utf8_encode($row['direccion']);
	    	$row['ciudad'] = utf8_encode($row['ciudad']);
	    	$listaRegistro[] = $row;	    	
	    }
	}
	// UTF8		
    return $listaRegistro;
}

function buscar($buscar){
	require('./server/conexion.php');
	$listaRegistro = array();
	// Establezco conexion
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	// Selecciona todos los registros en la tabla establecimiento
	$result  = $con -> query("SELECT T1.*, T2.nombrepais FROM establecimiento T1 INNER JOIN paises T2 ON T1.isopais = T2.iso WHERE (T1.nombre like '%$buscar%' or T1.ciudad like '%$buscar%' or T1.direccion like '%$buscar%') AND T1.activo = 1 ORDER BY T1.nombre")
	or die("Error en la consulta" . mysqli_error($result));
	// Enviar datos al array
	if ($result -> num_rows !=0){
		// Convertimos el objeto
	    while ($row= $result->fetch_assoc()){
	    	$row['nombre'] = utf8_encode($row['nombre']);
	    	$row['direccion'] = utf8_encode($row['direccion']);
	    	$row['ciudad'] = utf8_encode($row['ciudad']);
	    	$listaRegistro[] = $row;	    	
	    }
	}
	// UTF8		
    return $listaRegistro;
}
//Seleccionar el negocio
function sel_estable($negocio){
	require('./server/conexion.php');
	$estable = array();
	// Establezco conexion
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	// Selecciona todos los registros en la tabla establecimiento
	$result  = $con -> query("SELECT T1.*, T2.nombrepais FROM establecimiento T1 INNER JOIN paises T2 ON T1.isopais = T2.iso WHERE idEstablecimiento = '".$negocio."'")
	or die("Error en la consulta" . mysqli_error($result));
	// Enviar datos al array
	if ($result -> num_rows !=0){
		// Convertimos el objeto
	    while ($row= $result->fetch_assoc()){
    		$row['nombre'] = utf8_encode($row['nombre']);
	    	$row['direccion'] = utf8_encode($row['direccion']);
	    	$row['ciudad'] = utf8_encode($row['ciudad']);
	    	$estable[] = $row;
	    }
	}			
    return $estable;
}

//Seleccionar el negocio
function sel_negocio($negocio){
	require('./server/conexion.php');
	$estable = array();
	// Establezco conexion
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	// Selecciona todos los registros en la tabla establecimiento
	$result  = $con -> query("SELECT T1.*, T2.nombrepais FROM establecimiento T1 INNER JOIN paises T2 ON T1.isopais = T2.iso WHERE idEstablecimiento = '".$negocio."'")
	or die("Error en la consulta" . mysqli_error($result));
	// Enviar datos al array
	if ($result -> num_rows !=0){
		// Convertimos el objeto
	    while ($row= $result->fetch_assoc()){
    		$row['nombre'] = utf8_encode($row['nombre']);
	    	$row['direccion'] = utf8_encode($row['direccion']);
	    	$row['ciudad'] = utf8_encode($row['ciudad']);
	    	$estable = $row;
	    }
	}			
    return $estable;
}
//Creamos una función que detecte el idioma del navegador del cliente. 
function getUserLanguage() {  
     $idioma =substr($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2); 
     return $idioma;  
} 

//Horario de Inicio y Fin de atencion
function getMinMax($negocio) {
	require('./server/conexion.php');
	$Min = array();
	// Establezco conexion
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	// Selecciona todos los registros en la tabla establecimiento
	$result  = $con -> query("SELECT MIN(horaIni), MAX(horaFin) FROM atencion 
		WHERE idEstablecimiento = '".$negocio."'")
		or die("Error en la consulta" . mysqli_error($result));
	// Enviar datos al array
	if ($result -> num_rows !=0){
		// Convertimos el objeto
	    while ($row= $result->fetch_assoc()){
	    	$Min = $row;
	    }
	}			
    return $Min;
}

//Seleccionar Canchas
function getCanchas($negocio) {
	require('./server/conexion.php');
	$canchas = array();
	// Establezco conexion
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	// Selecciona todos los registros en la tabla establecimiento
	$result  = $con -> query("SELECT T1.*, T2.descrip FROM canchas T1 INNER JOIN superficie T2 ON T1.idSuperficie = T2.idSuperficie 
		WHERE idEstablecimiento = '".$negocio."' AND activa='1'")
		or die("Error en la consulta" . mysqli_error($result));
	// Enviar datos al array
	if ($result -> num_rows !=0){
		// Convertimos el objeto
	    while ($row= $result->fetch_assoc()){
	    	$row['nombreCancha'] = utf8_encode($row['nombreCancha']);
	    	$row['descriCancha'] = utf8_encode($row['descriCancha']);
	    	$canchas[] = $row;
	    }
	}			
    return $canchas;
}

//Seleccionar Cancha
function getCancha($can) {
	require('./server/conexion.php');
	$canchas = array();
	// Establezco conexion
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	// Selecciona todos los registros en la tabla establecimiento
	$result  = $con -> query("SELECT T1.*, T2.descrip FROM canchas T1 INNER JOIN superficie T2 
		ON T1.idSuperficie = T2.idSuperficie WHERE idCancha = '".$can."'")
		or die("Error en la consulta" . mysqli_error($result));
	// Enviar datos al array
	if ($result -> num_rows !=0){
		// Convertimos el objeto
	    while ($row= $result->fetch_assoc()){
	    	$row['nombreCancha'] = utf8_encode($row['nombreCancha']);
	    	$row['descriCancha'] = utf8_encode($row['descriCancha']);
	    	$canchas[] = $row;
	    }
	}			
    return $canchas;
}

//Seleccionar Cancha
function get_Cancha($can) {
	require('./server/conexion.php');
	$canchas = array();
	// Establezco conexion
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	// Selecciona todos los registros en la tabla establecimiento
	$result  = $con -> query("SELECT T1.*, T2.descrip FROM canchas T1 INNER JOIN superficie T2 
		ON T1.idSuperficie = T2.idSuperficie WHERE idCancha = '".$can."'")
		or die("Error en la consulta" . mysqli_error($result));
	// Enviar datos al array
	if ($result -> num_rows !=0){
		// Convertimos el objeto
	    while ($row= $result->fetch_assoc()){
	    	$row['nombreCancha'] = utf8_encode($row['nombreCancha']);
	    	$row['descriCancha'] = utf8_encode($row['descriCancha']);
	    	$canchas = $row;
	    }
	}			
    return $canchas;
}
//Horario de atencion por Dias
function getAtencion($negocio) {
	require('./server/conexion.php');
	$atencion = array();
	// Establezco conexion
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	// Selecciona todos los registros en la tabla establecimiento
	$result  = $con -> query("SELECT idDia, horaIni, horaFin FROM atencion 
		WHERE idEstablecimiento = '".$negocio."'")
		or die("Error en la consulta" . mysqli_error($result));
	// Enviar datos al array
	if ($result -> num_rows !=0){
		// Convertimos el objeto
	    while ($row= $result->fetch_assoc()){
	    	$atencion[] = $row;
	    }
	}			
    return $atencion;
}

//Seleccionar horas reservadas en el negocio
function getReservas($cancha) {
	require('./server/conexion.php');
	$reservas = array();
	// Establezco conexion
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	// Selecciona todos los registros en la tabla establecimiento
	$result  = $con -> query("SELECT idCliente, fechaIni, hora, estado FROM reservas 
		WHERE (idCancha = '".$cancha."') AND (fechaIni >= CURDATE())")
		or die("Error en la consulta" . mysqli_error($result));
	// Enviar datos al array
	if ($result -> num_rows !=0){
		// Convertimos el objeto
	    while ($row= $result->fetch_assoc()){
	    	$reservas[] = $row;
	    }
	}			
    return $reservas;
}

//Seleccionar horas reservadas en el negocio
function getReservas2($cancha) {
	require('./server/conexion.php');
	$reservas = array();
	// Establezco conexion
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	// Selecciona todos los registros en la tabla establecimiento
	$result  = $con -> query("SELECT T1.*, T2.* FROM reservas T1 INNER JOIN cliente T2 ON T1.idCliente = T2.idCliente  
		WHERE (idCancha = '".$cancha."') AND (fechaIni >= CURDATE())")
		or die("Error en la consulta" . mysqli_error($result));
	// Enviar datos al array
	if ($result -> num_rows !=0){
		// Convertimos el objeto
	    while ($row= $result->fetch_assoc()){
	    	$row['nombres'] = utf8_encode($row['nombres']);
	    	$row['apellidos'] = utf8_encode($row['apellidos']);
	    	$reservas[] = $row;
	    }
	}			
    return $reservas;
}

//Seleccionar fotos del negocio
function getFotos($negocio) {
	require('./server/conexion.php');
	$fotos = array();
	// Establezco conexion
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	// Selecciona todos los registros en la tabla establecimiento
	$result  = $con -> query("SELECT ruta, descripcion FROM foto 
		WHERE idEstablecimiento = '".$negocio."'")
		or die("Error en la consulta" . mysqli_error($result));
	// Enviar datos al array
	if ($result -> num_rows !=0){
		// Convertimos el objeto
	    while ($row= $result->fetch_assoc()){
	    	$fotos[] = $row;
	    }
	}			
    return $fotos;
}

//Seleccionar fotos del negocio
function getSuper() {
	require('./server/conexion.php');
	$super = array();
	// Establezco conexion
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	// Selecciona todos los registros en la tabla establecimiento
	$result  = $con -> query("SELECT * FROM superficie WHERE 1")
		or die("Error en la consulta" . mysqli_error($result));
	// Enviar datos al array
	if ($result -> num_rows !=0){
		// Convertimos el objeto
	    while ($row= $result->fetch_assoc()){
	    	$super[] = $row;
	    }
	}			
    return $super;
}

//Seleccionar estado de reserva
function getEstado() {
	require('./server/conexion.php');
	$estado = array();
	// Establezco conexion
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	// Selecciona todos los registros en la tabla establecimiento
	$result  = $con -> query("SELECT * FROM estado WHERE 1")
		or die("Error en la consulta" . mysqli_error($result));
	// Enviar datos al array
	if ($result -> num_rows !=0){
		// Convertimos el objeto
	    while ($row= $result->fetch_assoc()){
	    	$estado[] = $row;
	    }
	}			
    return $estado;
}

//Seleccionar Horarios
function getHorarios($negocio) {
	require('./server/conexion.php');
	$horarios = array();
	// Establezco conexion
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	// Selecciona todos los registros en la tabla establecimiento
	$result  = $con -> query("SELECT * FROM atencion WHERE idEstablecimiento = '".$negocio."'")
		or die("Error en la consulta" . mysqli_error($result));
	// Enviar datos al array
	if ($result -> num_rows !=0){
		// Convertimos el objeto
	    while ($row= $result->fetch_assoc()){
	    	/*$row['nombreCancha'] = utf8_encode($row['nombreCancha']);
	    	$row['descriCancha'] = utf8_encode($row['descriCancha']);
	    	*/
	    	$horarios[] = $row;
	    }
	}			
    return $horarios;
}

//Seleccionar Tarifas
function getTarifas($can) {
	require('./server/conexion.php');
	$tarifas = array();
	// Establezco conexion
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	// Selecciona todos los registros en la tabla establecimiento
	$result  = $con -> query("SELECT idDia, hora, valor FROM tarifas WHERE idCancha = '".$can."'")
		or die("Error en la consulta" . mysqli_error($result));
	// Enviar datos al array
	if ($result -> num_rows !=0){
		// Convertimos el objeto
	    while ($row= $result->fetch_assoc()){
	    	/*$row['nombreCancha'] = utf8_encode($row['nombreCancha']);
	    	$row['descriCancha'] = utf8_encode($row['descriCancha']);
	    	*/
	    	$tarifas[] = $row;
	    }
	}			
    return $tarifas;
}

function fechafinal($negocio){
	require('./server/conexion.php');
	$fechamax = array();
	// Establezco conexion
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	// Selecciona todos los registros en la tabla establecimiento
	$result  = $con -> query("SELECT fechafin FROM establecimiento WHERE idEstablecimiento = '".$negocio."'")
	or die("Error en la consulta" . mysqli_error($result));
	// Enviar datos al array
	if ($result -> num_rows !=0){
		// Convertimos el objeto
	    while ($row= $result->fetch_assoc()){
	    	$fechamax = $row;	    	
	    }
	}
	// UTF8		
    return $fechamax;
}

//Seleccionar el negocio
function getCliente($cod){
	require('./server/conexion.php');
	$usuario = array();
	// Establezco conexion
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	// Selecciona todos los registros en la tabla establecimiento
	$result = $con -> query("SELECT * FROM cliente WHERE idCliente = '".$cod."'")
	or die("Error en la consulta" . mysqli_error($result));
	// Enviar datos al array
	if ($result -> num_rows !=0){
		// Convertimos el objeto
	    while ($row= $result->fetch_assoc()){
    		$row['nombres'] = utf8_encode($row['nombres']);
    		$row['apellidos'] = utf8_encode($row['apellidos']);
	    	//$row['direccion'] = utf8_encode($row['direccion']);
	    	//$row['ciudad'] = utf8_encode($row['ciudad']);
	    	$usuario[] = $row;
	    }
	}			
    return $usuario;
}
//Seleccionar horas reservadas en el negocio
function getReservaCli($cli) {
	require('./server/conexion.php');
	$reservas = array();
	// Establezco conexion
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	// Selecciona todos los registros en la tabla reservas
	$result = $con -> query("SELECT T1.*, T2.nombreCancha, T3.nombre, T3.idEstablecimiento, T3.email, T4.estadoReserva FROM reservas T1 INNER JOIN canchas T2 
		 ON T1.idCancha = T2.idCancha INNER JOIN establecimiento T3 ON T3.idEstablecimiento = T2.idEstablecimiento 
		 INNER JOIN estado T4 ON T4.codEstado = T1.estado
		 WHERE idCliente = '".$cli."' ORDER BY fechaRes DESC")
		or die("Error en la consulta" . mysqli_error($result));
		 
	// Enviar datos al array
	if ($result -> num_rows !=0){
		// Convertimos el objeto
	    while ($row= $result->fetch_assoc()){
	    	$row['nombreCancha'] = utf8_encode($row['nombreCancha']);
	    	$row['nombre'] = utf8_encode($row['nombre']);
	    	$reservas[] = $row;
	    }
	}			
    return $reservas;
}

function getcodCliente($cod){
	require('./server/conexion.php');
	$usuario = array();
	// Establezco conexion
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	// Selecciona todos los registros en la tabla establecimiento
	$result = $con -> query("SELECT idCliente FROM cliente WHERE idEstablecimiento = '".$cod."'")
	or die("Error en la consulta" . mysqli_error($result));
	// Enviar datos al array
	if ($result -> num_rows !=0){
		// Convertimos el objeto
	    while ($row= $result->fetch_assoc()){
    		
	    	$usuario = $row;
	    }
	}			
    return $usuario;
}

function getBloqueo($cod){
	require('./server/conexion.php');
	$bloqueo = array();
	// Establezco conexion
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	// Selecciona todos los registros en la tabla establecimiento
	$result = $con -> query("SELECT bloqueado FROM cliente WHERE idCliente = '".$cod."'")
	or die("Error en la consulta" . mysqli_error($result));
	// Enviar datos al array
	if ($result -> num_rows !=0){
		// Convertimos el objeto
	    while ($row= $result->fetch_assoc()){
    		
	    	$bloqueo = $row;
	    }
	}			
    return $bloqueo;
}

function sumarDias(){
	$fecha = date('Y-m-j');
	$nuevafecha = strtotime ( '+2 day' , strtotime ( $fecha ) ) ;
	$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
	 
	echo $nuevafecha;
}
//Horario de atencion por Dias
function getFoto($cod) {
	require('./server/conexion.php');
	$galeria = array();
	// Establezco conexion
	$con = new mysqli($host, $user, $pw, $db)or die("Error de conexión" . mysqli_error($con));
	// Selecciona todos los registros en la tabla establecimiento
	$result  = $con -> query("SELECT idFoto,ruta,descripcion FROM foto WHERE idEstablecimiento = '".$cod."'")
	or die("Error en la consulta" . mysqli_error($result));
	// Enviar datos al array
	if ($result -> num_rows !=0){
		// Convertimos el objeto
	    while ($row= $result->fetch_assoc()){
	    	$row['descripcion'] = utf8_encode($row['descripcion']);
	    	$galeria[] = $row;
	    }
	}			
    return $galeria;
}
?>