<?php

	include("session.php");

	function ver_listado(){
		include_once 'db_connect.php';
		if ($stmt=$mysqli->prepare("SELECT COUNT(idGauchada) FROM Gauchada WHERE estado = 'activa' AND fechaVencimiento >= CURDATE()")){
			$stmt->execute();    // Ejecuta la consulta preparada.
			$stmt->store_result();
			$stmt->bind_result($idGauchada);
			$stmt->fetch();
			$stmt->close();
			if ($idGauchada == 0){
				echo '<div class="alert alert-warning"><strong>Información: </strong><span>En este momento no hay publicaciones activas</span></div>';
			}
			else{				
				if ($stmt=$mysqli->prepare("SELECT idGauchada, titulo, descripcion, fechaVencimiento, imagen, ciudad, u.nombre, u.apellido, u.idUsuario FROM Gauchada g INNER JOIN Usuario u ON g.idUsuario=u.idUsuario WHERE estado = 'activa' AND fechaVencimiento >= CURDATE()  ORDER BY fechaCreacion")){
					$stmt->execute();    // Ejecuta la consulta preparada.
					$res = $stmt->get_result();
					
					while($row = $res->fetch_assoc())
						$rows[] = $row;

					foreach($rows as $row){
						$idGauchada=$row["idGauchada"];
						$idUsuario=$row["idUsuario"];						
						if ($stmt=$mysqli->prepare("SELECT COUNT(g.idGauchada) as postulantes FROM Postulante p INNER JOIN Gauchada g ON p.idGauchada=g.idGauchada WHERE p.idGauchada = ?")){						
							$stmt->bind_param('i', $idGauchada);
							$stmt->execute();
							$stmt->store_result();
							$stmt->bind_result($postulantes);
							$stmt->fetch();
							if($row["imagen"]){
								$imagen = $row["imagen"];
							}else{
								$imagen = "../img/logo.png";
							}
							 
							echo '<div class="col col-md-12 background-color-grey padding-16 margin-bottom-16">            
		                    		<div class="list-group">
			                        	<h4 class="list-group-item-heading padding-16">'.$row["titulo"].'</h4>
			                       		<div class="list-group-item-text padding-sides-16">
			                            	<div class="row">
			                                	<div class="col col-md-8">
			                                    	<div class="row">Fecha de vencimiento: '.$row["fechaVencimiento"].'</div>
				                                    <div class="row"><div>Postulantes <span class="badge">'.$postulantes.'</span></div></div>
				                                    <div class="row">Publicado por: '.$row["nombre"].''; echo' '.$row["apellido"].'</div>
				                                    <div class="row">Ciudad: '.$row["ciudad"].'</div>
				                                </div>
				                                <div class="col col-md-4 padding-16 padding-top-0">
				                                    <img src="'.$imagen.'" class="size-75 img-fluid" width="100px"/>
				                                </div>
				                            </div>
				                        </div>
				                        <hr class="no-margin">
				                    </div>';
				            $titulo=addslashes($row["titulo"]);
				            $fVencimiento=$row["fechaVencimiento"];
				            $nombre=$row["nombre"];
				            $apellido=$row["apellido"];
				            $ciudad=$row["ciudad"];
				            echo '
				                    <form action="detalle.php" method="POST">
				                    	<button type="button" class="btn btn-default" onclick="return enviar_datos('.$idGauchada.' , '.$row["idGauchada"].');">Ver gauchada</button>
				                    </form>
				                </div>';
				        }
			        }
				}
			}
		}
	}

	function verGauchadasPedidas(){
		include_once 'db_connect.php';
		if ($stmt=$mysqli->prepare("SELECT fechaVencimiento, COUNT() FROM Gauchada g INNER JOIN Postulante p ON p.idGauchada=g.idGauchada WHERE estado = ? AND idUsuario = ?")){
			$estado='activa';
			$idUsuario=4;
			$stmt->bind_param('si', $estado, $idUsuario);
			$stmt->execute();    // Ejecuta la consulta preparada.
			$stmt->store_result();
			$stmt->bind_result($idGauchada);
			$stmt->fetch();
			$stmt->close();
			if ($idGauchada == 0){
				echo '<div class="alert alert-warning"><strong>Información: </strong><span>En este momento no hay gauchadas pedidas</span></div>';
			}
			else{

			}
		}
		echo '
		<div class="panel-group">
    		<div class="panel panel-warning">
		    	<div class="panel-heading">Titulo</div>
		    	<div class="panel-body">Fecha de vencimiento: 03/09/17
		    	<div>Postulantes <span class="badge">7</span></div></div>
		    	<div class="panel-body"><button type="button" class="btn btn-danger">Editar</button>
		    	<button type="button" class="btn btn-default" id="misPostulantes" data-dismiss="modal" onclick="verPostulantes()">Ver postulantes</button></div>
		    </div>
  		</div>';
	}

	function verGauchadasCerradas(){
		echo '
		<div class="panel-group">
    		<div class="panel panel-warning">
		    	<div class="panel-heading">Titulo</div>
		    	<div class="panel-body">
		    		<div class="form-group">
		    			<label for="candidato">Candidato:</label>
		    			<span>Juan</span>	
		    		</div>
			    	<div class="form-group">
	  					<label for="comentario">Comentario:</label>
	  					<textarea class="form-control" rows="5" id="comentario" disabled>Me parecio excelente toda la ayuda que nos brindaste</textarea>
					</div>
					<button type="button" class="btn btn-default" id="btn-calificar" data-dismiss="modal" onclick="calificar()">Calificar</button>
				</div>
		    </div>
  		</div>';
	}

	function hay_resultados($mysqli, $buscar){
		if ($stmt=$mysqli->prepare("SELECT COUNT(p.idPostulante) FROM Gauchada g INNER JOIN Categoria c ON (c.idCategoria = g.idCategoria) INNER JOIN Postulante p ON (p.idGauchada = g.idGauchada) WHERE (g.titulo LIKE ?) OR (g.ciudad LIKE ?) OR (c.titulo LIKE ?)")){
			$stmt->bind_param('sss', $buscar, $buscar, $buscar); 
			$stmt->execute();    // Ejecuta la consulta preparada.
			$stmt->store_result();
			$stmt->bind_result($cantPostulantes);
			$stmt->fetch();
			$stmt->close();
			if ($cantPostulantes == 0)
				return false;
			else
				return true;
		}
	}

	function verResultados($valor){

		include_once '../includes/db_connect.php';		

		if (!hay_resultados($mysqli, $valor))
			echo '<div class="well">No se encontraron resultados para la búsqueda realizada de:  <b>'.$valor.'</b></div>';
		else{
			if ($stmt=$mysqli->prepare("SELECT g.idCategoria, g.titulo, g.descripcion, g.fechaVencimiento, g.imagen, g.ciudad, g.idGauchada, g.idUsuario, g.imagen FROM Gauchada g INNER JOIN Categoria c ON (c.idCategoria = g.idCategoria) WHERE (g.titulo LIKE ?) OR (g.ciudad LIKE ?) OR (c.titulo LIKE ?)")){
				$stmt->bind_param('sss', $valor, $valor, $valor);
				$stmt->execute();    // Ejecuta la consulta preparada.
				$res = $stmt->get_result();
					
				while($row = $res->fetch_assoc())
					$rows[] = $row;

				foreach($rows as $row){
					$idGauchada=$row["idGauchada"];
					$idUsuario=$row["idUsuario"];						
					if ($stmt=$mysqli->prepare("SELECT COUNT(g.idGauchada) as postulantes, u.nombre, u.apellido FROM Postulante p INNER JOIN Gauchada g ON p.idGauchada=g.idGauchada INNER JOIN Usuario u  ON u.idUsuario=g.idUsuario WHERE (p.idGauchada = ?) GROUP BY g.idGauchada")){
						$stmt->bind_param('i', $idGauchada);
						$stmt->execute();
						$stmt->store_result();
						$stmt->bind_result($postulantes, $nombre, $apellido);
						$stmt->fetch();
						if($row["imagen"]) $imagen = $row["imagen"];
						else $imagen = "../img/logo.png";
						$dueño=$nombre . " " . $apellido;
						echo '<div class="col col-md-6 background-color-grey">
								<div class="panel-group margin-top-20">
						    		<div class="panel panel-warning">
								    	<div class="panel-heading">'.$row["titulo"].'</div>
								    	<div class="row">
											<div class="col col-md-8">
										    	<div class="panel-body">Fecha de vencimiento: '.$row["fechaVencimiento"].'
										    	<div>Postulantes <span class="badge">'.$postulantes.'</span>
										    	<div>Publicado por: '.$dueño.'
										    	<div>Ciudad: '.$row["ciudad"].'</div></div></div></div>
								    		</div>
								    		<div class="col col-md-4 margin-top-20">
												<img src="'.$imagen.'" class="size-75 img-fluid" width="100px"/>
											</div>
								    	</div>
								    	<div class="panel-body">
								    		<button type="button" class="btn btn-danger">Ver gauchada</button>
								    	</div>
								    </div>
						  		</div>
						  	</div>';
				    }
			    }
			}
		}
	}

	function usuario_logueado(){
		Session::init();
		$email=Session::get("email");
		echo '<li><a class="no-link"><b>'.$email.'</b></a></li>';
	}

	function ver_opcion_usuario(){
		Session::init();
		$estado=Session::get("conectado");
		if ($estado == 1){
			Session::destroy();
			echo '<li><a class="link" onclick="cerrar_sesion()">Cerrar sesión</a></li>';
		}
		else
			echo '<li><a href="login.html" class="link">Login</a></li>';
	}