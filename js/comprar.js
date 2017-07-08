function limpiar_campos(){
	$('#comprar-form').trigger("reset");
}

function validar_credito(e){
	var credito = comprar_form.credito.value;
	if (credito <= 0){
    	$("#error-format-comprar").html('<div class="alert alert-danger"><strong>¡Error! </strong><span>El valor ingresado en cantidad de creditos no es válido</span></div>');
    	return false;
	}
	else
		return true;
}

function validar_tarjeta(e){
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla==8)//Tecla de retroceso para borrar, siempre la permite
        return true;        
    patron =/[0-9]/;// Patron de entrada, en este caso solo acepta numeros
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}

function validar_longitud(comprar_form){
	var tarjeta = comprar_form.tarjeta.value;
	var longitud = tarjeta.length;	
    if (longitud < 16){
    	$("#error-format-comprar").html('<div class="alert alert-danger"><strong>¡Error! </strong><span>La longitud de la tarjeta de crédito deben ser 16 carácteres</span></div>');
    	return false;
    }
    else
    	return true;
}

function verificar_creditos(){
    $.ajax({
        type:'POST',
        url: '../process/process_creditos.php',
        data: {},
        success: function(response){
        	if (response == "No tiene creditos"){
        		$("#info-comprar").fadeIn(1000, function(){	
        			$("#info-comprar").html('<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">×</a><strong>¡Error! </strong><span>No dispone de créditos para poder publicar</span></div>');
        		});
        		return false;
        	}
        	else{
        		$("#info-comprar").html("");
        		$("#publicar").modal("show");
        		return false;
        	}
    	}
     });
}

$('document').ready(function(){
	$("#publicar").modal("hide"); 

    $("#comprar-form").validate({
		submitHandler: submitForm 
    });
    
    function submitForm(){  
		var data = $("#comprar-form").serialize();
		    	
		$.ajax({		
			type : 'POST',
			url  : '../process/process_comprar.php',
			data : data,
			beforeSend: function() { 
				$("#error-comprar").fadeOut();
				$("#error-format-comprar").fadeOut();
			},
			success: function(response) {
				if(response=="Compra exitosa"){
					$("#error-comprar").fadeIn(1000, function(){
						$("#error-comprar").html('<div class="alert alert-success"><strong>¡Exito! </strong><span>La compra de crédito(s) fue realizada correctamente</span></div>');
						$('#btn-cancelar-compra').attr('disabled', true);
						$('#btn-comprar').attr('disabled', true);
						setTimeout(' window.location.href = "../html/micuenta.php"; ',4000);
					});
				}
				else{
					if(response=="No tiene saldo suficiente"){
						$("#error-comprar").fadeIn(1000, function(){	
							$("#error-comprar").html('<div class="alert alert-danger"><strong>¡Error! </strong><span>No dispone de saldo suficiente para realizar esta operación</span></div>');
						});
					}
					else{
						if(response=="Verifique los datos ingresados"){
							$("#error-comprar").fadeIn(1000, function(){
								$("#error-comprar").html('<div class="alert alert-danger"><strong>¡Error! </strong><span>Verifique los datos ingresados</span></div>');
							});
						}						
					}
				}
			}
		});
		return false;
	}
});