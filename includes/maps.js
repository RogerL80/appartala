  //VARIABLES GENERALES
var map;
//declaras fuera del initialize
var nuevos_marcadores = [];
//variables de geocoder
var geocoder;
/*rutas*/
var directionsDisplay;
var directionsService = new google.maps.DirectionsService();
var map;
var inicio = new google.maps.LatLng();
var destino = new google.maps.LatLng();
/*rutas*/
//FUNCION PARA QUITAR MARCADORES DE MAPA
function limpiar_marcadores(lista){
  for(i in lista)
  {
      //QUITAR MARCADOR DEL MAPA
      lista[i].setMap(null);
  }
}
// Note: This example requires that you consent to location sharing when
// prompted by your browser. If you see a blank space instead of the map, this
// is probably because you have denied permission for location sharing.
function initialize() {
  geocoder = new google.maps.Geocoder();
  var image = {
          url: 'server/pin.png',
          size: new google.maps.Size(38, 38),
        };

  var mapOptions = {
      zoom: 17,
      mapTypeId: google.maps.MapTypeId.HYBRID
    };
    map = new google.maps.Map(document.getElementById('map-canvas'),
        mapOptions);
  if (document.getElementById("lat").value!="") {
    var pos = new google.maps.LatLng(document.getElementById("lat").value, document.getElementById("lng").value);
    var marcador = new google.maps.Marker({
          position: pos,
          map: map,
          icon:image,
          animation:google.maps.Animation.DROP,
          draggable:false
        });
      nuevos_marcadores.push(marcador);

        map.setCenter(pos);

  }else{ 
    // Try HTML5 geolocation
    if(navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        var pos = new google.maps.LatLng(position.coords.latitude,
                                         position.coords.longitude);

        var infowindow = new google.maps.InfoWindow({
          map: map,
          position: pos,
          content: 'AQUI ESTAS.'
        });

        map.setCenter(pos);
      }, function() {
        handleNoGeolocation(true);
      });
    } else {
      // Browser doesn't support Geolocation
      handleNoGeolocation(false);
    }
  }
     // This event listener when the map is clicked.
    google.maps.event.addListener(map, 'click', function(event) {
      
    var location = event.latLng;
    var coordenadas = event.latLng.toString();
    coordenadas = coordenadas.replace("(", "");
    coordenadas = coordenadas.replace(")", "");
    var lista = coordenadas.split(",");
    
    //PASAR LA INFORMACIÓN AL FORMULARIO
    var latitud = document.getElementById("lat");
    var longitud = document.getElementById("lng");

    latitud.value = lista[0];
    longitud.value = lista[1];
    
    codeLatLng(location);

       var marcador = new google.maps.Marker({
        position: location,
        map: map,
        icon: image,
        animation:google.maps.Animation.BOUNCE,
        draggable:false
      });
    //ALMACENAR UN MARCADOR EN EL ARRAY nuevos_marcadores
    nuevos_marcadores.push(marcador);

    //BORRAR MARCADORES NUEVOS
    limpiar_marcadores(nuevos_marcadores);
    marcador.setMap(map);
    });
}

function handleNoGeolocation(errorFlag) {
  if (errorFlag) {
    var content = 'Error: El servicio de Geolocalizacion fallo.';
  } else {
    var content = 'Error: Tu navegador no soporta Geolocalizacion.';
  }

  var options = {
    map: map,
    position: new google.maps.LatLng(60, 105),
    content: content
  };

  var infowindow = new google.maps.InfoWindow(options);
  map.setCenter(options.position);
}

function codeLatLng(latlng) {
  geocoder.geocode({'latLng': latlng}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
       //console.log(results); identificar COD de Pais
        if (results[1]) {
          var indice=0;
          for (var j=0; j<results.length; j++)
          {
              if (results[j].types[0]=='locality')
                  {
                      indice=j;
                      break;
                  }
              }
          //alert('The good number is: '+j);
          console.log(results[j]);
          for (var i=0; i<results[j].address_components.length; i++)
              {
                  if (results[j].address_components[i].types[0] == "locality") {
                          //this is the object you are looking for
                          city = results[j].address_components[i];
                      }
                  if (results[j].address_components[i].types[0] == "administrative_area_level_1") {
                          //this is the object you are looking for
                          region = results[j].address_components[i];
                      }
                  if (results[j].address_components[i].types[0] == "country") {
                          //this is the object you are looking for
                          country = results[j].address_components[i];
                      }
              }

              //city data
              var codpais = document.getElementById("codpais");
              codpais.value = country.short_name;
              var ciudad = document.getElementById("ciudad");
              ciudad.value = city.short_name;
              var estado = document.getElementById("estado");
              estado.value = region.short_name;
              if (document.getElementById("city")) {
                document.getElementById("city").value = ciudad.value +" - "+ estado.value; 
              };
              
        }
      if (results[0]) {
            
        var cade = results[0].formatted_address;
        var res = cade.split(",");
        var n = res.length;
        var pais = document.getElementById("pais");
        pais.value = res[n-1];
        res.pop();
        var direccion = document.getElementById("dir");
        direccion.value = res.join();
    
      } else {
         alert('No se encontraron resultados');
      }
    } else {
       alert('Geocoder failed due to: ' + status);
    }
  });
}

function bindInfoWindow(marker, map, infoWindow, html) {
  google.maps.event.addListener(marker, 'click', function() {
    if(!infoWindow){
            infoWindow = new google.maps.InfoWindow();
        }
    //ruta
    destino = marker.position;
    //ruta
    infoWindow.setContent(html);
    infoWindow.open(map, marker);

  });
}

//rutas
function calcRoute() {
  var start = inicio;
  var end = destino;
  var request = {
      origin:start,
      destination:end,
      travelMode: google.maps.TravelMode.DRIVING
  };
  directionsService.route(request, function(response, status){
    if (status == google.maps.DirectionsStatus.OK) {
      directionsDisplay.setDirections(response);
    }
  });
}
//rutas