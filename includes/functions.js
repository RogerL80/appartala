var meses = [31,28,31,30,31,30,31,31,30,31,30,31];
var celdaAnt = new Object();
var fun = new Object();

function modificar(fecha,fn,reserva,tarifa,vector){
    var cliente = document.getElementById('client').value;
    var ws = 1;
    var numdias = new String(); // vector que guarda los dias de la semana cuando cambian
    var nummes = new String(); // vector que guarda los meses de la semana cuando cambian
    var numanio = new String(); // vector que guard, a los años de la semana cuando 
    numdias[0] = "0";
    nummes[0] = "0";
    numanio[0] = "0";
    fun = fn; // ASIGNAR A LA VARIABLE GLOBAL EL FN FILE
    details = fecha.split("-");
    d = new Date(details[0],details[1]-1,details[2]);
    //FECHA SELECCIONADA
    var mes = parseInt(details[1]);
    var dia = parseInt(details[2]);
    var anio = parseInt(details[0]);
    //SABER SI ES BISIESTO
    if (!(anio%4)) {
        meses[1] = 29;
    }else{
        meses[1] = 28;
    };
    //SI EL MES ANTERIOR ES DICIEMBRE
    var mesant = false;
    var antemes = mes-1;
    if (mes==1) {
        antemes = 12;
    };
    //SI EL DIA DE LA SEMANA ES DOMINGO
    var diasem = d.getDay() - 1;
    if (diasem<0) {
        diasem = 6;
    };
    var inisem = dia - diasem; //el dia  con el que comienza la semana
    //IDENTIFICAR FIN Y COMIENZO DE MES
    if (inisem<1) {
        inisem = meses[antemes-1] + inisem;
        mesant = true;
    };
    var nomdia; // Nombre de los dias de la semana de acuerdo al idioma
    var diacal = inisem; //variable de impresion del dia
    //TITULO DEL HORARIO
    document.getElementById("parrafo").innerHTML = fn.months[mes-1]+" "+details[0];
    //llenar los dias(encabezados) del horario de acuerdo al idioma
    for (var i = 0; i < 7; i++) {
        switch (i){
            case 0: nomdia = fn.daysShort[i+ws]+" ";
                break;
            case 1: nomdia = fn.daysShort[i+ws]+" ";
                break;
            case 2: nomdia = fn.daysShort[i+ws]+" ";
                break;
            case 3: nomdia = fn.daysShort[i+ws]+" ";
                break;
            case 4: nomdia = fn.daysShort[i+ws]+" ";
                break;
            case 5: nomdia = fn.daysShort[i+ws]+" ";
                break;
            case 6: if ((i+ws)>6) {
                    nomdia = fn.daysShort[0]+" "
                }else nomdia = fn.daysShort[i+ws]+" ";
                break;
        }
        // Validacion para el calendario
        if (mesant) {
            if ((inisem+i)>meses[antemes-1]) {
                diacal = inisem-meses[antemes-1];
                nummes[i] = mes;
                numanio[i] = anio;
            }else{
                nummes[i] = antemes;
                if(antemes==12){
                    numanio[i] = anio - 1;
                }
                else{
                    numanio[i] = anio;
                };
            };
        }else{
            if ((inisem+i)>meses[mes-1]) {
                diacal = inisem-meses[mes-1];
                nummes[i] = mes + 1;
                numanio[i] = anio;
                if (nummes[i] > 12){
                    nummes[i] = 1;
                    numanio[i] = anio + 1;
                }
            }else{
                nummes[i] = mes;
                numanio[i] = anio;
            };
        };
        //VALIDAMOS DIAS Y MESES DE UN DIGITO PARA PASARLO A DOS
        var diaf = diacal + i;
        if (nummes[i]<10){ nummes[i] = "0"+nummes[i]};
        if (diaf<10){ diaf = "0"+diaf };
        numdias[i] = numanio[i]+"-"+ nummes[i] +"-"+ diaf;//numeros de dias de la semana cuando cambian
        document.getElementById("d"+(i+1)).innerHTML = nomdia + diaf;
    };
    // FUNCION Llenar datos de celdas (Ocupadas, Disponibles, etc...)
    var x = document.getElementsByClassName("horario");// Tomamos las casillas del horario a modificar
    var txt = "";//variable de prueba
    var i;
    //Llenamos el horario
    for (i = 0; i < x.length; i++) {
        x[i].style.color = "";
        var indice = (x[i].cellIndex)-1;
        var sel = numdias[indice]+"-"+x[i].parentNode.id;
        for(k=0;k<tarifa.length;k++){
            if(x[i].dataset.dia == tarifa[k].idDia){
                x[i].innerHTML = accounting.formatNumber(tarifa[k].valor);//ASIGNAMOS LA TARIFA A LA CASILLA 
                x[i].classList.remove("fpasada","reservado","preapartado","propia");
                x[i].classList.add("libre");
                break;
            }
        }       
        if(!comparar(sel)){
            x[i].setAttribute("onclick","");//deshabilitamos el clic en la celda de fecha menor -----------------
            x[i].innerHTML ="";
            x[i].classList.remove("libre","reservado","preapartado","propia");
            x[i].classList.add("fpasada");//---------------------------------------------
        }else{
            var j=0;
            for(j=0;j < reserva.length;j++){
                if(sel==reserva[j].fechaIni && reserva[j].estado != 3){// ASIGNAMOS SI ESTA RESERVADA LA HORA
                    x[i].innerHTML = vector[reserva[j].estado].descripcion;
                    x[i].setAttribute("onclick","");//deshabilitamos el clic en la celda de fecha menor-----------
                    x[i].classList.remove("fpasada","flibre","preapartado","propia");
                    x[i].classList.add("reservado");
                    if(reserva[j].estado == 1){
                        x[i].innerHTML = (vector[reserva[j].estado].descripcion).bold();//------------------
                        x[i].classList.remove("fpasada","flibre","reservado","propia");
                        x[i].classList.add("preapartado");
                    }
                    if(reserva[j].idCliente == cliente){        // Si la reserva es del cliente se pone en este color
                        x[i].classList.remove("fpasada","flibre","reservado","preapartado");
                        x[i].classList.add("propia");
                    }
                    break;
                };
            }             
            if(j==reserva.length){
                x[i].setAttribute("onclick","emergente(this)");//habilitamos el clic en la celda de fecha mayor-----------
                x[i].setAttribute("id",sel);//cambiamos el id de cada celda
                x[i].classList.remove("fpasada","reservado","preapartado","propia");
                x[i].classList.add("libre");
                celdaAnt = x[i]; //ASIGNAR A OBJETO GLOBAL
                celdaAnt.classList.remove("seleccionado");// QUITAR PROPIEDAD DE SELECCIONADO
            }
        }
    }
}

