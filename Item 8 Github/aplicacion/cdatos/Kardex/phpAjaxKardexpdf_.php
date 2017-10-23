<?php
require_once("../dompdf/dompdf_config.inc.php");
include('../mainFunctions.inc.php');
$texto=consultaKardexProducto($mysqli,28,'2013-09-20','2013-09-23');
$html= '
<html>
<body></body>
<table>
$texto
</table>
</html>
';
/*
'
<html>
<body>
<h1 align=center >Reporte de Kardex por Producto</h1>
BRYAM SYSTEM TECHNOLOGY E.I.R.L
<hr />
<table border="1">
            <thead>
              <tr>
              
                                                                <th>Fecha</th>
                                                                <th>Movim</th>
                                                                
                                                                <th>Concepto</th>
                                                                <th>Proveedor</th>
                                                               
                                                                <th>Destino / Cliente</th>
                                                                <th>N° Documento</th>
                                                                <th>Codigo</th>
                                                                <th>Entrada</th>
                                                                <th>Salida</th>

                                                                <th>Stock</th>
                                                                
              </tr>
            </thead>

            <tbody id="listaKarexOKpdf">
                                                  
              
            </tbody>
  </table> 
  </body> 
  </html>        
';*/
//echo $html;
$html = utf8_decode($html);
$dompdf = new DOMPDF();

$dompdf->load_html($html);
$dompdf->render();
$dompdf->stream("prueba.pdf");
?>
