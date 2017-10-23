<?php
// Omitir errores

ini_set("display_errors", false);

// Incluimos nustro script php de funciones y conexión a la base de CDatos
include('../CDatos/mainFunctions.inc.php');

if(!isset($_SESSION["id_usuario"])){

    header("Location: ../index.php");
}
else{
if(isset($_GET["token"]) and $_GET["r"]=="p"){
 $query = "UPDATE pedidos SET fk_Id_usuario =".$_SESSION["id_usuario"]." WHERE id_usuario = ".$_GET["id_usuext"];

    $resultadoQuery = $linkDB -> query($query);

    if($linkDB -> affected_rows == 1){
        
    echo '<script>alert("llego");</script>';
}
}

if($errorDbConexion == false){

	// MAnda a llamar la función para mostrar la lista de usuarios
    //$consulta usuarios es la variable que contandra la tabla armada que esta en el mainfunctions.inc.php
	    $consultaPerdiExterno = ConsultaPedidoExterno($mysqli);
       // $consultacomboDepartamentos = consultacomboDepartamentos($mysqli);
        //$consultacomboProveedor = consultacomboProveedor($mysqli);

       }
else
{
	// Regresa error en la base de CDatos
	$consultaPedidoExterno = '
		<tr id="sinCDatos">
			<td colspan="5" class="centerTXT">ERROR AL CONECTAR CON LA BASE DE CDatos</td>
	   	</tr>
	';
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Start Bootstrap - SB Admin Version 2.0 Demo</title>

    <!-- Core CSS - Include with every page -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- Page-Level Plugin CSS - Tables -->
    <link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">

    <!-- SB Admin CSS - Include with every page -->
    <link href="css/sb-admin.css" rel="stylesheet">

</head>

<body>

    <div id="wrapper">

      <?php

   include 'menu.php';

   ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Mis Solicitudes</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <b>Bandeja de Solicitudes</b>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="listadoPedidoExterno">
                                    <thead>
                                        <tr>
                                            <th>N° Titulo</th>
                                            <th>Año del titulo</th>
											<th>Propietario del titulo</th>
											<th>N° Atencion</th>
											<th>Fecha de Solicitud</th>
											<th>Estado</th>
											<th>Ver</th>
											<th>Titulo pdf</th>
                                        </tr>
                                    </thead>
                                    <tbody id="listaPedidoexternoOK">
                                 <?php echo $consultaPerdiExterno ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
							
							<!--<button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
                                Launch Demo Modal
                            </button>
							<button type="button" name="botoncito" class="btn btn-warning btn-circle" data-toggle="modal"data-target="#myModal" ><i class="fa fa-list"></i>
                            </button>-->
<!-- Modal -->
							    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="myModalLabel">Detalle de la solicitud</h4>
                                        </div>
                                        <div class="modal-body">
                                           <table id="listadoPedidoExternodetalle" class="table table-striped table-bordered table-hover table-condensed">
   </table>
                                        </div>
                                        <div class="modal-footer">
                                           <!--  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                            <button type="button" class="btn btn-primary">Save changes</button> -->
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
							<!-- /.modal -->
                           <!--  <div class="well">
                                <h4>DataTables Usage Information</h4>
                                <p>DataTables is a very flexible, advanced tables plugin for jQuery. In SB Admin, we are using a specialized version of DataTables built for Bootstrap 3. We have also customized the table headings to use Font Awesome icons in place of images. For complete documentation on DataTables, visit their website at <a target="_blank" href="https://datatables.net/">https://datatables.net/</a>.</p>
                                <a class="btn btn-default btn-lg btn-block" target="_blank" href="https://datatables.net/">View DataTables Documentation</a>
                            </div> -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        
            <!-- /.row -->
        
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Core Scripts - Include with every page -->
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>

    <!-- Page-Level Plugin Scripts - Tables -->
    <script src="js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="../CNegocio/Pedidos_Externos/PedidoExterno.js"></script>

    <!-- SB Admin Scripts - Include with every page -->
    <script src="js/sb-admin.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
	/*$('#listadoPedidoExterno').dataTable();
    $(document).ready(function() {
	 
	 $.ajax({
                type: "POST",
                dataType: "json",
                url: "../CDatos/Pedidos_Externos/phpAjaxPedidosExt.inc.php",
                data: { idPed: 1, accion:'Ver'},
                success: function(data){
				                     // $('#listadoPedidoExternodetalle').empty();
                     //$("#pop1").append(data.contenido);
                                      	$('#listadoPedidoExternodetalle').append(data.contenido);
							            
							


                },
                error: function (Data) {
                    alert('x: ' + Data);
                }
              });
       
		
		  
    });*/
    </script>

</body>

</html>
<?php } ?>