function modificar2(fecha,fn,reserva,tarifa,vector){
    var cliente = document.getElementById('client').value;
    var ws = 1;
    var numdias = new String(); // vector que guarda los dias de la semana cuando cambian
    var nummes = new String(); // vector que guarda los meses de la semana cuando cambian
    var numanio = new String(); // vector que guard, a los años de la semana cuando 
    numdias[0] = "0";
    nummes[0] = "0";
    numanio[0] = "0";
    fun = fn; // ASIGNAR A LA VARIABLE GLOBAL EL FN FILE
    details = fecha.split("-");
    d = new Date(details[0],details[1]-1,details[2]);
    //FECHA SELECCIONADA
    var mes = parseInt(details[1]);
    var dia = parseInt(details[2]);
    var anio = parseInt(details[0]);
    //SABER SI ES BISIESTO
    if (!(anio%4)) {
        meses[1] = 29;
    }else{
        meses[1] = 28;
    };
    //SI EL MES ANTERIOR ES DICIEMBRE
    var mesant = false;
    var antemes = mes-1;
    if (mes==1) {
        antemes = 12;
    };
    //SI EL DIA DE LA SEMANA ES DOMINGO
    var diasem = d.getDay() - 1;
    if (diasem<0) {
        diasem = 6;
    };
    var inisem = dia - diasem; //el dia  con el que comienza la semana
    //IDENTIFICAR FIN Y COMIENZO DE MES
    if (inisem<1) {
        inisem = meses[antemes-1] + inisem;
        mesant = true;
    };
    var nomdia; // Nombre de los dias de la semana de acuerdo al idioma
    var diacal = inisem; //variable de impresion del dia
    //TITULO DEL HORARIO
    document.getElementById("parrafo").innerHTML = fn.months[mes-1]+" "+details[0];
    //llenar los dias(encabezados) del horario de acuerdo al idioma
    for (var i = 0; i < 7; i++) {
        switch (i){
            case 0: nomdia = fn.daysShort[i+ws]+" ";
                break;
            case 1: nomdia = fn.daysShort[i+ws]+" ";
                break;
            case 2: nomdia = fn.daysShort[i+ws]+" ";
                break;
            case 3: nomdia = fn.daysShort[i+ws]+" ";
                break;
            case 4: nomdia = fn.daysShort[i+ws]+" ";
                break;
            case 5: nomdia = fn.daysShort[i+ws]+" ";
                break;
            case 6: if ((i+ws)>6) {
                    nomdia = fn.daysShort[0]+" "
                }else nomdia = fn.daysShort[i+ws]+" ";
                break;
        }
        // Validacion para el calendario
        if (mesant) {
            if ((inisem+i)>meses[antemes-1]) {
                diacal = inisem-meses[antemes-1];
                nummes[i] = mes;
                numanio[i] = anio;
            }else{
                nummes[i] = antemes;
                if(antemes==12){
                    numanio[i] = anio - 1;
                }
                else{
                    numanio[i] = anio;
                };
            };
        }else{
            if ((inisem+i)>meses[mes-1]) {
                diacal = inisem-meses[mes-1];
                nummes[i] = mes + 1;
                numanio[i] = anio;
                if (nummes[i] > 12){
                    nummes[i] = 1;
                    numanio[i] = anio + 1;
                }
            }else{
                nummes[i] = mes;
                numanio[i] = anio;
            };
        };
        //VALIDAMOS DIAS Y MESES DE UN DIGITO PARA PASARLO A DOS
        var diaf = diacal + i;
        if (nummes[i]<10){ nummes[i] = "0"+nummes[i]};
        if (diaf<10){ diaf = "0"+diaf };
        numdias[i] = numanio[i]+"-"+ nummes[i] +"-"+ diaf;//numeros de dias de la semana cuando cambian
        document.getElementById("d"+(i+1)).innerHTML = nomdia + diaf;
    };
    // FUNCION Llenar datos de celdas (Ocupadas, Disponibles, etc...)
    var x = document.getElementsByClassName("horario");// Tomamos las casillas del horario a modificar
    var txt = "";//variable de prueba
    var i;
    //Llenamos el horario
    for (i = 0; i < x.length; i++) {
        x[i].style.color = "";
        var indice = (x[i].cellIndex)-1;
        var sel = numdias[indice]+"-"+x[i].parentNode.id;
        for(k=0;k<tarifa.length;k++){
            if(x[i].dataset.dia == tarifa[k].idDia){
                x[i].innerHTML = accounting.formatNumber(tarifa[k].valor);//ASIGNAMOS LA TARIFA A LA CASILLA 
                x[i].classList.remove("fpasada","reservado","preapartado","propia");
                x[i].classList.add("libre");
                break;
            }
        }       
        if(!comparar(sel)){
            x[i].setAttribute("onclick","");//deshabilitamos el clic en la celda de fecha menor
            x[i].innerHTML ="";
            x[i].classList.remove("libre","reservado","preapartado","propia");
            x[i].classList.add("fpasada");//---------------------------------------------
        }else{
            var j=0;
            for(j=0;j < reserva.length;j++){
                if(sel==reserva[j].fechaIni && reserva[j].estado != 3){// ASIGNAMOS SI ESTA RESERVADA LA HORA
                    x[i].innerHTML = vector[reserva[j].estado].descripcion;
                    //x[i].setAttribute("onclick","emergente2(this)");//deshabilita que el negocio pueda cambiar despues de reservada
                    x[i].setAttribute("onclick","modiEstadoRes(this,"+reserva[j].estado+")");//habilitamos que se pueda cambiar
                    x[i].classList.remove("libre","fpasada","preapartado","propia");
                    x[i].classList.add("reservado");//verde
                    x[i].setAttribute("title",reserva[j].cliente);
                    x[i].dataset.fechares = reserva[j].fechaIni;
                    x[i].dataset.mail = reserva[j].email;
                    x[i].dataset.res = reserva[j].idReserva;
                    x[i].dataset.tel = reserva[j].telefono;
                    if(reserva[j].estado == 1){//pre-apar
                        x[i].innerHTML = vector[reserva[j].estado].descripcion;
                        x[i].setAttribute("onclick","modiEstadoRes(this,"+reserva[j].estado+")");
                        x[i].classList.remove("libre","fpasada","reservado","propia");
                        x[i].classList.add("preapartado");
                    }
                    if(reserva[j].idCliente == cliente){        // Si la reserva es del negocio se pone en este color
                        x[i].setAttribute("onclick","modiEstadoRes(this,"+reserva[j].estado+")");
                        x[i].classList.remove("libre","fpasada","reservado","preapartado");
                        x[i].classList.add("propia");
                    }
                    break;
                };
            }             
            if(j==reserva.length){
                x[i].setAttribute("onclick","emergente(this)");//habilitamos el clic en la celda de fecha mayor
                x[i].setAttribute("id",sel);//cambiamos el id de cada celda
                x[i].classList.remove("fpasada","reservado","preapartado","propia");
                x[i].classList.add("libre");
                celdaAnt = x[i]; //ASIGNAR A OBJETO GLOBAL
                celdaAnt.classList.remove("seleccionado");// QUITAR PROPIEDAD DE SELECCIONADO
            }
        }
    }
}
//Cuando se da clic a cada celda
function emergente(element){
    d = element.id.split("-");
    celdaAnt.classList.remove("seleccionado");
    celdaAnt.classList.add("libre");
    element.classList.remove("libre");
    element.classList.add("seleccionado");
    celdaAnt = element;
    //LLENAR INFO DE LA RESERVA
    document.getElementById("fecha").value = fun.days[(element.cellIndex%7)]+", "+fun.months[d[1]-1]+" "+d[2]+", "+d[0]; 
    document.getElementById("horadia").value = element.parentNode.cells[0].innerHTML;
    document.getElementById("fechao").value = d[0]+"-"+d[1]+"-"+d[2]; 
    document.getElementById("horao").value = d[3];
    document.getElementById("precior").value = element.innerHTML;
    document.getElementById("precio").value = accounting.unformat(element.innerHTML);
    document.getElementById("parrafo").innerHTML = fun.months[d[1]-1]+" "+d[0];
    a = new Date();
    fecha = a.getFullYear()+"-"+(a.getMonth()+1)+"-"+a.getDate()+" "+a.getHours()+":"+a.getMinutes()+":"+a.getSeconds();
    document.getElementById("horactual").value = fecha;
      
}
//FUNCION CUANDO CLIC EN RESERVA HECHA
function emergente2(element){
    nombre = element.getAttribute("title");
    mail = element.dataset.mail;
    tel = element.dataset.tel;
    alert("Cliente: "+nombre+"\nEmail: "+mail+"\nTelefono: "+tel+"\nEstado: "+element.textContent);
}
// COMPARA FECHAS
function comparar(fe){
    lim = document.getElementById("prueba").innerHTML.split("-");
    l = new Date(lim[0],lim[1]-1,lim[2],23,59);
    seleccionada = fe.split("-");
    s = new Date(seleccionada[0],seleccionada[1]-1,seleccionada[2],seleccionada[3]);
    a = new Date();
    min = a.getMinutes();
    a.setMinutes(0,0,0);
    if (a < s && s < l){
        return true;
    }else{
        return false;
    }
}
// COMPARA 2 FECHAS
function comparar2(fe){
    partir = fe.split(" ");
    fch = partir[0];
    fc = fch.split("-");
    hr = partir[1];
    h = hr.split(":");
    //alert("hora: "+h[0]+":"+h[1]+":"+h[2]);
    b = new Date(fc[0],fc[1]-1,fc[2],h[0],h[1],h[2]);
    a = new Date();
    a.setMilliseconds(0);
    if (a < b){
        return true;
    }
    else return false;
}
//VALIDAR RECAPTCHA ANTES DE ENVIAR
function checkCaptcha(form){
    var captcha = $('#g-recaptcha-response').val();
    //alert('Estamos en checkForm :'+captcha);
    if(captcha != "") {
      return true;
    } else {
        alert("Error: Debes dar clic en 'No soy un robot'. Para avanzar.");
        return false;
    }
}
//CONFIRMAR CAMBIO DE ESTADO DE RESERVA
function confirmamodi(form){
    var r = confirm("Esta seguro que desea cambiar el estado de la reserva?");
    return r;
}
//VALIDAR HORARIOS
function validahorario(form){
    for (var i = 0; i < 7; i++) {
        var ini = parseInt(document.getElementById('ini'+(i+1)).value);
        var fin = parseInt(document.getElementById('fin'+(i+1)).value);
        if( ini >= fin){
            alert("Horario de entrada mayor que el de salida.");
            document.getElementById('ini'+(i+1)).focus();
            return false;
            break;
        }
    }
    return true;
}

