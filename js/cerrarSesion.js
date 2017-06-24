function cerrar_sesion(){  

	$.ajax({		
		type : 'POST',
		url  : '../process/process_cerrar_sesion.php',
		data : {},
        success: function(response){
            if (response == "OK"){
    			setTimeout(' window.location.href = "../html/home.php"; ',0); 
    			return true;      	
            }
        },
	});
	return false;
}