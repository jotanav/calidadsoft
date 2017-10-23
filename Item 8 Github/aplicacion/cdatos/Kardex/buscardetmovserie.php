<?php 

if ( !isset($_REQUEST['term']) )
    exit;

$dblink = mysql_connect('localhost', 'root', '') or die( mysql_error() );
mysql_select_db('alextorresv_alm');


$rs = mysql_query('SELECT id_articulo, nombre_articulo, codigo, stock_act  FROM tbl_articulos WHERE codigo like "'. mysql_real_escape_string($_REQUEST['term']) .'%" ORDER BY nombre_articulo asc limit 0,10', $dblink);

$data = array();
if ( $rs && mysql_num_rows($rs) )
{
    while( $row = mysql_fetch_array($rs, MYSQL_ASSOC) )
    {
        $data[] = array(
            //'label' => $row['id_articulo'] .', '. $row['nombre_articulo'] .' '. $row['serie'] ,
            //'value' => $row['id_articulo']
            'value' => $row['codigo'] ,
			'nprod' => $row['nombre_articulo'] ,
			'idp' => $row['id_articulo'],
            'nstockact' => $row['stock_act']
        );
    }
}

echo json_encode($data);
flush();

?>