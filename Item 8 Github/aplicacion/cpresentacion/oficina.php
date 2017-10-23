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
	      $consultaOficinas = consultaOficinas($mysqli);
        $consultacomboArea = consultacomboArea($mysqli);

       // $consultacomboDepartamentos = consultacomboDepartamentos($mysqli);
       // $consultacomboProveedor = consultacomboProveedor($mysqli);

       }
else
{
	// Regresa error en la base de CDatos
	$consultaOficinas = '
		<tr id="sinCDatos">
			<td colspan="5" class="centerTXT">ERROR AL CONECTAR CON LA BASE DE CDatos</td>
	   	</tr>
	';
}

?>
<!DOCTYPE html>
 
<html lang="es">
 
<head>
<title>:: Sistema de Almacen ::</title>
<meta charset="utf-8" />
<link type="text/css" href="css/smoothness/jquery-ui-1.8.23.custom.css" rel="stylesheet" />
<link type="text/css" href="css/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
<link type="text/css" href="css/master.css" rel="stylesheet" />
<link rel="stylesheet" href="css/style.css" type="text/css" media="screen"/>
<script type="text/javascript" src="js/jquery1.4.2.js"></script>
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
<script type="text/javascript" src="js/jquery/jquery.1.8.1.js"></script>
<script type="text/javascript" src="js/jquery_ui/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="js/jquery_ui/jquery-ui-1.8.23.custom.min.js"></script>
<script type="text/javascript" src="js/bootstrap/bootstrap.min.js"></script>
<!-- Con este scrip manejas el CRUD-->
<script type="text/javascript" src="../CNegocio/Oficinas/OficJavaScript.js"></script>
<script type="text/javascript" src="js/jquery-validation-1.10.0/dist/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/jquery-validation-1.10.0/lib/jquery.metadata.js"></script>
<script type="text/javascript" src="js/jquery-validation-1.10.0/localization/messages_es.js"></script>

<script>
 //JS PARA PAGINACIÓN
//$( document ).ready(function() {
  
  //$("#p_familia option:first").attr('selected','selected');
  
 
  function paginar(){
      
       $('table#listadoOficina').each(function() {
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
    
   
//});     
   
    
</script>


<script language="javascript">
           
           //JS PARA FILTRO DE BUSQUEDA
            
            
            
             function doSearch() {
                     
        
                var tableReg = document.getElementById('listadoOficina');
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
    
     
       <!-- Ventana Modal-->
      <?php 
    header('Content-type: text/html; charset=utf-8'); 

       include 'menu.php';?> 

  <div class="row-fluid">
    </br>
    </br>
       <div  class="container"  style="margin-top=200px;">
      
       <h1>OFICINAS</h1>

       </div>
       </div>
		<div class="hide" id="agregarOficina" Title="Agregar Oficina">
	    	<form action="" method="post" id="formOficina" name="formOficina">
            <fieldset id="ocultos">
	    			<input type="hidden" id="accion" name="accion" class="{required:true}"/>
	    			<input type="hidden" id="id_oficina" name="id_oficina" class="{required:true}" value="0"/>
                                
	    	   </fieldset>
               
				<fieldset id="CDatosOficina">
                                   
                                            
                                            
                                            <table>
                                            <tr>
                                                <td>
                                                   <p>Codigo de la Oficina:</p>
                                                   <span></span>
                                                   <input type="text"  id="of_codigo" name="of_codigo"  class="{required:true,maxlength:12} span3"/>
                                                </td>
                                                <td width="11"></td>
                                                <td>
                                                <p>Nombre del Área:</p>
                                                 <span></span>
                                                <select id="of_codigoa" name="of_codigoa" class="{required:true} span3">
                                                 <option value="">< Seleccione Opcion ></option>
                                                 <?php echo $consultacomboArea?>           
                                                </select>
                                               </td>

                                            <tr>
                                            </table>

                                            <table>
                                             <tr>
                                              <td>
                                                <p>Nombre:</p>
                                                <span></span>
                                                <input type="text"  id="of_nombre" name="of_nombre"  class="{required:true,maxlength:200} span3"/>
                                             </td> 
              
                                             <td width="11"></td>
                                              <td>
                                              <p>Anexo:</p>
                                                <span></span>
                                                <input type="text"  id="of_anexo" name="of_anexo"  class="{required:true,maxlength:200} span3"/>
                                             </td>
                                              <tr>  
                                        </table>
                                                                                     
                                        <table>
                                            <tr>
                                             <td>
                                               <p>Piso:</p>
      					                               <span></span>
      					                               <input type="text"  id="of_piso" name="of_piso"  class="{required:true,maxlength:200} span3"/>
                                          </td>
                                          
                                          <td width="11"></td>
                                             <td>
                                             <p>Descripción:</p>
                                              <span></span>
                                                <input type="text"  id="of_descripcion" name="of_descripcion"  class="{required:true,maxlength:200} span3"/>
                                             </td>
                                             <tr>

                                        </table>      
                                  </fieldset> 

                                   <fieldset id="mensajeborrar">
                                      <p>Desea borrar esta Oficina?<p>
                                        <img style ="margin-left: 0px;"src="images/advertencia.png"/>
                                    </fieldset>



                                   <fieldset id="btnAgregar" style="text-align:center;">
                                                <input type="submit" id="continuar" value="Continuar" />
                                  </fieldset> 
                                 
                                      
                                        <fieldset id="ajaxLoader" class="ajaxLoader hide">
					                                   <img src="images/ajax-loader.gif">
					                                 <p>Espere un momento...</p>
                                        </fieldset>
                    
                    
                   
				

				
                      
			</form>
	    </div>
              
              
        <!-- Fin ventana modal-->

        
		<div id="wraper">
		    <section id="content">
		    	<div id="btnAddOficina" class=" center busquedadiv">
		    	<div id="busqueda">
                            <button id="goNuevoOficina" type="button" class="btn btn-default">Agregar Oficinas</button> &nbsp;&nbsp;&nbsp;<input id="searchTerm" type="text" onkeyup="doSearch()"  /> <img src="images/buscar.png"/>
                        </div>	
		    	                      
                        </div>
                        
				<div id="listaOrganizadores">
					<table id="listadoOficina" class="table table-striped table-bordered table-hover table-condensed">
						<thead>
							<tr>
                                								                <th>Codigo</th>
                                                                <th>Nombre del Área</th>
                                								                <th>Nombre</th>
                                                                <th>Anexo</th>
                                                                <th>Piso</th>
                                                                <th>Descripción</th>

                                                                
                                                                <th></th>
                                                                <th></th>
							</tr>
						</thead>
                        <!-- MUESTRAS RESULTADOS-->
						<tbody id="listaOficinaOk">
                                                  
						  <?php echo $consultaOficinas ?>
						</tbody>
                        <!-- FIN-->
					</table>
				</div>
                        


		    </section>
                    
                    
 		</div>
              
             
  		<footer>
	        Powered by Victor torres villar || 2013
		</footer>
               
             
</body>
</html>


<?php } ?>