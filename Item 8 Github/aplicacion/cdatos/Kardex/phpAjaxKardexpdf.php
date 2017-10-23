<?php
// Script para ejecutar AJAX

// Insertar y actualizar tabla de usuarios


// Inicializamos variables de mensajes y JSON
//$respuestaOK = false;
//$mensajeError = "No se puede ejecutar la aplicación";
//$contenidoOK = "";

// Incluimos el archivo conexión a la base de CDatos
//include('../mainFunctions.inc.php');
                


require_once("../class/class.php");
require_once('../class.ezpdf.php');


               
                 //$id_prod = $_POST['id_prod'];

                 // $arrypost = array('id' => $id_prod);
                  $tra=new Trabajo();	

 //$str = $_POST['id_prod'];
 $fechai = $_GET['valor2'];  
 $fechaf = $_GET['valor3'];               
$reg=$tra->get_kardex($_GET['valor'],"'".$fechai."'",$fechaf);

//$statusTipoOK =  array("1" => "btn-success","0" => "btn-warning");
//$statusTiponombreOK = array("1" => "Activo","0" => "Suspendido");
 //$estado_perOK = array("2"=> "Administrador","1"=> "Usuario");
 

	// Validamos qe existan las variables post

		// Verificamos las variables de acción
                        //AQUI ESTARAN LAS FUNCIONES DE EDITAR Y BORRAR
			        $pdf =& new Cezpdf('a3');
					$pdf->selectFont('../fonts/Helvetica.afm');
			      $datacreator = array (
					'Title'=>$id_prod,
					'Author'=>'Victor Torres Villar',
					'Subject'=>'Reporte Kardex Bryam System Technology E.I.R.L',
					'Creator'=>'alextorres_v@',
					'Producer'=>'http://www.bryamsystem.com'
					);



			      $pdf->addInfo($datacreator);
				// Armamos el query
                  
			     


                   //$contenidoOK = consultaKardexProducto($mysqli,$id_prod,$fechainicio,$fechafin);

                  $statusMov = array("0" => "Salida","1" => "Entrada");
                  $concepto = array("1" => "AJUSTE DE INVENTARIO","2" => "DESPACHO","3" => "PRESTAMOS","4" => "REPARACIÓN","5" => "OTRAS SALIDAS","6" => "CAMBIO","7" => "COMPRA MERCADERIA","8" => "DEVOLUCIÓN","9" => "PRODUCCIÓN","10" => "OTRAS ENTRADAS");
				 // $concepto = array("1" => "ENVIO DE MERCADERIA","2" => "COMPRA DE MERCADERIA");
        //defines los estados del trabajador

        
	//$salida = '';
    
    //mostrar la tabla con CDatos si es que lo hay o sin CDatos

	/*$consulta = $linkDB -> query("SELECT a.fecha,a.tipo_mov,a.con_mov,e.nombre AS proveedor,d.nombre AS cliente,a.num_doc,c.codigo,b.cantidadin AS Entrada,b.cantidad AS Salida,b.stock_act,c.nombre_articulo FROM tbl_movimientos a INNER JOIN tbl_detalle_movimiento b ON a.num_doc = b.id_movimiento INNER JOIN tbl_articulos c ON b.id_articulo = c.id_articulo INNER JOIN tbl_clientes d ON  d.id_cliente = a.id_cliente LEFT JOIN tbl_proveedores e ON e.id_proveedor = a.id_prov  WHERE c.id_articulo = 28 AND a.fecha >= '2013-09-21' AND a.fecha <= '2013-09-23' ORDER BY b.fechareg ASC
");*/


	//si hay registros entonces...
 
		
		// convertimos el objeto
		/*while($listadoOK = $consulta -> fetch_array(MYSQLI_BOTH))
		{
*/

					/*		$data[]=array
					(
						
						"fecha"=>invierte_fecha($listadoOK['fecha']),
						"tipo_mov"=>utf8_decode($statusMov[$listadoOK['tipo_mov']]),
						//"concepto"=>utf8_decode($concepto[$listadoOK['con_mov']],
						//"proveedor"=>utf8_decode($listadoOK['proveedor']),
						"cliente"=>utf8_decode($listadoOK['cliente']),
						"numdoc"=>$listadoOK['num_doc'],
						"codigo"=>$listadoOK['codigo'],
						"entrada"=>$listadoOK['Entrada'],
						"salida"=>$listadoOK['Entrada'],
						"stockact"=>$listadoOK['stock_act']
					);*/


