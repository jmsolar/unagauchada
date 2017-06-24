<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta http-equiv="cache-control" content="max-age=0" />
        <meta http-equiv="cache-control" content="no-cache" />
        <meta http-equiv="expires" content="0" />
        <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
        <meta http-equiv="pragma" content="no-cache" />
        <title>Inicio - Una gauchada</title>

        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/custom.css" rel="stylesheet">
    </head>
    
    <?php require_once( "../includes/functions.php" );?>
    <body class="home-container">
        <?php error_reporting(0); ?>
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
                    <a class="navbar-brand font-size-14" href="misdatos.html">Mis datos</a>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <?php usuario_logueado(); ?>
                        <li><a class="link" onclick="cerrar_sesion()">Cerrar sesión</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <form id="publicar-form" method="POST" name="publicar_form" enctype="multipart/form-data">
            <div class="modal fade" id="publicar" role="dialog" data-backdrop="static">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h5 class="modal-title" id="titulo">Nueva publicación</h5>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="titulo">Título</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Título" autofocus="autofocus" required>
                            </div>
                            <div class="form-group">
                                <label for="descripcion">Descripción</label>
                                <textarea class="form-control" rows="3" id="descripcion" name="descripcion" required></textarea>
                            </div>
                            <div class="row">
                                <div class="col col-md-3">
                                    <div class="form-group">
                                        <label for="categoria">Categoria</label>
                                        <input type="text" class="form-control" id="categoria" name="categoria" placeholder="Categoria" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-md-6">
                                    <div class="form-group">
                                        <label for="fecVencimiento">Fecha de vencimiento</label>
                                        <input type="date" class="form-control" id="fecVencimiento" name="fecVencimiento" value="2017-01-04" required>
                                    </div>
                                </div>
                                <div class="col col-md-6">
                                    <div class="form-group">
                                        <label for="ciudad">Ciudad</label>
                                        <input type="text" class="form-control" id="ciudad" name="ciudad" placeholder="Ciudad" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="foto">Imagen</label>
                                <input name="foto" id="foto" type="file">
                            </div> 
                            <div id="error-publicar"></div>
                        </div>                   
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-warning">Aceptar</button>
                        </div>                
                    </div>
                </div>
            </div>
        </form>

        <form id="comprar-form" method="POST" name="comprar_form" onchange="return validar_longitud(this);">
            <div class="modal fade" id="comprarCredito" role="dialog" data-backdrop="static">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h5 class="modal-title" id="titulo">Comprar créditos</h5>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col col-md-6">
                                    <div class="form-group">
                                        <label for="tarjeta">Tarjeta de crédito</label>
                                        <input type="text" class="form-control" id="tarjeta" name="tarjeta" placeholder="N° de la tarjeta de crédito" autofocus="autofocus" onkeypress="return validar_tarjeta(event)" required>
                                    </div>
                                    <div id="error-format-comprar"></div>
                                </div>
                                <div class="col col-md-4">
                                    <div class="form-group">
                                        <label for="credito">Cantidad de créditos</label>
                                        <input class="form-control" rows="3" id="credito" name="credito" placeholder="Cantidad de créditos" onkeypress="return validar_tarjeta(event);" onchange="return validar_credito(event);" required>
                                    </div>
                                </div>
                            </div>
                            <div id="error-comprar"></div>
                        </div>                   
                        <div class="modal-footer">
                            <button type="button" id="btn-cancelar" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" id="btn-comprar" class="btn btn-warning">Aceptar</button>
                        </div>                
                    </div>
                </div>
            </div>
        </form>

        <form id="ver-postulantes-form" method="POST" name="ver_postulantes_form">
            <div class="modal fade" id="verPostulantes" role="dialog" data-backdrop="static">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" onclick="cerrarPostulantes()">&times;</button>
                            <h5 class="modal-title" id="titulo">Postulantes</h5>
                        </div>
                        <div class="modal-body">
                            <div>
                                <div><span>Apellido: </span>Gimenez</div>
                                <div><span>Nombre: </span>Soledad</div>
                                <button type="button" class="btn btn-danger">Ver perfil</button>
                            </div>
                            <hr>
                            <div>
                                <div><span>Apellido: </span>Gimenez</div>
                                <div><span>Nombre: </span>Soledad</div>
                                <button type="button" class="btn btn-danger">Ver perfil</button>
                            </div>
                            <hr>
                            <div>
                                <div><span>Apellido: </span>Gimenez</div>
                                <div><span>Nombre: </span>Soledad</div>
                                <button type="button" class="btn btn-danger">Ver perfil</button>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn-volver" class="btn btn-warning" onclick="cerrarPostulantes()">Volver al listado</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <form id="ver-gauchadas-pedidas-form" method="POST" name="ver_gauchadas_pedidas_form" enctype="multipart/form-data">
            <div class="modal fade" id="verGauchadasPedidas" role="dialog" data-backdrop="static">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h5 class="modal-title" id="titulo">Mis gauchadas pedidas</h5>
                        </div>
                        <div class="modal-body">
                            <?php verGauchadasPedidas(); ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn-cancelar" class="btn btn-warning" data-target="verGauchadasPedidas" data-dismiss="modal">Cerrar</button>
                        </div> 
                    </div>
                </div>
            </div>
        </form>

        <form id="ver-gauchadas-cerradas-form" method="POST" name="ver_gauchadas_cerradas_form" enctype="multipart/form-data">
            <div class="modal fade" id="verGauchadasCerradas" role="dialog" data-backdrop="static">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h5 class="modal-title" id="titulo">Mis gauchadas cerradas</h5>
                        </div>
                        <div class="modal-body">
                            <?php verGauchadasCerradas(); ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn-cancelar" class="btn btn-warning" data-target="verGauchadasCerradas" data-dismiss="modal">Cerrar</button>
                        </div> 
                    </div>
                </div>
            </div>
        </form> 

        <form id="calificar-form" method="POST" name="calificar_form">
             <div class="modal fade" id="calificar" role="dialog" data-backdrop="static">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h5 class="modal-title" id="titulo">Calificar usuario</h5>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="comentario">Cómo calificarías al candidato?</label>
                                <label class="checkbox-inline"><input type="checkbox" value="" id="comentario">Positivo</label>
                                <label class="checkbox-inline"><input type="checkbox" value="" id="comentario">Neutro</label>
                                <label class="checkbox-inline"><input type="checkbox" value="" id="comentario">Negativo</label>
                            </div>
                            <div class="form-group">
                                <label for="comentario">Qué comentario querés realizar? *Campo obligatorio</label>
                                <textarea class="form-control" rows="5" id="comentario"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btn-calificar" class="btn btn-success" data-dismiss="modal">Calificar</button>
                            <button type="button" id="btn-cancelar" class="btn btn-warning" data-target="calificar" data-dismiss="modal">Cancelar</button>
                        </div> 
                    </div>
                </div>
            </div>           
        </form> 

        <div class="container">
            <div class="row">
                <div class="col col-md-12">
                    <button type="button" class="btn btn-md btn-success" data-toggle="modal" data-target="#publicar">Publicar</button>
                    <button type="button" class="btn btn-md btn-warning" data-toggle="modal" data-target="#comprarCredito">Comprar crédito</button>
                    <button type="button" class="btn btn-md btn-default" data-toggle="modal" data-target="#verGauchadasPedidas">Ver gauchadas pedidas</button>
                    <button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#verGauchadasCerradas">Ver gauchadas cerradas</button>
                </div>
            </div>    
        </div>

    <script src="../js/jquery-3.2.1.min.js"></script>    
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/publicar.js"></script>
    <script src="../js/comprar.js"></script>
    <script src="../js/verGauchadasPedidas.js"></script>
    <script src="../js/verPostulantes.js"></script>
    <script src="../js/jquery.validate.min.js"></script>
    <script src="../js/cerrarSesion.js"></script>
    </body>
</html>