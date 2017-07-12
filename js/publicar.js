function limpiar_campos_pub(){
	$('#publicar-form').trigger("reset");
}

$('document').ready(function(){
    $("#publicar-form").validate({
		submitHandler: submitImage
    });
    

     function submitImage(image){

    	
    	if(!$('#foto').val()){
    		submitForm();
    		return;
    	};
    	var formData = new FormData(image);
        $.ajax({
            type:'POST',
            url: '../process/process_upload.php',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success:function(img_path){
            	submitForm(img_path);
                location.reload();
            },
            error: function(data){
                $("#error-register").fadeIn(1000, function(){
					$("#error-register").html('<div class="alert alert-danger"><strong>¡Error! </strong><span>No se pudo subir la imagen</span></div>');
				});
            }
        });

    }

    /* login submit */
    function submitForm(img){  
		var data = $("#publicar-form").serialize();
		data = data+"&image="+img;

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
						$("#botonCancelarPublicar").attr('disabled', true);
						$("#botonPublicar").attr('disabled', true);
						setTimeout(' window.location.href = "../html/micuenta.php"; ',4000);	
					});
				}
				else{
					if(response=="Tiene publicaciones pendientes"){
						$("#error-publicar").fadeIn(1000, function(){
							$("#error-publicar").html('<div class="alert alert-danger"><strong>¡Error! </strong><span>Verifique los datos ingresados</span></div>');
						});
					}
					else{
						if (response=="Sin categoria"){
							$("#error-publicar").fadeIn(1000, function(){
								$("#error-publicar").html('<div class="alert alert-danger"><strong>¡Error! </strong><span>Debe seleccionar una categoria</span></div>');
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