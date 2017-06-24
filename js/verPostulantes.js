$('document').ready(function(){

    $("#ver-postulantes-form").validate({
    	submitHandler: submitForm
    });
    
    function submitForm(){  
		var data = $("#ver-postulantes-form").serialize();    	
    	
		$.ajax({		
			type : 'POST',
			url  : '../process/ver_postulantes.php',
			data : data
		});
	}
});