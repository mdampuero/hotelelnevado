// JavaScript Document

//Elimina un div
function eliminar_div(id){
	///alert(id);
	var o = document.getElementById(id);
	o.parentNode.removeChild(o);
}

//OCULTAR O HACER VISIBLE UN ID
function ov(id){
	var o = document.getElementById(id);
	if (o.className=="visible"){
		o.className="oculto";	
	}else{
		o.className="visible";
	}
}

//******************************************
//FUNCION AJAX
//******************************************
function Ajax(){
	var xmlhttp=false;
	try{
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	}catch(e){
		try {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}catch(E){
			xmlhttp = false;
		}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}

//******************************************
//HORA EN UNIX, UTIL PARA SALTEAR CACHE DE IE
//******************************************
fetch_unix_timestamp = function(){
	return parseInt(new Date().getTime().toString().substring(0, 10))
}

//******************************************
//REFRESCA UN DIN, RECIBE EL ID DEL DIV Y 
//EL ARCHIVO QUE ACTUALIZA Y EL TERCER PARAMETRO
//ES OPCIONAL QUE ES UN STRING CON PARAMETROS GET
//******************************************
function refrescar(div,archivo){
	var opciones = arguments[2] || {};
	var argumentos = opciones.argumentos || "";
 	divListado = document.getElementById(div);
 	ajax=Ajax();
 	ajax.open("GET", archivo+"?"+argumentos);
 	ajax.onreadystatechange=function() {
  		if (ajax.readyState==4) {
   			divListado.innerHTML = ajax.responseText
  		}
 	}
 	ajax.send(null);
}



        function abrir (url,alto,ancho,alineacion){ 
            
		if (alineacion == "centro"){
			altoPantalla = parseInt(screen.availHeight);
			anchoPantalla = parseInt(screen.availWidth);
			//Calculo en punto medio
			centroAncho = parseInt((anchoPantalla/2))
			centroAlto = parseInt((altoPantalla/2))
			// ubico la venta segun sus dimensiones
			laXPopup = centroAncho - parseInt((ancho/2))
			laYPopup = centroAlto - parseInt((alto/2))
		}else if (alineacion == "esquina"){
			laXPopup = 0
			laYPopup = 0
		}
		window.open(url,'','location=no, left ='+ laXPopup +',top='+ laYPopup +', menubar=no, scrollbars=yes, status=no, toolbar=no, resizable=yes, height='+alto+', width='+ancho+'') ;
	}
	
	
	
	function restar(date1,date2)
	{
	if (date1.indexOf("-") != -1) { date1 = date1.split("-"); } else if (date1.indexOf("/") != -1) { date1 = date1.split("/"); } else { return 0; }
	   if (date2.indexOf("-") != -1) { date2 = date2.split("-"); } else if (date2.indexOf("/") != -1) { date2 = date2.split("/"); } else { return 0; }
	   if (parseInt(date1[0], 10) >= 1000) {
		   var sDate = new Date(date1[0]+"/"+date1[1]+"/"+date1[2]);
	   } else if (parseInt(date1[2], 10) >= 1000) {
		   var sDate = new Date(date1[2]+"/"+date1[0]+"/"+date1[1]);
	   } else {
		   return 0;
	   }
	   if (parseInt(date2[0], 10) >= 1000) {
		   var eDate = new Date(date2[0]+"/"+date2[1]+"/"+date2[2]);
	   } else if (parseInt(date2[2], 10) >= 1000) {
		   var eDate = new Date(date2[2]+"/"+date2[0]+"/"+date2[1]);
	   } else {
		   return 0;
	   }
	   var one_day = 1000*60*60*24;
	   var daysApart = Math.abs(Math.ceil((sDate.getTime()-eDate.getTime())/one_day));
   		return daysApart;
	 //alert( daysApart);
	}