// CARGAR UNA IMAGEN
function archivo(evt) {
  var files = evt.target.files; // FileList object
  // Obtenemos la imagen del campo "file".
  for (var i = 0, f; f = files[i]; i++) {
    //Solo admitimos imágenes.
    if (!f.type.match('image.*')) {
        continue;
    }
    var reader = new FileReader();
    reader.onload = (function(theFile) {
      return function(e) {
          // Insertamos la imagen
          document.getElementById("list").innerHTML = ['<img class="img-thumbnail img-responsive" src="', e.target.result,'" title="', escape(theFile.name), '"/>'].join('');
        };
    })(f);
    reader.readAsDataURL(f);
  }
}
// INICIAR SESION
function inisesion() {   
    if (document.getElementById("BtnIniciar")) {
        alert("Debe Iniciar Sesion");
        $('#myModal').modal('show');
    }else{
        a = new Date();
        fecha = a.getFullYear()+"-"+(a.getMonth()+1)+"-"+a.getDate()+" "+a.getHours()+":"+a.getMinutes()+":"+a.getSeconds();
        fb = document.getElementById("bloqueado").value;
        if(fb!="" && comparar2(fb)){
            alert("Usuario Bloqueado por unos dias");
            document.getElementById("msjalerta").setAttribute("class","alert alert-danger alert-dismissable");
            document.getElementById("msjalerta").innerHTML = '<button type="button" class="close" data-dismiss="alert">&times;</button>El usuario no puede apartar canchas hasta '+fb+', por haber cancelado una reserva anterior';
        }
        else{
            document.getElementById("precio").value = accounting.unformat(document.getElementById("precio").value);
            document.getElementById("horactual").value = fecha;
            document.getElementById('formulario').submit();
        }
    }
}
// CAMBIAR FOTO
function agregarFoto() {
  $('#fotoModal').modal('show');
}
// MODIFICAR ESTABLECIMIENTO
function modiEstable() {
    var pagina="./modiestable.php?em="+document.getElementById("idest").value;
    location.href=pagina;
}
// MODIFICAR ESTADO
function modiEstado(estado) {
    var pagina="./modiestado.php?em="+document.getElementById("idest").value+"&est="+estado;
    location.href=pagina;
}
// MODIFICAR CLIENTE
function modiCliente() {
    var pagina="./modicliente.php?cl="+document.getElementById("idest").value;
    location.href=pagina;
}
// AGREGAR CANCHA
function AgregarCancha() {
    $('#myModal2').modal('show');    
}
// MODIFICAR CANCHA
function modicancha(cod,can) {
    var x = document.getElementsByClassName(cod);// Tomamos las casillas a modificar
    var txt = "";//variable de prueba
    document.getElementById("nombre1").value = x[0].textContent;
    document.getElementById("descripcion1").value = x[1].textContent;
    document.getElementById("largo1").value = x[2].textContent;
    document.getElementById("ancho1").value = x[3].textContent;
    document.getElementById("jugadores1").value = x[4].textContent;
    document.getElementById("idcan").value = cod;
    document.getElementById("superficie1").value = can;
    $('#modiCancha').modal('show');
}
// MODIFICAR ESTADO RESERVA
function modiEstadoRes(element,est) {
    document.getElementById("nombres").textContent = element.getAttribute("title");
    document.getElementById("emailr").textContent = element.dataset.mail;
    document.getElementById("idreser").value = element.dataset.res;
    document.getElementById("estad").value = est;
    $('#modiEstado').modal('show');
}
// ELIMINAR CANCHA
function elicancha(cod) {    
    document.getElementById('idcan1').value = cod;
    $('#elimiCancha').modal('show');
}
// ELIMINAR RESERVA
function cancelar(element,cod,email,id) {
    a= element.parentNode;
    document.getElementById('mailcli').value = document.getElementById('correo').textContent;
    document.getElementById('nomclient').value = document.getElementById('nom').textContent;
    document.getElementById('idcan1').value = cod;
    document.getElementById('nomest').value = a.cells[3].textContent;
    document.getElementById('idesta').value = id;
    document.getElementById('mailest').value = email;
    document.getElementById('nomcancha').value = a.cells[4].textContent;
    document.getElementById('fechareser').value = a.cells[1].textContent;
    document.getElementById('horareser').value = a.cells[2].textContent;
    document.getElementById('sancion').value = mostrarFecha(5);
    $('#eliReserva').modal('show');
}
//MODIFICAR LABEL DAYS
function modays(fn){    
    for (var i = 0; i < 7; i++) {
        document.getElementById("d"+i).textContent = fn.daysShort[i];
    }
}
//MODIFICAR LABEL DAYS TARIFAS
function motari(fn){    
    for (var i = 0; i < 7; i++) {
        document.getElementById("t"+i).textContent = fn.daysShort[i];
    }
}
//FUNCION GENERAR ARRAY
function generar(arrayTabla){
    alert("Estoy en generar");
    for(i=0;i<arrayTabla.length;i++){
        document.getElementById("texto").innerHTML = "<br/>";
        for (j=0;j<arrayTabla[i].length;j++){
            document.getElementById("texto").innerHTML = " - valor celda: "+arrayTabla[i][j];
        }
    }
}
// FUNCION miFunction
function bloqueodias(horarios){
    var x = document.getElementsByClassName("dias");// Tomamos las casillas del horario a modificar
    // Container <div> where dynamic content will be placed
    var container = document.getElementById("campos");
    // Clear previous contents of the container
    while (container.hasChildNodes()) {
        container.removeChild(container.lastChild);
    }
    for (var i = 0; i < x.length; i++) {
        var padre = x[i].parentNode;
        var d = (i+1)%7;
        x[i].classList.add("normal");
        if(padre.id<horarios[d].horaIni && padre.id>horarios[d].horaFin){
            x[i].textContent = "";
            x[i].classList.remove("normal");
            x[i].classList.add("fpasada");
        }else{
            // Create an <input> element, set its type and name attributes
            var input = document.createElement("input");
            input.type = "hidden";
            input.setAttribute("class","validos");
            input.setAttribute("form","modtarifas");
            input.id = x[i].id;
            input.name = x[i].id;
            input.required = true;
            container.appendChild(input);
        };
    };
}
// FUNCION Bloquear horas
function bloqueodias2(horarios){
    var x = document.getElementsByClassName("horario");// Tomamos las casillas del horario a modificar
    for (var i = 0; i < x.length; i++) {       
       var d = (i+1)%7;
       if(x[i].parentNode.id<horarios[d].horaIni && x[i].parentNode.id>horarios[d].horaFin){
            x[i].setAttribute("onclick","");            
            x[i].innerHTML = "";
            x[i].classList.remove("libre","reservado","preapartado","propia");
            x[i].classList.add("fpasada");
       }       
    }
}
function agregartari(objeto){
    objeto.innerHTML="<div class='col-xs-9'><input type='text' class='form-control input-sm' value=''></div>\
                        <a class='enlace guardar' href='#'> <span class='glyphicon glyphicon-ok'></span> </a>\
                        <a class='enlace cancelar' href='#'> <span class='glyphicon glyphicon-remove'></span></a>";
}
/**
 * Funcion que devuelve la fecha actual y la fecha modificada n dias
 * Tiene que recibir el numero de dias en positivo o negativo para sumar o 
 * restar a la fecha actual.
 * Ejemplo:
 *  mostrarFecha(-10) => restara 10 dias a la fecha actual
 *  mostrarFecha(30) => añadira 30 dias a la fecha actual
 */
