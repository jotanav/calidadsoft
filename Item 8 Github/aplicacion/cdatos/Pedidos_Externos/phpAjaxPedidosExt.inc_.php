<?php
// Script para ejecutar AJAX

// Insertar y actualizar tabla de usuarios


// Inicializamos variables de mensajes y JSON
$respuestaOK = false;
$mensajeError = "No se puede ejecutar la aplicación";
$contenidoOK = "";

// Incluimos el archivo conexión a la base de CDatos
include('../mainFunctions.inc.php');

		
$statusTipo = array("1" => "btn btn-info","2" => "btn-warning","3" => "btn btn-success","4" => "btn btn-danger");
$statusTiponombre = array("1" => "Solicitado","2" => "En Proceso","3" => "Atendido","4" => "Anulado");

 $consulta = $mysqli -> query("
SELECT
 pt.id_pedido_titulo
,tit.numero
,tit.anio
,(SELECT CONCAT_WS(' ',UPPER(sup.ape_paterno),UPPER(sup.ape_materno),',',UPPER(sup.nombres)) FROM sunarp_usuarios sup INNER JOIN titulos titu ON titu.fk_usuario_propietario = sup.id_usuario where titu.id_titulo = pt.fk_Id_Titulo) as nocomp
,ped.nro_atencion
,DATE(ped.fecha_pedido) as fechapedido
,pt.estado
,tit.nombrearchivo

FROM pedidos_titulo pt
INNER JOIN titulos tit ON pt.fk_Id_Titulo = tit.id_titulo
INNER JOIN sunarp_usuarios su ON su.id_usuario = pt.fk_Id_Usuario
INNER JOIN pedidos ped ON ped.id_pedido_tit = pt.id_pedido_titulo

where id_usuario =1");
  if($consulta -> num_rows != 0){

		// convertimos el objeto
		while($listadoOK = $consulta -> fetch_assoc())
		{
		   if($listadoOK['nombrearchivo'] == "" || $listadoOK['estado'] !=3 ){
             $val = '<img src="images/nopdf.png" alt="descargar"/>';
             $ref = '#';
		   }
           else{
              $val = '<img src="images/pdf.png" alt="descargar"/>';
              $ref = 'Titulospdf'.'/'.$listadoOK['nombrearchivo'];

           }


        //armas los registros
			$salida .= '
				<tr>
                    <td>'.$listadoOK['numero'].'</td>
					<td>'.$listadoOK['anio'].'</td>
                    <td>'.$listadoOK['nocomp'].'</td>
                    <td>'.$listadoOK['nro_atencion'].'</td>
                    <td>'.$listadoOK['fechapedido'].'</td>
                    <td><span class="btn btn-mini '.$statusTipo[$listadoOK['estado']].'">'.$statusTiponombre[$listadoOK['estado']].'</span></td>
					<td id="Ver"><a class="popper" data-popbox="pop1" href="'.$listadoOK['id_pedido_titulo'].'"><img src="images/ver.png" alt="ver"/></a></td>
                    <td id="Pdf"><a href="'.$ref.'">'.$val.'</a></td>
				<tr>
			';
		}
		
														$respuestaOK = true;
                                                        $mensajeError = "Se ha agregado el registro correctamente";
                                                        $contenidoOK = $salida;

	}
	
	else{
		$salida = '
			<tr id="sinCDatos">
				<td colspan="5" class="centerTXT">NO HAY REGISTROS EN LA BASE DE CDatos</td>
	   		</tr>
		';
		$respuestaOK = true;
        $mensajeError = "Error";
        $contenidoOK = $salida;
	}
	
	
            
// Armamos array para convertir a JSON Y ENVIARLO AL MAINJavascript
$salidaJson = array("respuesta" => $respuestaOK,
					"mensaje" => $mensajeError,
					"contenido" => $contenidoOK);

echo json_encode($salidaJson);
?>