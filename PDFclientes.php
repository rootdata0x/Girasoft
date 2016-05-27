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
<caption class="text"><strong>Listado de Usuarios/Cliente</strong></caption>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td width="17"><center><img src="img/logo.png" width="114" height="65" alt="" /></center></td>
    <td width="83%" colspan="2">
      <div align="center">
        <span class="text">'.$empresa.' Nit. '.$nit.'</span><br />
        <span class="text">Ciudad: '.$ciudad.' Direccion: '.$direccion.' </span><br />
        <span class="text">TEL: '.$tel1.' TEL2: '.$tel2.'</span><br />
        <span class="text">Reporte Impreso el '.$hoy.' por '.$_SESSION['username'].'</span>
      </div>
    </td>
  </tr>
</table><br />
<table width="100%" border="0">
  <tr>
    <td width="10%" bgcolor="#A4DBFF"><strong class="text">Documento</strong></td>
    <td width="19%" bgcolor="#A4DBFF"><strong class="text">Nombre y Apellido</strong></td>
    <td width="11%" bgcolor="#A4DBFF"><strong class="text">Telefono</strong></td>
    <td width="10%" bgcolor="#A4DBFF"><strong class="text">Celular</strong></td>
    <td width="9%" bgcolor="#A4DBFF"><strong class="text">Ciudad</strong></td>
    <td width="16%" bgcolor="#A4DBFF"><strong class="text">Direccion</strong></td>
    <td width="8%" bgcolor="#A4DBFF"><strong class="text">Usuario</strong></td>
    <td width="8%" bgcolor="#A4DBFF"><strong class="text">Estado</strong></td>
    <td width="9%" bgcolor="#A4DBFF"><strong class="text">Tipo</strong></td>
    </tr>';
 
  	$num=0;
	$can=mysql_query("SELECT * FROM usuarios order by nom");	
	while($dato=mysql_fetch_array($can)){
		if($dato['tipo']=='a'){ 
			$clase='Administrador'; 
		}elseif($dato['tipo']=='cl'){
			$clase='Cliente';
		}else{
			$clase='Cajera';
		}
		if($dato['estado']=="n"){
			$estado='Inactivo';
		}else{
			$estado='Activo';
		}	
		$num=$num+1;
		$resto = $num%2; 
  		if ((!$resto==0)) { 
        	$color="#CCCCCC"; 
   		}else{ 
        	$color="#FFFFFF";
   		}  
 
$codigoHTML.='
  <tr>
    <td bgcolor="'.$color.'"><span class="text">'.$dato['ced'].'</span></td>
    <td bgcolor="'.$color.'"><span class="text">'.$dato['nom'].'</span></td>
    <td bgcolor="'.$color.'"><span class="text">'.$dato['tel'].'</span></td>
    <td bgcolor="'.$color.'"><span class="text">'.$dato['cel'].'</span></td>
    <td bgcolor="'.$color.'"><span class="text">'.$dato['ciudad'].'</span></td>
    <td bgcolor="'.$color.'"><span class="text">'.$dato['dir'].'</span></td>
    <td bgcolor="'.$color.'"><span class="text">'.$dato['usu'].'</span></td>
    <td bgcolor="'.$color.'"><span class="text">'.$estado.'</span></td>
    <td bgcolor="'.$color.'"><span class="text">'.$clase.'</span></td>
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
$dompdf->stream("Listado_Clientes_".$fech.".pdf");

?>