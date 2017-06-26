<?php
include_once '../includes/db_connect.php';
include_once '../includes/session.php';
Session::init();

$textoRespuesta = $mysqli->real_escape_string($_POST['respuesta']);
$comentario = $mysqli->real_escape_string($_POST['comentario']);

if (!$textoRespuesta || !$comentario) {
	header('HTTP/1.1 500 Internal Server');
	echo "No se pudo agregar la respuesta";
}

else if ($stmt=$mysqli->prepare("UPDATE comentario SET textoRespuesta = '".$textoRespuesta."', fechaRespuesta = NOW() WHERE idComentario = $comentario")) {
	$stmt->execute();    // Ejecuta la consulta preparada.
	$stmt->store_result();
	$stmt->close();
	echo "Respuesta agregada con exito ";
}
exit();

?>