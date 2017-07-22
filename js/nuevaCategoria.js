function limpiar_campos_nueva(){
	$('#nueva-categoria-form').trigger("reset");
	$("#error-nueva-categoria").fadeOut();
	var elem = $('.list-group-item.active');
    elem.removeClass('active');
}

function deshabilitarCampos(){
	$('#botonAgregar').attr('disabled', false);
	document.getElementById("nombreAgregar").disabled = true;
}

$('document').ready(function(){
	deshabilitarCampos();
	
    $("#nueva-categoria-form").validate({
		submitHandler: submitForm 
    });
    
    function submitForm(){  
		var data = $("#nueva-categoria-form").serialize();
		    	
		$.ajax({		
			type : 'POST',
			url  : '../process/process_agregar_categoria.php',
			data : data,
			success: function(response) {
				if(response=="Categoria agregada"){
					$("#error-comprar").fadeIn(1000, function(){
						$("#error-nueva-categoria").html('<div class="alert alert-success"><strong>¡Exito! </strong><span>La categoria fue agregada exitosamente</span></div>');
						$('#botonCancelarCategoria').attr('disabled', true);
						$('#botonAgregar').attr('disabled', true);
						setTimeout(function(){
							location.reload();
						},4000);
					});
				}
				else{
					if(response=="Categoria duplicada"){
						$("#error-nueva-categoria").fadeIn(1000, function(){	
							$("#error-nueva-categoria").html('<div class="alert alert-danger"><strong>¡Error! </strong><span>La categoria ingresada ya existe</span></div>');
						});
					}
				}
			}
		});
		return false;
	}
});