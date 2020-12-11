
function autoSlide(absoluteId, contadorSliderId){
     var target = document.getElementById(absoluteId);
     targetContador = document.getElementById(contadorSliderId);
     intervalo = setInterval(function(){         
     $(".bandera").fadeTo(100, 0.5);
     switch(target.style.left){
        case '-715px':
            $("#circulo_3").fadeTo(100, 1);
            target.style.left = '-1430px';
            targetContador.innerHTML = '3/4';
            break;
        case '-1430px':
            $("#circulo_4").fadeTo(100, 1);
            target.style.left = '-2145px';
            targetContador.innerHTML = '4/4';
            break;
        case '-2145px':
            $("#circulo_1").fadeTo(100, 1);
            target.style.left = '0px';
            targetContador.innerHTML = '1/4';
            break;
        default:
            $("#circulo_2").fadeTo(100, 1);
            target.style.left = '-715px';
            targetContador.innerHTML = '2/4';
            break;
        }
    }, 10000);
}

function slideDiv(absoluteId, pixeles, idCirculo){
    clearInterval(intervalo);
    var target = document.getElementById(absoluteId);
    //target.style.webkitAnimation = 'none';
    //target.style.MozAnimation = 'none';
    target.style.left = pixeles;
    //var targetContador = document.getElementById(contadorSlider);
    //targetContador.innerHTML =  numero;
    $(".bandera").fadeTo(100, 0.6, function(){		
            targetCirculo = document.getElementById(idCirculo);
            targetCirculo.style.opacity = 1;
            });
	autoSlide(absoluteId);
}        
