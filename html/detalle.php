<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title>Detalle de gauchada - Una gauchada</title>

        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/custom.css" rel="stylesheet">
    </head>
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
                  <a class="navbar-brand" href="home.php">Una Gauchada</a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <!-- <li><a href="login.html">Login</a></li> -->
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="row">
                <div class="col col-md-8 offset-md-2">
                    <?php 
                        require( "../process/process_detallar.php" );
                    ?>
                    <div class="list-group ">
                        <h4 class="list-group-item-heading padding-16"><?=$gauchadasRes->titulo?></h4>
                            <p class="list-group-item-text">
                                <div class="row">
                                    <div class="col col-md-10">
                                        <div class="row"><?=$gauchadasRes->descripcion?></div>
                                        
                                        <div class="row">Fecha de creacion: <?=$gauchadasRes->fechaCreacion?></div>

                                        <div class="row">Fecha de vencimiento: <?=$gauchadasRes->fechaVencimiento?></div>
                                        <div class="row"><div role="presentation">Postulantes <span class="badge"><?=$nroPostulantes?></span></div> </div>
                                        <div class="row">Estado: <span class="label label-info"><?=$gauchadasRes->estado?></span></div>
                                        <div class="row">Publicador: <?=$nombreCompleto?></div>
                                        <div class="row">Ciudad: <?=$gauchadasRes->ciudad?></div>
                                    </div>
                                    <div class="col col-md-2">
                                        <img src="../img/<?=$image?>" class="img-fluid"/>
                                    </div>
                                </div>
                            </p>

                            <hr>

                            <div>
                                <h4>Comentarios</h4>
                                <table class="table table-hover">
                                    <thead>
                                        <th width="80%">Comenario</th>
                                        <th width="20%">Autor</th>
                                    </thead>
                                    <tbody>
                                        <?php foreach($postulantesRes as $postulante): ?>
                                        <tr>
                                            <td><?=$postulante['comentario']?></td>
                                            <td><?=$postulante['nombre']?> <?=$postulante['apellido']?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <form action="home.php">
                                <input type="submit" class="btn btn-warning" value="Volver al listado"/>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    <script src="../js/jquery-3.2.1.min.js"></script>    
    <script src="../js/jquery.validate.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/detallar.js"></script>
    </body>
</html>