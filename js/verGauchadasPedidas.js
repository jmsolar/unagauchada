function verPostulantes(){
	$("#verPostulantes").modal("show");
}

function cerrarPostulantes(){
	$("#verPostulantes").modal("hide");
	$("#verGauchadasPedidas").modal("show");	
}

function calificar(){
	$("#calificar").modal("show");
}

$('document').ready(function(){

    /*$("#ver-gauchadas-pedidas-form").validate({
    	submitHandler: submitForm
    });
    
    function submitForm(){  
		var data = $("#ver-gauchadas-pedidas-form").serialize();    	
    	
		$.ajax({		
			type : 'POST',
			url  : '../process/ver-gauchadas-pedidas-form.php',
			data : data
		});
	}*/
});