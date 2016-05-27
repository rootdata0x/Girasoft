<?php 

		session_start();
		include('php_conexion.php'); 
		$usuario=$_SESSION['username'];
		if(!$_SESSION['tipo_usu']=='a' or !$_SESSION['tipo_usu']=='ca'){
			header('location:error.php');
		}
		
		$can=mysql_query("SELECT MAX(factura) as maximo FROM factura");//codigo de la factura	
        if($dato=mysql_fetch_array($can)){	$cfactura=$dato['maximo']+1;	}
		if($cfactura==1){$cfactura=1000;}//si es primera factura colocar que empieze en 1000
		$hoy=$fechay=date("Y-m-d");
		
		if($_GET['button']=='Cobrar Dinero Recibido'){ //contado
			$ccpago=$_GET['ccpago'];
			$tpagar=$_GET['tpagar'];
			$t_importe=0;
			if($tpagar<=$ccpago){
				//guarda tabla factura
				$factura_sql="INSERT INTO factura (factura, cajera, fecha, estado) VALUES ('$cfactura','$usuario','$hoy','s')";
				mysql_query($factura_sql);	
				//codigo de la factura / guarda en detalles
				$can=mysql_query("SELECT * FROM caja_tmp where usu='$usuario'");	
				while($dato=mysql_fetch_array($can)){
					$cod=$dato['cod'];			$nom=$dato['nom'];			$cant=$dato['cant'];
					$venta=$dato['venta'];		$importe=$dato['importe'];	$t_importe=$t_importe+$importe;
					
					$detalle_sql="INSERT INTO detalle (factura, codigo, nombre, cantidad, valor, importe, tipo)
							VALUES ('$cfactura','$cod','$nom','$cant','$venta','$importe','CONTADO')";
					mysql_query($detalle_sql);
					////ACTUALIZAR LA EXISTENCIA//////////////////
					$ca=mysql_query("SELECT * FROM producto where cod='$cod'");	
					if($date=mysql_fetch_array($ca)){
						$e_actual=$date['cantidad'];
					}
					$n_cantidad=$e_actual-$cant;
					if($n_cantidad<0){	$n_cantidad=0;	}// si la cantidad da negativo ponerlo en 0
					$sql="Update producto Set cantidad='$n_cantidad' Where cod='$cod'";
					mysql_query($sql);	
					/////////////////////////////////////////////
				}
								
				$borrar_sql="DELETE FROM caja_tmp WHERE usu='$usuario'";//borrar todo de la caja temporal
				mysql_query($borrar_sql);
				
				header('location:contado.php?tpagar='.$tpagar.'&ccpago='.$ccpago.'&factura='.$cfactura);
			}else{
				header('location:contado.php?mensaje=error');
			}
		}
		$_SESSION['ddes']=0;
		
?>