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

	function crear_consulta($mysqli, $buscar, $categoria, $ciudad){
		include_once '../includes/db_connect.php';
		//CON FILTROS Y BUSQUEDA
		if (($categoria !== 0) AND ($ciudad !== 0) AND (!empty($buscar)))
			return $mysqli->prepare("SELECT g.idGauchada FROM Gauchada g INNER JOIN Categoria c ON (c.idCategoria=g.idCategoria) WHERE (g.titulo LIKE '%$buscar%') AND (c.titulo = '$categoria') AND (g.ciudad = '$ciudad') AND (g.fechaVencimiento >= CURDATE())");
		else{
			//CON FILTROS SIN BUSQUEDA
			if (($categoria !== 0) AND ($ciudad !== 0) AND (empty($buscar)))
				return $mysqli->prepare("SELECT g.idGauchada FROM Gauchada g INNER JOIN Categoria c ON (c.idCategoria=g.idCategoria) WHERE (c.titulo = '$categoria') AND (g.ciudad = '$ciudad') AND (g.fechaVencimiento >= CURDATE())");
			else{
				//FILTRO CATEGORIA Y BUSQUEDA
				if (($categoria !== 0) AND ($ciudad == 0) AND (!empty($buscar)))
					return $mysqli->prepare("SELECT g.idGauchada FROM Gauchada g INNER JOIN Categoria c ON (c.idCategoria=g.idCategoria) WHERE (g.titulo LIKE '%$buscar%') AND (c.titulo = '$categoria') AND (g.fechaVencimiento >= CURDATE())");
				else{
					//FILTRO CATEGORIA Y SIN BUSQUEDA
					if (($categoria !== 0) AND ($ciudad == 0) AND (empty($buscar)))
						return $mysqli->prepare("SELECT g.idGauchada FROM Gauchada g INNER JOIN Categoria c ON (c.idCategoria=g.idCategoria) WHERE (c.titulo = '$categoria') AND (g.fechaVencimiento >= CURDATE())");
					else{
						//FILTRO CIUDAD Y BUSQUEDA
						if (($ciudad !== 0) AND ($categoria == 0) AND (!empty($buscar)))
							return $mysqli->prepare("SELECT g.idGauchada FROM Gauchada g INNER JOIN Categoria c ON (c.idCategoria=g.idCategoria) WHERE (g.titulo LIKE '%$buscar%') AND (ciudad = '$ciudad') AND (fechaVencimiento >= CURDATE())");
						else{
							//SIN FILTROS Y BUSQUEDA
							if (($categoria == 0) AND ($ciudad == 0) AND (!empty($buscar)))
								return $mysqli->prepare("SELECT g.idGauchada FROM Gauchada g WHERE (g.titulo LIKE '%$buscar%') AND (g.fechaVencimiento >= CURDATE())");
						}
					}
				}
			}
		}
	}

	function mostrar_busqueda($busqueda, $mysqli){
		if (!trim($busqueda))
			echo '<div class="well">No se ingreso ningún dato para el campo de búsqueda</div>';
		else{
			if($stmt=$mysqli->prepare("SELECT COUNT(idGauchada) AS cantidad FROM Gauchada WHERE (titulo LIKE '%$busqueda%') AND (fechaVencimiento >= CURDATE())")){
				$stmt->execute();
				$stmt->store_result();
				$stmt->bind_result($cantidad);
				$stmt->fetch();
				if ($cantidad !== 0)
					echo '<div class="well">Se encontraron los siguientes resultados para la búsqueda de: <b>'.$busqueda.'</b></div>';
				else
					echo '<div class="well">No se encontraron resultados para la búsqueda de: <b>'.$busqueda.'</b></div>';
			}
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
		echo '<hr>';
	}

	function ordenar_resultados($datos, $orden, $tipoOrden){
		foreach ($datos as $d)
			$valor[] = $d[$orden];
		if ($tipoOrden == 'Ascendente')
			array_multisort($valor, SORT_ASC, $datos);
		else
			array_multisort($valor, SORT_DESC, $datos);
		return $datos;
	}

	function hay_datos($buscar,$categoria,$ciudad){
		if (!trim($buscar) AND !trim($categoria) AND !trim($ciudad))
			return false;
		return true;
	}

	function mostrar_ordenacion($orden, $tipoOrden, $buscar){
		echo '
		<form id="buscar-form" method="GET" name="buscar_form" action="../html/busqueda.php">
            <div class="row margin-top-20">
                <div class="col col-md-12">
                    <h4>Gauchadas ordenadas por</h4>
                    <div class="row">
                        <div class="col col-md-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col col-md-4">
                                        <select class="form-control" id="orden" name="orden"> 
                                        	<option value="" disabled>'.$orden.'</option>                                          
                                            <option>cantidad de postulantes</option>
                                            <option>fecha de creacion</option>
                                            <option>titulo</option>
                                        </select>
                                    </div>
                                    <div class="col col-md-3">
	                                    <select class="form-control" id="tipoOrden" name="tipoOrden">
	                                    	<option value="" disabled>'.$tipoOrden.'</option>
	                                        <option>Ascendente</option>
	                                        <option>Descendente</option>
	                                    </select>
                                   	</div>
                                   	<div class="col col-md-4 pull-right">
                                        <input type="submit" class="btn btn-success" value="Reordenar" >
                                    </div>
                               	</div>
                           	</div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="text" class="form-control" id="buscar" name="busqueda" value="'.$buscar.'" style="display:none!important">
        </form>
        <hr>';
	}

	function datos($orden, $tipoOrden){
		include '../includes/db_connect.php';
		$elementos=array();
		Session::init();
		$rows=Session::get("respuesta");
		foreach($rows as $row){
			$idGauchada=$row["idGauchada"];
			if ($stmt=$mysqli->prepare("SELECT COUNT(g.idGauchada) as postulantes, u.nombre, u.apellido, g.fechaCreacion, g.imagen, g.titulo, g.ciudad FROM Postulante p INNER JOIN Gauchada g ON p.idGauchada=g.idGauchada INNER JOIN Usuario u  ON u.idUsuario=g.idUsuario WHERE (p.idGauchada = $idGauchada)")){
				$stmt->execute();
				$stmt->store_result();
				$stmt->bind_result($postulantes, $nombre, $apellido, $fechaCreacion, $imagen, $titulo, $ciudad);
				$stmt->fetch();
				$dueño=$nombre . " " . $apellido;
				$elem=array('cantidad de postulantes'=>$postulantes, "fecha de creacion"=>$fechaCreacion, "imagen"=>$imagen, "titulo"=>$titulo, "ciu"=>$ciudad, "dueño"=>$dueño);
				array_push($elementos, $elem);
			}
		}
		$elementos=ordenar_resultados($elementos, $orden, $tipoOrden);
		foreach($elementos as $row){
			echo '<div class="col col-md-6 background-color-grey">
					<div class="panel-group margin-top-20">
			    		<div class="panel panel-warning">
					    	<div class="panel-heading">'.$row["titulo"].'</div>
						    	<div class="row">
									<div class="col col-md-8">
								    	<div class="panel-body">Fecha de creación: '.$row["fecha de creacion"].'
											<div>Postulantes <span class="badge">'.$row["cantidad de postulantes"].'</span>
											<div>Publicado por: '.$row["dueño"].'
											<div>Ciudad: '.$row["ciu"].'</div></div></div></div>
									    </div>
									    <div class="col col-md-4 margin-top-20">
											<img src="'.$row["imagen"].'" class="size-75 img-fluid" width="100px"/>
										</div>
									</div>
									<form class="padding-16" action="detalle.php?id='.$idGauchada.'" method="POST">
				                    	<button type="submit" class="btn btn-default">Ver gauchada</button>
				                    </form>
								</div>
							</div>
						</div>';
		}
	}

	function verResultados($buscar,$categoria,$ciudad,$orden,$tipoOrden){
		include '../includes/db_connect.php';
		if (!hay_datos($buscar, $categoria, $ciudad) )
			echo '<div class="well">No se ingreso ningún dato</div>';
		else{
			if ($stmt=crear_consulta($mysqli, $buscar, $categoria, $ciudad)){
				$stmt->execute();    // Ejecuta la consulta preparada.
				$res = $stmt->get_result();
				while($row = $res->fetch_assoc())
					$rows[] = $row;
				Session::init(); Session::set("respuesta", $rows);
				mostrar_busqueda($buscar, $mysqli);
				mostrar_filtros($categoria, $ciudad);
				mostrar_ordenacion($orden, $tipoOrden, $buscar);
				datos($orden, $tipoOrden);
			}
		}
	}

	function mostrar_categorias(){
		include('../includes/db_connect.php');
		echo'<select class="form-control" id="cate" name="filtrarCategoria">';		
		$stmt=$mysqli->prepare("SELECT titulo FROM Categoria");
				$stmt->execute();    // Ejecuta la consulta preparada.
				$res = $stmt->get_result();
				echo '<option value="" disabled selected>Selecciona una categoria</option>';
				while($row = $res->fetch_assoc())
					echo '<option>'.$row["titulo"].'</option>';
		echo'</select>';
	}

	function mostrar_categorias_cuenta(){
		include('../includes/db_connect.php');
		echo'<select class="form-control" id="cate" name="filtrarCategoria" required>';		
		$stmt=$mysqli->prepare("SELECT titulo FROM Categoria");
				$stmt->execute();    // Ejecuta la consulta preparada.
				$res = $stmt->get_result();
				echo '<option value="" disabled selected>Selecciona una categoria</option>';
				while($row = $res->fetch_assoc())
					echo '<option>'.$row["titulo"].'</option>';
		echo'</select>';
	}

	function mostrar_categorias_admin_e(){
		include('../includes/db_connect.php');	
		$stmt=$mysqli->prepare("SELECT titulo FROM Categoria");
		$stmt->execute();    // Ejecuta la consulta preparada.
		$res = $stmt->get_result();
		echo '<ul class="list-group">';
		$i=0;
		while($row = $res->fetch_assoc()){
			echo '<a href="#" data-alias="elem'.$i.'" class="list-group-item">'.$row["titulo"].'</a>';
			$i++;
		}
		echo '</ul>';
	}

	function mostrar_categorias_admin(){
		include('../includes/db_connect.php');		
		$stmt=$mysqli->prepare("SELECT titulo FROM Categoria");
		$stmt->execute();    // Ejecuta la consulta preparada.
		$res = $stmt->get_result();
		echo '<div class="list-group">';
		while($row = $res->fetch_assoc())
			echo '<li class="list-group-item">'.$row["titulo"].'</li>';
		echo '</div>';
	}

	function mostrar_categorias_admin_list(){
		include('../includes/db_connect.php');		
		$stmt=$mysqli->prepare("SELECT titulo FROM Categoria");
		$stmt->execute();    // Ejecuta la consulta preparada.
		$res = $stmt->get_result();
		echo '<div class="list-group">';
		while($row = $res->fetch_assoc())
			echo '<li class="list-group-item margin-4">'.$row["titulo"].'</li>';
		echo '</div>';
	}

	function mostrar_ciudades(){
		include('../includes/db_connect.php');
		echo'<select class="form-control" id="ciu" name="filtrarCiudad">';
		$stmt=$mysqli->prepare("SELECT ciudad FROM Gauchada");
				$stmt->execute();    // Ejecuta la consulta preparada.
				$res = $stmt->get_result();
				echo '<option value="" disabled selected>Selecciona una ciudad</option>';
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
			echo '<li><a class="no-link" href="micuenta.php">Mi Cuenta</a></li>';
			echo '<li><a class="link" onclick="cerrar_sesion()">Cerrar sesión</a></li>';
		}
		else
			echo '<li><a href="login.html" class="link">Login</a></li>';
	}

	function ver_ranking(){
		include('../includes/db_connect.php');
		$valor=$_POST['ranking'];
		if ($stmt=$mysqli->prepare("SELECT nombre, puntos FROM Usuario u INNER JOIN Calificacion c ON idUsuarioAnunciante = u.idUsuario ORDER BY puntos DESC, nombre ASC LIMIT $valor")){
			$stmt->execute();
			$res = $stmt->get_result();
			while($row = $res->fetch_assoc()){
				echo '<tr>';
				echo '<td>'.$row["nombre"].'</td>';
				echo '<td>'.$row["puntos"].'</td>';
				echo '</tr>';
			}
		}
	}
	
	//Rangos
	function getRanking($reputacion, $mysqli){
		if ($stmt=$mysqli->prepare("SELECT r.nombre FROM rangoreputacion r
		WHERE r.puntosIni <= $reputacion AND r.puntosFin >= $reputacion")){
			$stmt->execute();    // Ejecuta la consulta preparada.
			$stmt->store_result();
			$stmt->bind_result($rankingRes);
			$stmt->fetch();
			return $rankingRes;
		}
		return 'Sin definir';
	}


	function mostrarOpcionesUsuario(){
		Session::init();
		$esAdmin=Session::get("admin");
		if ($esAdmin == 0){
			echo '
	            <button type="submit" class="btn btn-md btn-success" onclick="verificar_creditos()">Publicar</button>
	            <button type="button" class="btn btn-md btn-warning" data-toggle="modal" data-target="#comprarCredito">Comprar crédito</button>';
	    }
	    else{
	    	echo '
                <div class="col col-md-4 margin-bottom-20 margin-sides-16" style="background-color: rgba(0,0,0, 0.1)!important; border-radius: 8px; padding:16px">
                    <h3>Administración de categorias</h3>
                    <h5>Estas son las categorias utilizadas en las gauchadas</h5>
                    <div class="row">
                        <div class="col col-md-12">';
                            mostrar_categorias_admin_list();
                   echo'</div>
                   		<div class="margin-sides-16"> 
	                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#nueva_categoria">Agregar</button>
	                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editar_categoria">Editar</button>
	                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#eliminar_categoria">Eliminar</button>
                        </div>
                    </div>
                </div>
                <div class="col col-md-6 margin-sides-16" style="background-color: rgba(0,0,0, 0.1)!important; border-radius: 8px; padding:16px">
                   	<h3>Estadistica de ingresos</h3>
                   	<h5>Con esta herramientas vas a poder consultar el ingreso en un período de tiempo</h5>
                   	<div class="row">
                   		<div class="col col-md-12">
							<form id="estadistica-form" method="POST" name="estadistica_form">
								<h6>Selccione las fechas del período</h6>
				                <div class="row">
				                	<div class="col col-md-12">
										<div class="row">
				                			<div class="col col-md-4">
										    	<div class="form-group">
		                                			<label for="fechaInicio">Fecha inicio</label>
		                                			<input type="date" class="form-control" id="fechaInicio" name="fechaInicio" value="2017-08-01" required>
		                                		</div>
		                                	</div>
											<div class="col col-md-4">
										    	<div class="form-group">
		                                			<label for="fechaFin">Fecha fin</label>	
										    		<input type="date" class="form-control" id="fechaFin" name="fechaFin" value="2017-08-01" required>
										      	</div>
										    </div>
											<div class="col col-md-4" style="margin-top:25px!important">
												<div class="form-group">
								      	   			<button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#estadistica" data-id="1">Consultar</button>
								      	   		</div>
								      	   	</div>
								      	</div>
								    </div>
				                </div>
				            </form>
				        </div>
                    </div>
                </div>
                <div class="col col-md-6 margin-sides-16 margin-top-20" style="background-color: rgba(0,0,0, 0.1)!important; border-radius: 8px; padding:16px">
                   	<h3>Ranking de usuarios</h3>
                   	<h5>Con esta herramientas vas a poder ver los usuarios con mayor puntaje</h5>
                   	<div class="row">
                   		<div class="col col-md-12">
							<form id="ranking-form" name="ranking_form" method="GET">
				                <div class="row">
				                	<div class="col col-md-8">
								    	<div class="form-group">
								      		<select class="form-control" id="ranking" name="ranking">
								      			<option>3</option><option>4</option>
								      			<option>5</option><option>7</option>
								      		</select>
								      	</div>
								    </div>
								    <div class="col col-md-4">
								    	<button type="button" class="btn btn-success rankear" data-toggle="modal" data-target="#rankear">Ver ranking</button>
								    </div>
				                </div>
                            	</div>
				            </form>
				        </div>
                    </div>
                </div>';
	    }
	}