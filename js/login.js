function validar_email(login_form){
	var email = login_form.email.value;
	var atpos = email.indexOf("@");
	var dotpos = email.lastIndexOf(".");	
    if (atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length) {    	
    	$("#error-format-email").html('<div class="alert alert-danger"><strong>¡Error! </strong><span>El formato de email es incorrecto</span></div>');
    	$('#btn-login').attr('disabled', true);
        return false;
    }
    else{
    	$('#btn-login').attr('disabled', false);
    	return true;
    }
}

function volver_home(){
	$("#login-form").hide();
	setTimeout('window.location.href = "../html/home.php"; ', 0);
}

$('document').ready(function(){

    $("#login-form").validate({
    	submitHandler: submitForm
    });
    
    function submitForm(){  
		var data = $("#login-form").serialize();    	
    	
		$.ajax({		
			type : 'POST',
			url  : '../process/process_login.php',
			data : data,
			beforeSend: function() { 
				$("#error-login").fadeOut();
				$("#error-format-email").fadeOut();
			},
			success: function(response) {
				if(response=="Log in exitoso"){
					$("#error-login").fadeIn(1000, function(){						
						setTimeout(' window.location.href = "../html/micuenta.html"; ',0);
						return true;
					});
				}
				else{
					$("#error-login").fadeIn(1000, function(){
						$("#error-login").html('<div class="alert alert-danger"><strong>¡Error! </strong><span>Verifique los datos ingresados</span></div>');
						return false;
					});
				}
			}
		});
		return false;
	}
});