var valor=""

function limpiar_campos_editar(){
	$('#editar-categoria-form').trigger("reset");
	$("#error-editar-categoria").fadeOut();
	var elem = $('.list-group-item.active');
    elem.removeClass('active');
    $('#botonAceptar').attr('disabled', true);
}

$('.list-group-item').on('click', function() {
    var $this = $(this);
    var $alias = $this.data('alias');

    $('.active').removeClass('active');
    $this.toggleClass('active');

 	valor=$this.text();
 	$('#botonAceptar').attr('disabled', false);
 	$('#nombreNuevo').attr('disabled', false);
})

function deshabilitarCampos(){
	$('#botonAceptar').attr('disabled', true);
	$('#nombreNuevo').attr('disabled', true);
}

$('document').ready(function(){
	deshabilitarCampos();

    $("#editar-categoria-form").validate({
		submitHandler: submitForm 
    });
    
    function submitForm(){  
		data = $("#editar-categoria-form").serialize();
		data = data + "&nombre=" + valor;
		    	
		$.ajax({		
			type : 'POST',
			url  : '../process/process_editar_categoria.php',
			data : data,
			beforeSend: function(response){
				deshabilitarCampos();
			},
			success: function(response) {
				if(response=="Categoria editada"){
					$("#error-editar-categoria").fadeIn(1000, function(){
						$("#error-editar-categoria").html('<div class="alert alert-success"><strong>¡Exito! </strong><span>La categoria fue editada correctamente</span></div>');
						deshabilitarCampos();
						document.getElementById("nombreNuevo").disabled = true;
						document.getElementById("botonAceptar").disabled = true;
						$('#botonCancelarCategoriaEditar').attr('disabled', true);
						setTimeout(function(){
							location.reload();
						},4000);
					});
				}
				else{
					if(response=="Categoria duplicada"){
						$("#error-editar-categoria").fadeIn(1000, function(){	
							$("#error-editar-categoria").html('<div class="alert alert-danger"><strong>¡Error! </strong><span>La categoria ingresada ya existe</span></div>');
							$('#botonAceptar').attr('disabled', false);
						});
					}
					else{
						if(response=="Campo vacio"){
							$("#error-editar-categoria").fadeIn(1000, function(){	
								$("#error-editar-categoria").html('<div class="alert alert-danger"><strong>¡Error! </strong><span>Debe completar el campo nuevo nombre</span></div>');
								$('#botonAceptar').attr('disabled', false);
							});
						}
					}
				}
			}
		});
		return false;
	}
});