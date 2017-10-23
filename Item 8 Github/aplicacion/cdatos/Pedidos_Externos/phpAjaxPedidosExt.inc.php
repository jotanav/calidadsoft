<?php
// Script para ejecutar AJAX

// Insertar y actualizar tabla de usuarios


// Inicializamos variables de mensajes y JSON
$respuestaOK = false;
$mensajeError = "No se puede ejecutar la aplicación";
$contenidoOK = "";

// Incluimos el archivo conexión a la base de CDatos
include('../mainFunctions.inc.php');

//$statusTipoOK =  array("1" => "btn-success","0" => "btn-warning");
//$statusTiponombreOK = array("1" => "Activo","0" => "Suspendido");
 //$estado_perOK = array("2"=> "Administrador","1"=> "Usuario");
 
 
// Validar conexión con la base de CDatos
if($errorDbConexion == false){
	// Validamos qe existan las variables post
	if(isset($_POST) && !empty($_POST)){
		// Verificamos las variables de acción
                        //AQUI ESTARAN LAS FUNCIONES DE EDITAR Y BORRAR
		switch ($_POST['accion']) {
			case 'Ver':
				// Armamos el query
                               /* $id_prov = mysql_real_escape_string($_POST['p_proveedor']);
                                $tipo = mysql_real_escape_string($_POST['p_tipo']);
                                $codigo = mysql_real_escape_string($_POST['p_codigo']);
                                $estado = 1;
                                $nombre = mysql_real_escape_string($_POST['p_nombre']);
                                $p_costo = mysql_real_escape_string($_POST['p_costo']);
                                $p_venta = mysql_real_escape_string($_POST['p_venta']);
                                $p_garantia = mysql_real_escape_string($_POST['p_garantia']);
                                $p_familia = mysql_real_escape_string($_POST['p_familia']);
                                $p_marca = mysql_real_escape_string($_POST['p_marca']);
                                $p_serie = mysql_real_escape_string($_POST['p_serie']);
                                $p_smin = mysql_real_escape_string($_POST['p_smin']);
                                $p_smax = mysql_real_escape_string($_POST['p_smax']);
                                $p_sact = mysql_real_escape_string($_POST['p_sact']);
                                $p_moneda = mysql_real_escape_string($_POST['p_moneda']);*/

//$consultarproducto = "SELECT  ped.numero_pedido,ped.nro_atencion ,ped.fecha_entrega ,ped.fecha_devolucion,su.usuario as regusuario ,tit.anio,tit.mes,tit.numero as numerotitulo,tr.descripcion as tiporeg,tom.nr_tomo ,su.dni ,CONCAT_WS(' ',su.ape_paterno,su.ape_materno,su.nombres) as nomcomp ,su.direccion ,su.celular ,su.telefono FROM pedidos_titulo pt INNER JOIN sunarp_usuarios su ON pt.fk_Id_Usuario = su.id_usuario INNER JOIN titulos tit ON pt.fk_Id_Titulo = tit.id_titulo INNER JOIN pedidos ped ON ped.fk_Id_usuario = su.id_usuario INNER JOIN tipos_registro tr ON tit.fk_Id_Tipo_Registro_Titulo = tr.id_tipo_registro INNER JOIN tomos tom ON tom.id_tomo = tit.fk_Id_Tomo WHERE pt.id_pedido_titulo =".$_POST['idPed'];

$consultarproducto = "

SELECT
 ped.nro_atencion
,ped.fecha_entrega
,ped.fecha_devolucion
,(SELECT CONCAT_WS(' ',UPPER(sup.ape_paterno),UPPER(sup.ape_materno),',',UPPER(sup.nombres)) FROM sunarp_usuarios sup INNER JOIN pedidos pedi ON pedi.fk_Id_usuario = sup.id_usuario where pedi.id_pedido = ped.id_pedido) as usureg
,tit.anio
,tit.mes
,tit.numero
,tit.numero_fojas
,tit.numero_planos
,tr.descripcion as tiporeg
,tom.nr_tomo

FROM pedidos ped
INNER JOIN pedidos_titulo pt ON ped.id_pedido_tit = pt.id_pedido_titulo
INNER JOIN titulos tit ON pt.fk_Id_Titulo = tit.id_titulo
INNER JOIN tipos_registro tr ON tit.fk_Id_Tipo_Registro_Titulo = tr.id_tipo_registro
INNER JOIN tomos tom ON tit.fk_Id_Tomo = tom.id_tomo

where ped.id_pedido_tit =".$_POST['idPed'];

                $res = $mysqli -> query($consultarproducto);




                if($res -> num_rows != 0){

		// convertimos el objeto
        		while($listadoOK = $res -> fetch_assoc())
        		{


                //armas los registros
        			$salida .= '


						<thead>
							<tr>
                                								<th>Datos de Titulo</th>


							</tr>
						</thead>
                        <!-- MUESTRAS RESULTADOS-->
						<tbody>


  <tr>
    <td>Fecha Entrega:</td>
    <td>'.$listadoOK['fecha_entrega'].'</td>
  </tr>
  <tr>
    <td>Fecha Devolucion</td>
    <td>'.$listadoOK['fecha_devolucion'].'</td>
  </tr>
  <tr>
    <td>Año</td>
    <td>'.$listadoOK['anio'].'</td>
  </tr>
  <tr>
    <td>Mes</td>
    <td>'.$listadoOK['mes'].'</td>
  </tr>
  <tr>
    <td>Numero</td>
    <td>'.$listadoOK['numero'].'</td>
  </tr>
  <tr>
    <td>Numero Fojas</td>
    <td>'.$listadoOK['numero_fojas'].'</td>
  </tr>
  <tr>
    <td>Numero Planos</td>
    <td>'.$listadoOK['numero_planos'].'</td>
  </tr>
  <tr>
    <td>Tipo Registro</td>
    <td>'.$listadoOK['tiporeg'].'</td>
  </tr>
   <tr>
    <td>Numero de Tomos</td>
    <td>'.$listadoOK['nr_tomo'].'</td>
  </tr>
  
  						    <tr>

						</tbody>
                        <!-- FIN-->

        			';
        		}

                                                        $respuestaOK = true;
                                                        $mensajeError = "Se ha agregado el registro correctamente";
                                                        $contenidoOK = $salida;


}






				else{
					$mensajeError = "No se puede guardar el registro en la base de CDatos";
				}
            //fin de else de validacion
			break;
			//Editar Usuario
			case 'editProd':
				// Armamos el query

				$query = sprintf("UPDATE tbl_articulos
								 SET unidades='%s', tipo_articulo=%d, codigo='%s', estado=%d, nombre_articulo='%s', precio_costo=%f, precio_venta=%f, garantia=%d, id_familia=%d, id_marca=%d, serie='%s',stock_min=%d, stock_max=%d, stock_act=%d, moneda=%d WHERE id_articulo=%d LIMIT 1",

								 $_POST['p_unid'],$_POST['p_tipo'],$_POST['p_codigo'],1,$_POST['p_nombre'],$_POST['p_costo'],$_POST['p_venta'],$_POST['p_garantia'],$_POST['p_familia'],$_POST['p_marca'],$_POST['p_serie'],$_POST['p_smin'],$_POST['p_smax'],$_POST['p_sact'],$_POST['p_moneda'],$_POST['id_Producto']);

				// Ejecutamos el query

                                

				$resultadoQuery = $mysqli -> query($query);

				// Validamos que se haya actualizado el registro
				if($mysqli -> affected_rows == 1){
					$respuestaOK = true;
					$mensajeError = 'Se ha actualizado el registro correctamente';

                        //lista el contenido actualizado consulta userfuncion que esta dentro de mainfunctions invocado arriba
					$contenidoOK = consultaProductos($mysqli);

				}else{
					$mensajeError = 'No se ha actualizado el registro';
				}


			break;

                        //Borrar Usuario
			case 'delProd':
				// Armamos el query

				$query = sprintf("DELETE FROM tbl_articulos
								  WHERE id_articulo=%d LIMIT 1",
								 $_POST['id_Producto']);

				// Ejecutamos el query



				$resultadoQuery = $mysqli -> query($query);

				// Validamos que se haya actualizado el registro
				if($mysqli -> affected_rows == 1){
					$respuestaOK = true;
					$mensajeError = 'Se ha borrado el registro correctamente';

                        //lista el contenido actualizado consulta userfuncion que esta dentro de mainfunctions invocado arriba
					$contenidoOK = consultaProductos($mysqli);

				}else{
					$mensajeError = 'No se ha borrado el registro';
				}


			break;

			default:
				$mensajeError = 'Esta acción no se encuentra disponible';
			break;
		}
	}
	else{
		$mensajeError = 'No se puede ejecutar la aplicación';
	}


}
else{
	$mensajeError = 'No se puede establecer conexión con la base de CDatos';
}

// Armamos array para convertir a JSON Y ENVIARLO AL MAINJavascript
$salidaJson = array("respuesta" => $respuestaOK,
					"mensaje" => $mensajeError,
					"contenido" => $contenidoOK);

echo json_encode($salidaJson);
?>