<?php 
session_start();
include('php_conexion.php');
$usuario=$_SESSION['username'];

if(!empty($_GET['xcodigo'])){$codigo=$_GET['xcodigo'];}
if(!empty($_GET['cantidad'])){$cantidad=$_GET['cantidad'];}

if(!empty($_GET['tventa'])){	
	$_SESSION['tventa']=$_GET['tventa'];	
}

if(!$_SESSION['username']==""){

	if(!empty($cantidad)){
		$cann=mysql_query("SELECT * FROM caja_tmp where cod='$codigo'");	
		if($datos=mysql_fetch_array($cann)){
			if($cantidad<>0){
				$importe=$datos['venta']*$cantidad;
				$sql="Update caja_tmp Set cant='$cantidad', importe='$importe' Where cod='$codigo'";
				mysql_query($sql);
				echo $sql;
			}
		}
	}
	
	if($_SESSION["tventa"]=='venta'){
		$cann=mysql_query("SELECT * FROM caja_tmp where usu='$usuario'");	
		while($datos=mysql_fetch_array($cann)){
			$codp=$datos['cod'];	$cant=$datos['cant'];
			$can=mysql_query("SELECT * FROM producto where cod='$codp'");	
			if($dato=mysql_fetch_array($can)){
				$valore=$dato['venta'];		$improtee=$valore*$cant;
				$sqld="Update caja_tmp Set venta='$valore', importe='$improtee' Where cod='$codp'";
				mysql_query($sqld);
			}
		}
	}
	if($_SESSION['tventa']=='mayoreo'){
		$cann=mysql_query("SELECT * FROM caja_tmp where usu='$usuario'");	
		while($datos=mysql_fetch_array($cann)){
			$codp=$datos['cod'];	$cant=$datos['cant'];
			$can=mysql_query("SELECT * FROM producto where cod='$codp'");	
			if($dato=mysql_fetch_array($can)){
				$valore=$dato['mayor'];		$improtee=$valore*$cant;
				$sqld="Update caja_tmp Set venta='$valore', importe='$improtee' Where cod='$codp'";
				mysql_query($sqld);
			}
		}
	}
}
header('location:caja.php?ddes='.$_SESSION['ddes']);
?>