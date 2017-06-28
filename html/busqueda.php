<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Inicio - Una gauchada</title>

        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/custom.css" rel="stylesheet">

    </head>
    <?php require( "../includes/functions.php" );?>
    <body class="home-container">
        <nav class="navbar navbar-inverse no-border-radius">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="home.php">Una gauchada</a>
                </div>

                 <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <?php 
                            usuario_logueado();
                            ver_opcion_usuario(); 
                        ?>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container margin-bottom-16">
            <div class="row">
				<div class="col col-md-12">
					<div class="row">
						<?php //busqueda, categoria, ciudad
                            if (!isset($_GET["filtrarCategoria"]) AND !isset($_GET["filtrarCiudad"]))
                                verResultados($_GET["busqueda"],0,0);//Sin filtros
                            else{
                                if (isset($_GET["filtrarCategoria"]) AND !isset($_GET["filtrarCiudad"]))
                                    verResultados($_GET["busqueda"],$_GET["filtrarCategoria"],0);//Con filtro categoria
                                else{
                                    if (!isset($_GET["filtrarCategoria"]) AND isset($_GET["filtrarCiudad"]))
                                        verResultados($_GET["busqueda"],0,$_GET["filtrarCiudad"]);//Con filtro ciudad
                                    else
                                        verResultados($_GET["busqueda"],$_GET["filtrarCategoria"],$_GET["filtrarCiudad"]);//Ambos filtros
                                }
                            }
                        ?>
            		</div>
            		<hr>
            		<form action="home.php">
    					<input type="submit" class="btn btn-warning float-right" value="Volver al inicio">
					</form>
            	</div>
            </div>
        </div>
    <script src="../js/jquery-3.2.1.min.js"></script>    
    <script src="../js/jquery.validate.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/login.js"></script>
    <script src="../js/filtrar.js"></script>
    </body>
</html>