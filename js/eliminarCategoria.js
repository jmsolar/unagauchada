var valor=""

function limpiar_campos_eliminar(){
	$('#eliminar-categoria-form').trigger("reset");
	$("#error-eliminar-categoria").fadeOut();
	var elem = $('.list-group-item.active');
    elem.removeClass('active');
    $('#botonEliminar').attr('disabled', true);
}

$('.list-group-item').on('click', function() {
    var $this = $(this);
    var $alias = $this.data('alias');

    $('.active').removeClass('active');
    $this.toggleClass('active');

 	valor=$this.text();
 	$('#botonEliminar').attr('disabled', false);
})

function deshabilitarCampos(){
	$('#botonEliminar').attr('disabled', true);
}

$('document').ready(function(){

	deshabilitarCampos();

    $("#eliminar-categoria-form").validate({
		submitHandler: submitForm 
    });
    
    function submitForm(){
		data = "nombre=" + valor; 
		$.ajax({		
			type : 'POST',
			url  : '../process/process_eliminar_categoria.php',
			data : data,
			beforeSend: function(response){
				deshabilitarCampos();
			},
			success: function(response) {
				if(response=="Categoria eliminada"){
					$("#error-eliminar-categoria").fadeIn(1000, function(){
						$("#error-eliminar-categoria").html('<div class="alert alert-success"><strong>¡Exito! </strong><span>La categoria fue eliminada correctamente</span></div>');
						deshabilitarCampos();
						$('#botonCancelarCategoriaEliminar').attr('disabled', true);
						setTimeout(function(){
							location.reload();
						},4000);
					});
				}
				else{
					if(response=="Categoria existente"){
						$("#error-eliminar-categoria").fadeIn(1000, function(){	
							$("#error-eliminar-categoria").html('<div class="alert alert-danger"><strong>¡Error! </strong><span>La categoria seleccionada esta asignada a una(s) gauchada(s)</span></div>');							
						});
					}
				}
			}
		});
		return false;
	}
});