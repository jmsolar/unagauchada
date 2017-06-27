$('input[name="checkboxCategoria"]').change(function() {
	$('select[name="filtrarCategoria"]').val("");
	if($('input[name="checkboxCategoria"]').prop('checked'))
    	$('select[name="filtrarCategoria"]').val("General"); //Es el primer dato de la tabla categoria
    $('select[name="filtrarCategoria"]').attr('disabled',!this.checked);
});

$('input[name="checkboxCiudad"]').change(function() {
	$('select[name="filtrarCiudad"]').val("");
	if($('input[name="checkboxCiudad"]').prop('checked'))
		$('select[name="filtrarCiudad"]').val("Viedma"); //Es el primer dato de la tabla gauchada
    $('select[name="filtrarCiudad"]').attr('disabled',!this.checked);
});

$( document ).ready(function() {
	$('select[name="filtrarCategoria"]').val("");
	$('select[name="filtrarCiudad"]').val("");
});