$('document').ready(function(){ 
    $("#publicar-form").validate({
		submitHandler: submitForm 
    });
    
    /* login submit */
    function submitForm(){  
		var data = $("#publicar-form").serialize();
		    	
		$.ajax({		
			type : 'POST',
			url  : '../process/process_publicar.php',
			data : data,
			beforeSend: function() { 
				$("#error-publicar").fadeOut();
			},
			success: function(response) {
				if(response=="Publicacion exitosa"){
					$("#error-publicar").fadeIn(1000, function(){
						$("#error-publicar").html('<div class="alert alert-success"><strong>¡Exito! </strong><span>La publicación fue creada, espere unos minutos para poder verla en la lista de sus publicaciones activas</span></div>');
						setTimeout(' window.location.href = "../html/micuenta.html"; ',5000);
					});
				}
				else{
					if(response=="No tiene credito"){
						$("#error-publicar").fadeIn(1000, function(){	
							$("#error-publicar").html('<div class="alert alert-danger"><strong>¡Error! </strong><span>Debe tener un crédito para poder publicar</span></div>');
						});
					}
					else{
						if(response=="Publicacion exitosa"){
							$("#error-publicar").fadeIn(1000, function(){
								$("#error-publicar").html('<div class="alert alert-danger"><strong>¡Error! </strong><span>Verifique los datos ingresados</span></div>');
							});
						}						
					}
				}
			}
		});
		return false;
	}
    /* login submit */
});