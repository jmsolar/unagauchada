<?php include_once('../includes/session.php');
Session::init(); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title>Detalle de gauchada - Una gauchada</title>

        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/custom.css" rel="stylesheet">

        <script src="../js/jquery-3.2.1.min.js"></script>    
    <script src="../js/jquery.validate.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/detallar.js"></script>
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

                            <div class="row">
                                <h4>Comentarios</h4>
                                <?php if(sizeof($comentariosRes)): ?>
                                <table class="table table-hover">
                                    <thead>
                                        <th width="50%">Comentario</th>
                                        <th width="10%">Fecha</th>
                                        <th width="10%">Autor</th>
                                        <th width="30%">Respuesta</th>
                                    </thead>
                                    <tbody>
                                        <?php foreach($comentariosRes as $comentario): ?>
                                        <tr>
                                            <td><?=$comentario['textoComentario']?></td>
                                            <td><?=$comentario['fechaComentario']?></td>
                                            <td><?=$comentario['nombre']?> <?=$comentario['apellido']?></td>
                                            <td><?php if($comentario['textoRespuesta']): ?>
                                                <?=$comentario['textoRespuesta']?>
                                            <?php elseif ($gauchadasRes->email === Session::get('email')): ?>
                                                <button class="btn btn-small btn-info" data-toggle="modal" data-target="#answerModal" data-comment-id="<?=$comentario['idComentario']?>">Responder</button>
                                            <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <div>No hay comentarios para esta publicacion</div>
                                <br/>
                            <?php endif; ?>
                            </div>


                            <div>
                                <?php if(Session::get('conectado') == 1 && $gauchadasRes->email !== Session::get('email')): ?>
                                    <a class="btn btn-info" data-toggle="modal" data-target="#commentModal">Dejar un comentario</a>

                                    <a href="home.php" class="btn btn-success">Postularme</a>
                                <?php endif; ?>

                                <a href="home.php" class="btn btn-warning">Volver al listado</a>                            
                            </div>
                    </div>
                </div>
            </div>
        </div>



    <!-- comment modal -->
    
    <div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Dejar un comentario</h4>
          </div>
          <form>
              <div class="modal-body">
                 <div class="form-group">
                    <label for="Comentario">Comentario</label>
                    <textarea class="form-control" id="Comentario" rows="3"></textarea>
                    <input type="hidden" id="idGauchada" value="<?=$_GET['id']?>"></textarea>
                    <div id="errorMsg" style="display:none">El comentario no puede ser vacio</div>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" onclick="submitComment()" class="btn btn-primary">Guardar</button>
              </div>
          </form>
        </div>
      </div>

      <script type="text/javascript">

            function submitComment() {
                $('#errorMsg').hide();
                if (!$('#Comentario').val()) {
                    $('#errorMsg').show();
                    return;
                };

                $.ajax({
                    type: "POST",
                    data: {comment: $('#Comentario').val(), gauchada: $('#idGauchada').val()},
                    url: "../process/process_comentar.php",
                    success: function(data){
                        location.reload();
                    },
                    error: function(data) {
                        $('#errorMsg').html(data.responseText).show();
                    }
                });
            }



      </script>
    </div>

        <!-- comment modal -->
    
    <div class="modal fade" id="answerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Responder Comentario</h4>
          </div>
          <form>
              <div class="modal-body">
                 <div class="form-group">
                    <label for="Comentario">Respuesta</label>
                    <textarea class="form-control" id="Respuesta" rows="3"></textarea>
                    <input type="hidden" id="idGauchada" value="<?=$_GET['id']?>"/>
                    <div id="errorMsg" style="display:none">El comentario no puede ser vacio</div>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" onclick="submitAnswer()" class="btn btn-primary">Guardar</button>
              </div>
          </form>
        </div>
      </div>

      <script type="text/javascript">
        var commentId = -1;
        
        $('#answerModal').on('show.bs.modal', function(e) {
            //get data-id attribute of the clicked element
            commentId = $(e.relatedTarget).data('comment-id');
        });

        function submitAnswer() {
            $('#errorMsg').hide();
            if (!$('#Respuesta').val()) {
                $('#errorMsg').show();
                return;
            };

            $.ajax({
                type: "POST",
                data: {respuesta: $('#Respuesta').val(), comentario: commentId},
                url: "../process/process_responder.php",
                success: function(data){
                    location.reload();
                },
                error: function(data) {
                    $('#errorMsg').html(data.responseText).show();
                }
            });
        }
     

            

            

      </script>
    </div>


    
    </body>
</html>