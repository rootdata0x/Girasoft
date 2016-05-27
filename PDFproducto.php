<?php
 		session_start();
		require_once("dompdf/dompdf_config.inc.php");
		include('php_conexion.php'); 
		if(!$_SESSION['tipo_usu']=='a' or !$_SESSION['tipo_usu']=='ca'){
			header('location:error.php');
		}
		$can=mysql_query("SELECT * FROM empresa where id=1");
		if($dato=mysql_fetch_array($can)){
			$empresa=$dato['empresa'];
			$nit=$dato['nit'];
			$direccion=$dato['direccion'];
			$ciudad=$dato['ciudad'];
			$tel1=$dato['tel1'];
			$tel2=$dato['tel2'];
			$web=$dato['web'];
			$correo=$dato['correo'];
		}
		$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
		$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
 		$hoy=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y');
		$fech=date('d')."".$meses[date('n')-1]."".date('y');
		//Salida: Viernes 24 de Febrero del 2012

$codigoHTML=' 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Reporte</title>
<style type="text/css">
.text {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 12px;
}
</style>
</head>

<body>
<div align="center">
<table width="100%" border="0">
<caption class="text">
<strong>Listado de Productos</strong>
</caption>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td width="17"><center><img src="img/logo.png" width="114" height="65" alt="" /></center></td>
    <td width="83%" colspan="2">
      <div align="center">
        <span class="text">Soft Unicorn</span> <span class="text">Nit. 1234567899</span><br />
        <span class="text">Ciudad: Cartagena Direccion: Caracoles Manzana 36 lote 10 2da etapa. </span><br />
        <span class="text">TEL: 6679159 CEL: 3167658276</span><br />
        <span class="text">Reporte Impreso el '.$hoy.' por '.$_SESSION['username'].'</span>
      </div>
    </td>
  </tr>
</table><br />
<table width="100%" border="1">
  <tr>
    <td width="4%" bgcolor="#A4DBFF">&nbsp;</td>
    <td width="3%" bgcolor="#A4DBFF"><strong class="text">Codigo</strong></td>
    <td width="18%" bgcolor="#A4DBFF"><strong class="text">Nombre del Producto</strong></td>
    <td width="11%" bgcolor="#A4DBFF"><strong class="text">Proveedor</strong></td>
    <td width="12%" bgcolor="#A4DBFF"><strong class="text">Cod. del Proveedor</strong></td>
    <td width="8%" bgcolor="#A4DBFF"><strong class="text">Precio Costo</strong></td>
    <td width="10%" bgcolor="#A4DBFF"><strong class="text">Precio Mayoreo</strong></td>
    <td width="9%" bgcolor="#A4DBFF"><strong class="text">Precio Venta</strong></td>
    <td width="8%" bgcolor="#A4DBFF"><strong class="text">Cant. Actual</strong></td>
    <td width="8%" bgcolor="#A4DBFF"><strong class="text">Cant. Minima</strong></td>
    <td width="9%" bgcolor="#A4DBFF"><strong class="text">Seccion</strong></td>
    </tr>'; 
  	$num=0;
	$can=mysql_query("SELECT * FROM producto");	
	while($dato=mysql_fetch_array($can)){	
		$num=$num+1;
		$resto = $num%2; 
  		if ((!$resto==0)) { 
        	$color="#CCCCCC"; 
   		}else{ 
        	$color="#FFFFFF";
   		}
		$codigo=$dato['cod']; 
		$cprov=$dato['prov']; 
		$cann=mysql_query("SELECT * FROM proveedor where codigo=$cprov");	
		if($datos=mysql_fetch_array($cann)){	$n_prov=$datos['empresa'];	}

		$seccion=$dato['seccion']; 
		$cann=mysql_query("SELECT * FROM seccion where id=$seccion");	
		if($datos=mysql_fetch_array($cann)){	$n_seccion=$datos['nombre'];	}
		if (file_exists("articulo/".$codigo.".jpg")){
			$img='articulo/'.$codigo.'.jpg';
		}else{ 
			$img='articulo/producto.png';
		}
$codigoHTML.='
  <tr>
    <td bgcolor="'.$color.'"><img src="'.$img.'" width="50" height="50"></td>
    <td bgcolor="'.$color.'"><center><span class="text">'.$dato['cod'].'</span></center></td>
    <td bgcolor="'.$color.'"><span class="text">'.$dato['nom'].'</span></td>
    <td bgcolor="'.$color.'"><span class="text">'.$n_prov.'</span></td>
    <td bgcolor="'.$color.'"><span class="text">'.$dato['cprov'].'</span></td>
    <td bgcolor="'.$color.'"><span class="text">$ '.number_format($dato['costo'],2,",",".").'</span></td>
    <td bgcolor="'.$color.'"><span class="text">$ '.number_format($dato['mayor'],2,",",".").'</span></td>
    <td bgcolor="'.$color.'"><span class="text">$ '.number_format($dato['venta'],2,",",".").'</span></td>
    <td bgcolor="'.$color.'"><span class="text">'.$dato['cantidad'].'</span></td>
    <td bgcolor="'.$color.'"><span class="text">'.$dato['minimo'].'</span></td>
    <td bgcolor="'.$color.'"><span class="text">'.$n_seccion.'</span></td>
  </tr>';
  }
$codigoHTML.='
</table><br />
<div align="right"><span class="text">Registros Encontrados '.$num.'</span></div>
</div>
</body>
</html>';

$codigoHTML=utf8_decode($codigoHTML);
$dompdf=new DOMPDF();
$dompdf->load_html($codigoHTML);
ini_set("memory_limit","128M");
$dompdf->render();
$dompdf->stream("Listado_Productos_".$fech.".pdf");
?>