/* if (isset($_POST['id_prod'])) {
    
} */

       //$fechainicio = '2013-09-20';
	    //$fechafin = '2013-09-23';
	   

//$reg=$tra->get_kardex(28,'2013-09-20','2013-09-23');




for ($i=0;$i<sizeof($reg);$i++)
{//inicio for
							$data[] = array
							(
									
										"fecha"=>Conectar::invierte_fecha($reg[$i]['fecha']),
										"tipo_mov"=>utf8_decode($statusMov[$reg[$i]['tipo_mov']]),
										"concepto"=>utf8_decode($concepto[$reg[$i]['con_mov']]),
										"proveedor"=>utf8_decode($reg[$i]['proveedor']),
										"cliente"=>utf8_decode($reg[$i]['cliente']),
										"numdoc"=>$reg[$i]["num_doc"],
										$codigo =$reg[$i]["codigo"],
										"entrada"=>$reg[$i]["Entrada"],
										"salida"=>$reg[$i]["Salida"],
										"stockact"=>$reg[$i]["stock_act"],
                                        $nomart = $reg[$i]["nombre_articulo"]
	
							);




}

		$titles = array
	(
						"fecha"=>"<b>Fecha</b>",
						"tipo_mov"=>"<b>Movim</b>",
						"concepto"=>"<b>Concepto</b>",
						"proveedor"=>"<b>Proveedor</b>",
						"cliente"=>"<b>Destino</b>",
						"numdoc"=>"<b>NDocumento</b>",

						"entrada"=>"<b>Entrada</b>",
                        "salida"=>"<b>Salida</b>",

						"stockact"=>"<b>Stock</b>"
	);







/*$data[] = array('num'=>1, 'mes'=>'Enero');
$data[] = array('num'=>2, 'mes'=>'Febrero');
$data[] = array('num'=>3, 'mes'=>'Marzo');
$data[] = array('num'=>4, 'mes'=>'Abril');
$data[] = array('num'=>5, 'mes'=>'Mayo');
$data[] = array('num'=>6, 'mes'=>'Junio');
$data[] = array('num'=>7, 'mes'=>'Julio');
$data[] = array('num'=>8, 'mes'=>'Agosto');
$data[] = array('num'=>9, 'mes'=>'Septiembre');
$data[] = array('num'=>10, 'mes'=>'Octubre');
$data[] = array('num'=>11, 'mes'=>'Noviembre');
$data[] = array('num'=>12, 'mes'=>'Diciembre');
 
$titles = array('num'=>'<b>Numero</b>', 'mes'=>'<b>Mes</b>');*/

$options = array(
    'shadeCol'=>array(0.9,0.9,0.9),//Color de las Celdas.
    'xOrientation'=>'center',//El reporte aparecerá Centrado.
    'width'=>800//Ancho de la Tabla.
);

$pdf->ezText(utf8_decode("                                                       <b>KARDEX DE ARTÍCULOS</b>")."\n",18);
$pdf->ezText("<b>BRYAM SYSTEM TECHNOLOGY E.I.R.L</b>                      RUC : 108398493090\n",12);

$pdf->ezText(utf8_decode("<b>NOMBRE ARTÍCULO:</b> ").$nomart."        Codigo: ".$codigo."                                       Desde: ".$fechai."     Hasta:".$fechaf);
$pdf->ezText("\n");

$pdf->ezTable($data,$titles,'',$options);
$pdf->ezText("\n\n\n",10);
$pdf->ezText("<b>Documento Generado el:</b> ".date("d/m/Y"),10);

$pdf->ezStream();

		  
		//}



	/*$options = array(
              'shadeCol'=>array(0.9,0.9,0.9),//Color de las Celdas.
              'xOrientation'=>'center',//El reporte aparecerá Centrado.
              'width'=>900//Ancho de la Tabla.
            );

	$pdf->ezText("<b>Reporte Kardex por Producto</b>\n",16);	
//creamos la tabla dentro del pdf
	$pdf->ezTable($data,$titles,'',$options );
//mostramos el pdf
	$pdf->ezStream();*/

		//$respuestaOK = true;
        //$mensajeError = "Kardex ok";

	
			
	
	

// Armamos array para convertir a JSON Y ENVIARLO AL MAINJavascript
//$salidaJson = array("respuesta" => $respuestaOK,
					//"mensaje" => $mensajeError,
					//"contenido" => $contenidoOK);

//echo json_encode($salidaJson);
?>