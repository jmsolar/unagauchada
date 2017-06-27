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
				                    <form action="detalle.php?id='.$row['idGauchada'].'" method="POST">
				                    	<button type="submit" class="btn btn-default">Ver gauchada</button>
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
		    	<div class="panel-body">
		    		<button type="button" class="btn btn-danger">Editar</button>
		    		<button type="button" class="btn btn-default" id="misPostulantes" data-dismiss="modal" onclick="verPostulantes()">Ver postulantes</button>
		    	</div>
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

	function crear_consulta($mysqli, $buscar, $categoria, $ciudad){
		include_once '../includes/db_connect.php';
		
		//CON FILTROS
		if (($categoria !== 0) AND ($ciudad !== 0)){
			return $mysqli->prepare("SELECT g.idGauchada, g.idUsuario, COUNT(g.idGauchada) AS cantidad FROM Gauchada g INNER JOIN Categoria c ON (c.idCategoria=g.idCategoria) WHERE (g.titulo LIKE '%$buscar%') AND (c.titulo LIKE '$categoria') AND (g.ciudad LIKE '$ciudad') AND (g.fechaVencimiento >= CURDATE())");
		}
		else{
			//FILTRO CATEGORIA
			if (($categoria !== 0) AND ($ciudad == 0)){
				return $mysqli->prepare("SELECT g.idGauchada, g.idUsuario, COUNT(g.idGauchada) AS cantidad FROM Gauchada g INNER JOIN Categoria c ON (c.idCategoria=g.idCategoria) WHERE (g.titulo LIKE '%$buscar%') AND (c.titulo LIKE '$categoria') AND (g.fechaVencimiento >= CURDATE())");
			}
			else{
				//FILTRO CIUDAD
				if (($ciudad !== 0) AND ($categoria == 0)){
					return $mysqli->prepare("SELECT g.idGauchada, g.idUsuario, COUNT(g.idGauchada) AS cantidad FROM Gauchada g INNER JOIN Categoria c ON (c.idCategoria=g.idCategoria) WHERE (g.titulo LIKE '%$buscar%') AND(ciudad LIKE '$ciudad') AND (fechaVencimiento >= CURDATE())");
				}
				else{
					//SIN FILTROS
					if (($categoria == 0) AND ($ciudad == 0)){
						return $mysqli->prepare("SELECT g.idGauchada, g.idUsuario, COUNT(g.idGauchada) AS cantidad FROM Gauchada g WHERE (g.titulo LIKE '%$buscar%') AND (g.fechaVencimiento >= CURDATE())");
					}
				}
			}
		}
	}

	function hay_resultados($mysqli, $buscar){
		if (!trim($buscar)){
			echo '<div class="well">No se ingreso ningún dato para la búsqueda</div>';
			return false;
		}
		if ($stmt=$mysqli->prepare("SELECT COUNT(idGauchada) AS gauchadas FROM Gauchada WHERE (titulo LIKE '%$buscar%') AND (fechaVencimiento >= CURDATE())")){
			$stmt->execute();    // Ejecuta la consulta preparada.
			$stmt->store_result();
			$stmt->bind_result($gauchadas);
			$stmt->fetch();
			$stmt->close();
			if ($gauchadas != 0)
				return true;
			else
				return false;
		}
	}

	function mostrar_filtros($categoria, $ciudad){
		if ($categoria !== 0)
			echo '<div class="well">Filtro de categoría:  <b>'.$categoria.'</b></div>';
		else
			echo '<div class="well">Filtro de categoría:  <b>-</b></div>';
		if ($ciudad !== 0)
			echo '<div class="well">Filtro de ciudad:  <b>'.$ciudad.'</b></div>';
		else
			echo '<div class="well">Filtro de ciudad:  <b>-</b></div>';
	}

	function verResultados($buscar,$categoria,$ciudad){
		include_once '../includes/db_connect.php';
		if (!trim($buscar))
			echo '<div class="well">No se ingreso ningún dato para la búsqueda</div>';
		else{
			if ($stmt=crear_consulta($mysqli, $buscar, $categoria, $ciudad)){
				$stmt->execute();    // Ejecuta la consulta preparada.
				$res = $stmt->get_result();
				while($row = $res->fetch_assoc())
					$rows[] = $row;
				
				foreach($rows as $row){
					$cantidad=$row["cantidad"]; 
					if ($cantidad == 0){
						echo '<div class="well">No se encontraron resultados para la búsqueda realizada de:  <b>'.$buscar.'</b></div>';
						mostrar_filtros($categoria, $ciudad);
					}
					else{
						echo '<div class="well">Se encontraron los siguientes resultados para la búsqueda realizada de:  <b>'.$buscar.'</b></div>';
						mostrar_filtros($categoria, $ciudad);
						$idGauchada=$row["idGauchada"];
						$idUsuario=$row["idUsuario"];						
						if ($stmt=$mysqli->prepare("SELECT COUNT(g.idGauchada) as postulantes, u.nombre, u.apellido, g.fechaVencimiento, g.imagen, g.titulo, g.ciudad FROM Postulante p INNER JOIN Gauchada g ON p.idGauchada=g.idGauchada INNER JOIN Usuario u  ON u.idUsuario=g.idUsuario WHERE (p.idGauchada = $idGauchada) GROUP BY g.idGauchada ORDER BY g.fechaCreacion")){
							$stmt->execute();
							$stmt->store_result();
							$stmt->bind_result($postulantes, $nombre, $apellido, $fechaVencimiento, $imagen, $titulo, $ciudad);
							$stmt->fetch();
							$dueño=$nombre . " " . $apellido;
							echo '<div class="col col-md-6 background-color-grey margin-top-20">
									<div class="panel-group margin-top-20">
							    		<div class="panel panel-warning">
									    	<div class="panel-heading">'.$titulo.'</div>
									    	<div class="row">
												<div class="col col-md-8">
											    	<div class="panel-body">Fecha de vencimiento: '.$fechaVencimiento.'
											    	<div>Postulantes <span class="badge">'.$postulantes.'</span>
											    	<div>Publicado por: '.$dueño.'</div></div></div>
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
				if ($cantidad !== 0){
					echo '
					<div class="row margin-top-20">
                        <div class="col col-md-12 margin-top-20">
                            <form id="busqueda-form" method="GET" name="buscar_form" action="../html/busqueda.php">
                                <div class="row">
                                    <div class="col col-md-5">
                                        <input type="text" class="form-control" id="buscar" name="busqueda" placeholder="Buscar..">
                                    </div>
                                    <div class="col col-md-7">
                                        <input type="submit" class="btn btn-success" id="botonBuscar" value="Buscar">
                                    </div>
                                </div>
                                <div class="row margin-top-20">
                                    <div class="col col-md-12">
                                        <h4>Filtros</h4>
                                        <div class="row">
                                            <div class="col col-md-12">
                                                <div class="row">
                                                    <div class="col col-md-12">
                                                        <div class="form-group">
                                                            <label for="cate">Categoría:</label>
                                                            <div class="row">
                                                                <div class="col col-md-5">';
                                                                    mostrar_categorias();
                                                                echo'</div>
                                                                <div class="col col-md-7">
                                                                    <input type="checkbox" id="cate" name="checkboxCategoria" value="categoria"/>
                                                                    <p class="text-danger">* habilita el check para filtrar por categoría</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row margin-top-20">
                                            <div class="col col-md-12">
                                                <div class="row">
                                                    <div class="col col-md-12">
                                                        <div class="form-group">
                                                            <label for="ciu">Ciudad:</label>
                                                            <div class="row">
                                                                <div class="col col-md-5">';
                                                                    mostrar_ciudades();
                                                                echo '</div>
                                                                <div class="col col-md-7">
                                                                    <input type="checkbox" id="ciud" name="checkboxCiudad" value="ciudad"/>
                                                                    <p class="text-danger">* habilita el check para filtrar por ciudad</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                    
                                </div>
                            </form>
                        </div>
                    </div>';
                }
			}
		}
	}

	function mostrar_categorias(){
		include('../includes/db_connect.php');
		echo'<select class="form-control" id="cate" name="filtrarCategoria" disabled="disabled">';		
		$stmt=$mysqli->prepare("SELECT titulo FROM Categoria");
				$stmt->execute();    // Ejecuta la consulta preparada.
				$res = $stmt->get_result();
				while($row = $res->fetch_assoc())
					echo '<option>'.$row["titulo"].'</option>';
		echo'</select>';
	}

	function mostrar_ciudades(){
		include('../includes/db_connect.php');
		echo'<select class="form-control" id="ciu" name="filtrarCiudad" disabled="disabled">';		
		$stmt=$mysqli->prepare("SELECT ciudad FROM Gauchada");
				$stmt->execute();    // Ejecuta la consulta preparada.
				$res = $stmt->get_result();
				while($row = $res->fetch_assoc())
					echo '<option>'.$row["ciudad"].'</option>';
		echo'</select>';
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
			echo '<li><a class="link" onclick="cerrar_sesion()">Cerrar sesión</a></li>';
		}
		else
			echo '<li><a href="login.html" class="link">Login</a></li>';
	}