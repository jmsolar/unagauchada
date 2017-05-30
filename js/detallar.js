function enviar_datos(titulo, descripcion, fVencimiento, nombre, apellido, ciudad, postulantes){
	location.href="detalle.php?titulo=" +titulo+ "desc=" +descripcion+ "fvenc=" +fVencimiento+ "nombre=" +nombre+ "apellido=" +apellido+ "ciudad=" +ciudad+ "postulantes=" +postulantes;
}

$('document').ready(function(){

    $("#detalle-form").validate({
    	submitHandler: submitForm
    });
    
    function submitForm(){  
		var data = $("#detalle-form").serialize();    	
    	
		$.ajax({		
			type : 'POST',
			url  : '',
			data : data
		});
		setTimeout(' window.location.href = "../html/detalle.php"; ',0);
	}
});