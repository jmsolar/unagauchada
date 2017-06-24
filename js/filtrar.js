$('input:checkbox').click(function(){
    var $inputs = $('input:checkbox');
    if($(this).is(':checked')){
    	$inputs.not(this).prop('disabled',true); // <-- deshabilita todos los check
    	$('#botonFiltrar').attr('disabled', false);
    }
    else{
        $inputs.prop('disabled',false);
        $('#botonFiltrar').attr('disabled', true);
    }
})

$('document').ready(function(){ 
    $("#filtrar-form").validate({
		submitHandler: submitForm 
    });
    
    function submitForm(){  
		var data = $("#filtrar-form").serialize();
		var filtro = $('#opcion1').prop('checked');
		if (!filtro)
			var filtro = document.getElementById("opcion2").value;
		else
			var filtro = document.getElementById("opcion1").value;
		$.ajax({		
			type : 'POST',
			url  : '../process/process_filtrar.php',
			data : {data: data, filtro: filtro},
			success: function(response) {
				if(response=="Sin datos"){
					$("#error-filtrar").fadeIn(1000, function(){
						$("#error-filtrar").html('<div class="alert alert-danger"><strong>Error! </strong><span>No se encontraron resultados para el filtro</span></div>');
					});
				}
			}
		});
		return false;
	}
});