function mostrarFecha(days){
    milisegundos=parseInt(35*24*60*60*1000);
    fecha=new Date();
    day=fecha.getDate();
    horablo = fecha.getHours()+':'+fecha.getMinutes()+':'+fecha.getSeconds();
    // el mes es devuelto entre 0 y 11
    month=fecha.getMonth()+1;
    year=fecha.getFullYear();
    //Obtenemos los milisegundos desde media noche del 1/1/1970
    tiempo=fecha.getTime();
    //Calculamos los milisegundos sobre la fecha que hay que sumar o restar...
    milisegundos=parseInt(days*24*60*60*1000);
    //Modificamos la fecha actual
    total=fecha.setTime(tiempo+milisegundos);
    day=fecha.getDate();
    month=fecha.getMonth()+1;
    year=fecha.getFullYear();
    s= year+"-"+month+"-"+day+" "+horablo;
    return s;
}
//HABILITAR FECHA MAX DE RESERVA REPETIDA
function habilitar(obj){
    a = document.getElementById("fecham");
    if(obj.checked) {
        a.required = true ;
        a.disabled = false;
    }else{
        a.required = false ;
        a.disabled = true;
        a.value = "";
    }
}
// ELIMINAR FOTO
function eliminarfoto(cod,ruta) {    
    var r = confirm("Esta seguro que desea eliminar esta foto?");
    if (r == true) {
        var pagina="./eliminarfoto.php?idF="+cod+"&rt="+ruta;
        location.href=pagina;
    }
}
function isValidEmail(mail)
{
    return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(mail);
    //isValidEmail('david@davidburgosonline.com');
}