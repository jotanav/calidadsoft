<?php
// Omitir errores

ini_set("display_errors", false);

// Incluimos nustro script php de funciones y conexión a la base de CDatos
include('../CDatos/mainFunctions.inc.php');

if(!isset($_SESSION["usr_session"])){

    header("Location: ../index.php");
}
else{

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

<title>.::.Sunarp.::.</title>
<meta charset="utf-8" />
<link type="text/css" href="css/smoothness/jquery-ui-1.8.23.custom.css" rel="stylesheet" />
<link type="text/css" href="css/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
<link type="text/css" href="css/master.css" rel="stylesheet" />
<link rel="stylesheet" href="css/style.css" type="text/css" media="screen"/>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
      <script type="text/javascript">
            $(function() {
				/**
				* for each menu element, on mouseenter,
				* we enlarge the image, and show both sdt_active span and
				* sdt_wrap span. If the element has a sub menu (sdt_box),
				* then we slide it - if the element is the last one in the menu
				* we slide it to the left, otherwise to the right
				*/
                $('#sdt_menu > li').bind('mouseenter',function(){
					var $elem = $(this);
					$elem.find('img')
						 .stop(true)
						 .animate({
							'width':'168px',
							'height':'84px',
							'left':'0px'
						 },400,'easeOutBack')
						 .andSelf()
						 .find('.sdt_wrap')
					     .stop(true)
						 .animate({'top':'140px'},500,'easeOutBack')
						 .andSelf()
						 .find('.sdt_active')
					     .stop(true)
						 .animate({'height':'170px'},300,function(){
						var $sub_menu = $elem.find('.sdt_box');
						if($sub_menu.length){
							var left = '170px';
							if($elem.parent().children().length == $elem.index()+1)
								left = '-170px';
							$sub_menu.show().animate({'left':left},200);
						}
					});
				}).bind('mouseleave',function(){
					var $elem = $(this);
					var $sub_menu = $elem.find('.sdt_box');
					if($sub_menu.length)
						$sub_menu.hide().css('left','0px');

					$elem.find('.sdt_active')
						 .stop(true)
						 .animate({'height':'0px'},300)
						 .andSelf().find('img')
						 .stop(true)
						 .animate({
							'width':'0px',
							'height':'0px',
							'left':'85px'},400)
						 .andSelf()
						 .find('.sdt_wrap')
						 .stop(true)
						 .animate({'top':'25px'},500);
				});
            });
        </script>

<!--<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css"> -->
    <!--<link rel="stylesheet" href="css/main.css"> -->



<script type="text/javascript" src="js/jquery/jquery.1.8.1.js"></script>
<script type="text/javascript" src="js/bootstrap/bootstrap.min.js"></script>

<script type="text/javascript" src="../CNegocio/Pedidos_Externos/PedidoExterno.js"></script>
<script type="text/javascript" src="js/jquery-validation-1.10.0/dist/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/jquery-validation-1.10.0/lib/jquery.metadata.js"></script>
<script type="text/javascript" src="js/jquery-validation-1.10.0/localization/messages_es.js"></script>

<script>
function paginar(){

       $('table#listadoPedidoExterno').each(function() {
    var currentPage = 0;
    var numPerPage = 20;
    var $table = $(this);

    $table.bind('repaginate', function() {
        $table.find('tbody tr').hide().slice(currentPage * numPerPage, (currentPage + 1) * numPerPage).show();
    });
    $table.trigger('repaginate');
    var numRows = $table.find('tbody tr').length;
    var numPages = Math.ceil(numRows / numPerPage);
    var $pager = $('<div class="pager" ></div>');
    for (var page = 0; page < numPages; page++) {
        $('<span class="page-number"></span>').text(page + 1).bind('click', {
            newPage: page
        }, function(event) {
            currentPage = event.data['newPage'];
            $table.trigger('repaginate');
            $(this).addClass('active').siblings().removeClass('active');
        }).appendTo($pager).addClass('clickable');
    }


      if(numRows > 20)
      {
         $pager.insertBefore($table).find('span.page-number:first').addClass('active');

      }


});

  }
        //JS PARA FILTRO DE BUSQUEDA

</script>


       <script language="javascript">


             function doSearch() {


                var tableReg = document.getElementById('listadoPedidoExterno');
                var searchText = document.getElementById('searchTerm').value.toLowerCase();
                for (var i = 1; i < tableReg.rows.length; i++) {
                    var cellsOfRow = tableReg.rows[i].getElementsByTagName('td');
                    var found = false;
                    for (var j = 0; j < cellsOfRow.length && !found; j++) {
                        var compareWith = cellsOfRow[j].innerHTML.toLowerCase();
                        if ((compareWith.indexOf(searchText) > -1)) {
                            found = true;

                        }

                      }
                         if (found) {


                        tableReg.rows[i].style.display = '';

                        if(searchText == 0)
                            {
                                location.reload();

                                doSearch();

                                return false;

                            }

                    } else {
                        tableReg.rows[i].style.display = 'none';


                    }
                }


            }


        </script>

</head>
<body onload="paginar();">

   <?php

   include 'menu.php';

   ?>

<div id="pop1" class="popbox" style="display: none; top: 1563px; left: 547px;">
    <h2>DETALLE DE TITULO</h2>

   <table id="listadoPedidoExternodetalle" class="table table-striped table-bordered table-hover table-condensed">
   </table>
</div>
<div class="row-fluid">
 </br>
 </br>
<!--Aqui se coloca formularios etc -->

      <div  class="container"  style="margin-top=200px;">
    <h1>Bandeja de Solicitudes</h1>
    </div>

   </div>



             <!--This is a popbox test.  <a href="#" class="popper" data-popbox="pop1">Hover here</a> to see how it works.  You can also hover <a href="#" class="popper" data-popbox="pop2">here</a> to see a different example.-->
       <div id="wraper">
       <section id="content">
       <div id="btnAddArea" class=" center busquedadiv">
		    	<div id="busqueda">
                 <input id="searchTerm" type="text" onkeyup="doSearch()"  /> <img src="images/buscar.png"/>
                </div>

       </div>
       <div id="listaPedidoExterno">
					<table id="listadoPedidoExterno" class="table table-striped table-bordered table-hover table-condensed">
						<thead>
							<tr>
                            <th>N° Titulo</th>
                            <th>Año del titulo</th>
                            <th>Propietario del titulo</th>
                            <th>N° Atencion</th>
                            <th>Fecha de Solicitud</th>
                            <th style ="text-align:center;">Estado</th>
                            <th style ="text-align:center;">Ver</th>
                             <th style ="text-align:center;">Titulo pdf</th>

							</tr>
						</thead>
                        <!-- MUESTRAS RESULTADOS-->
						<tbody id="listaPedidoexternoOK">

						   	<?php echo $consultaPerdiExterno ?>
						</tbody>
                        <!-- FIN-->
					</table>
	    </div>
        </section>
      </div>



<!--Ejemplo de tabla responsive -->



    <!--/.well -->
    <!--/span3-->


    <!--/span9-->


<!--/row-fluid-->


<footer align="center">
    <p>Copyright &copy; 2013 <strong>Company Name</strong></p>
</footer>

</body>
</html>
 <?php } ?>
