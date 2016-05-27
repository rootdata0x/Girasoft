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
		$fech=date("Ymd");
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
<div align="center" class="text">
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
		</table><br /><br />
    <table width="100%" border="1">
          <tr>
            <td colspan="6"><strong><center>Productos de Baja Existencia</center></strong></td>
          </tr>
          <tr>
            <td width="15%"><strong>Codigo</strong></td>
            <td width="26%"><strong>Descripcion del Producto</strong></td>
            <td width="13%"><div align="right"><strong>Costo</strong></div></td>
            <td width="16%"><div align="right"><strong>Venta a por Mayor</strong></div></td>
            <td width="16%"><div align="right"><strong>Valor Venta</strong></div></td>
            <td width="14%"><strong><center>Existencia</center></strong></td>
          </tr>';
        
            $mensaje='no';$num=0;
            $can=mysql_query("SELECT * FROM producto");	
            while($dato=mysql_fetch_array($can)){
                $cant=$dato['cantidad'];
                $minima=$dato['minimo'];
				$num++;
				$resto = $num%2; 
				if ((!$resto==0)) { 
					$color="#CCCCCC"; 
				}else{ 
					$color="#FFFFFF";
				}
                if($cant<=$minima){
                    $mensaje='si';
        
        $codigoHTML.='
          <tr>
            <td bgcolor="'.$color.'">'.$dato['cod'].'</td>
            <td bgcolor="'.$color.'">'.$dato['nom'].'</td>
            <td bgcolor="'.$color.'"><div align="right">$ '.number_format($dato['costo'],2,",",".").'</div></td>
            <td bgcolor="'.$color.'"><div align="right">$ '.number_format($dato['mayor'],2,",",".").'</div></td>
            <td bgcolor="'.$color.'"><div align="right">$ '.number_format($dato['venta'],2,",",".").'</div></td>
            <td bgcolor="'.$color.'"><center>'.$cant.'</center></td>
          </tr>';
         }} 
		 $codigoHTML.='
       </table>';
       
       if($mensaje=='no'){	$codigoHTML.='<div align="center"><strong>No existen articulos bajos de stock!</strong></div>'; } 
	   $codigoHTML.='
	   <br><br>

        <table width="100%" border="1">
          <tr>
            <td colspan="6"><strong><center>Listado y Totales de Productos</center></strong></td>
            </tr>
          <tr>
            <td width="15%"><strong>Codigo</strong></td>
            <td width="26%"><strong>Descripcion del Producto</strong></td>
            <td width="13%"><div align="right"><strong>Costo</strong></div></td>
            <td width="16%"><div align="right"><strong>Venta a por Mayor</strong></div></td>
            <td width="16%"><div align="right"><strong>Valor Venta</strong></div></td>
            <td width="14%"><strong><center>Existencia</center></strong></td>
          </tr>';
        
            $mensaje2='no';$costo=0;$mayor=0;$venta=0;$art=0;$num=0;
            $can=mysql_query("SELECT * FROM producto");	
            while($dato=mysql_fetch_array($can)){
                $cant=$dato['cantidad'];
                $minima=$dato['minimo'];
                $mensaje2='si';
                $art=$art+$cant;
                $costo=$costo+($dato['costo']*$dato['cantidad']);
                $mayor=$mayor+($dato['mayor']*$dato['cantidad']);
                $venta=$mayor+($dato['venta']*$dato['cantidad']);
                if($cant<=$minima){
                    $cantidad=$cant;
                }else{
                    $cantidad=$cant;
                }
				
				$num++;
				$resto = $num%2; 
				if ((!$resto==0)) { 
					$color="#CCCCCC"; 
				}else{ 
					$color="#FFFFFF";
				}
        
        $codigoHTML.='
          <tr>
            <td bgcolor="'.$color.'">'.$dato['cod'].'</td>
            <td bgcolor="'.$color.'">'.$dato['nom'].'</td>
            <td bgcolor="'.$color.'"><div align="right">$ '.number_format($dato['costo'],2,",",".").'</div></td>
            <td bgcolor="'.$color.'"><div align="right">$ '.number_format($dato['mayor'],2,",",".").'</div></td>
            <td bgcolor="'.$color.'"><div align="right">$ '.number_format($dato['venta'],2,",",".").'</div></td>
            <td bgcolor="'.$color.'"><center>'.$cantidad.'</center></td>
          </tr>';
           } 
            if($mensaje2=='2'){
          $codigoHTML.='
           <tr>
            <td colspan="6">
                    <div align="center">
                      <strong>No hay Articulos registrados actualmente</strong>
                    </div></td>
            </tr>';
          	}
			$codigoHTML.='
          <tr>
            <td colspan="2"><div align="right"><strong>Totales:</strong></div></td>
            <td><div align="right"><strong>$ '.number_format($costo,2,",",".").'</strong></div></td>
            <td><div align="right"><strong>$ '.number_format($mayor,2,",",".").'</strong></div></td>
            <td><div align="right"><strong>$ '.number_format($venta,2,",",".").'</strong></div></td>
            <td><CENTER>'.$art.'</CENTER></td>
          </tr>
        </table>
</div>
</body>
</html>';

$codigoHTML=utf8_decode($codigoHTML);
$dompdf=new DOMPDF();
$dompdf->load_html($codigoHTML);
ini_set("memory_limit","128M");
$dompdf->render();
$dompdf->stream("EstadoInventario_".$fech.".pdf");
?>