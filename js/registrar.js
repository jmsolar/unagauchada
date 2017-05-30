function limpiar_campos(){
	$('#register-form').trigger("reset");
	$('#btn-registrar').attr('disabled', true);
}

function validar_email(register_form){
	var email = register_form.email.value;
	var atpos = email.indexOf("@");
	var dotpos = email.lastIndexOf(".");	
    if (atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length) {    	
    	$("#error-format-email").html('<div class="alert alert-danger"><strong>¡Error! </strong><span>El formato de email es incorrecto</span></div>');
    	$('#btn-registrar').attr('disabled', true);
        return false;
    }
    else{
    	$('#btn-regitrar').attr('disabled', false);
    	return true;
    }
}

function validar_telefono(e){
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla==8){
        return true;
    }
        
    // Patron de entrada, en este caso solo acepta numeros
    patron =/[0-9]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}


$('document').ready(function(){ 
    $("#register-form").validate({
		submitHandler: submitForm 
    });
    
	function submitForm(){  
		var data = $("#register-form").serialize();
		
		$.ajax({		
			type : 'POST',
			url  : '../process/process_registrar.php',
			data : data,
			beforeSend: function() { 
				$("#error-register").fadeOut();
				$("#error-format-email").fadeOut();
			},
			success: function(response) {
				if(response=="Usuario registrado exitosamente"){
					$("#error-register").fadeIn(1000, function(){
						$("#error-register").html('<div class="alert alert-success"><strong>¡Exito! </strong><span>Para comenzar a utilizar su cuenta inicie sesión</span></div>');										
						setTimeout(' window.location.href = "../html/home.php"; ',4000);
					});
				}
				else{
					if(response=="Las contraseñas son distintas"){
						$("#error-register").fadeIn(1000, function(){					
							$("#error-register").html('<div class="alert alert-danger"><strong>¡Error! </strong><span>Las contraseñas ingresadas deben coincidir</span></div>');
						});
					}
					else{
						if(response=="Email existente"){
							$("#error-register").fadeIn(1000, function(){
								$("#error-register").html('<div class="alert alert-danger"><strong>¡Error! </strong><span>El email ingresado ya existe, intente con otra cuenta</span></div>');
							});
						}			
					}
				}
			}
		});
		return false;
	}
});