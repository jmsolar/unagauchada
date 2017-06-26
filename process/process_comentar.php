<?php
include_once '../includes/db_connect.php';
include_once '../includes/session.php';
Session::init();

$comment = $mysqli->real_escape_string($_POST['comment']);
$gauchada = $mysqli->real_escape_string($_POST['gauchada']);
$usuario = idUsuario(Session::get('email'), $mysqli);

if (!$comment || !$gauchada || $usuario === -1) {
	header('HTTP/1.1 500 Internal Server');
	echo "No se pudo agregar el comentario";
}

else if ($stmt=$mysqli->prepare("INSERT INTO comentario(`idGauchada`, `idUsuario`, `textoComentario`, `fechaComentario`) VALUES($gauchada, $usuario, '".$comment."', NOW())")) {
	$stmt->execute();    // Ejecuta la consulta preparada.
	$stmt->store_result();
	$stmt->close();
	echo "Comentario agregado con exito";
}
exit();

function idUsuario($email, $mysqli){
	if ($stmt=$mysqli->prepare("SELECT idUsuario FROM Usuario WHERE email = ? LIMIT 1")) {
		$stmt->bind_param('s', $email);  // Une $email al parámetro.
		$stmt->execute();    // Ejecuta la consulta preparada.
		$stmt->store_result();
		$stmt->bind_result($idUsuario);
		$stmt->fetch();
		$stmt->close();
		return $idUsuario;			
	} else 
	return -1;
}

?>