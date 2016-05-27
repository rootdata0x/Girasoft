<?php
		session_start();
		include('php_conexion.php'); 
		$usu=$_SESSION['username'];
		if(!$_SESSION['tipo_usu']=='a' or !$_SESSION['tipo_usu']=='ca'){
			header('location:error.php');
		}
		$can=mysql_query("SELECT MAX(codigo)as numero FROM proveedor");
		if($dato=mysql_fetch_array($can)){
			$numero=$dato['numero']+1;
		}
		
		$codigo='';$contacto='';$empresa='';$ciudad='';$correo='';$direccion='';$telefono='';$celular='';$obs='';	
		if(!empty($_GET['codigo'])){	
			$numero=$_GET['codigo'];
			$can=mysql_query("SELECT * FROM proveedor where codigo=$numero");
			if($dato=mysql_fetch_array($can)){
				$empresa=$dato['empresa'];$contacto=$dato['nom'];$direccion=$dato['dir'];$ciudad=$dato['ciudad'];$telefono=$dato['tel'];$celular=$dato['cel'];$correo=$dato['correo'];$obs=$dato['obs'];$boton="Actualizar Proveedor";}}else{$boton="Guardar Proveedor";}
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Crear Proveedor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/google-code-prettify/prettify.css" rel="stylesheet">
    <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
	<script src="js/jquery.js"></script>
    <script src="js/bootstrap-transition.js"></script>
    <script src="js/bootstrap-alert.js"></script>
    <script src="js/bootstrap-modal.js"></script>
    <script src="js/bootstrap-dropdown.js"></script>
    <script src="js/bootstrap-scrollspy.js"></script>
    <script src="js/bootstrap-tab.js"></script>
    <script src="js/bootstrap-tooltip.js"></script>
    <script src="js/bootstrap-popover.js"></script>
    <script src="js/bootstrap-button.js"></script>
    <script src="js/bootstrap-collapse.js"></script>
    <script src="js/bootstrap-carousel.js"></script>
    <script src="js/bootstrap-typeahead.js"></script>
    <script src="js/bootstrap-affix.js"></script>
    <script src="js/holder/holder.js"></script>
    <script src="js/google-code-prettify/prettify.js"></script>
    <script src="js/application.js"></script>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="assets/ico/favicon.png">

</head>
<body data-spy="scroll" data-target=".bs-docs-sidebar">

  <?php 
	if(!empty($_POST['empresa']) and !empty($_POST['contacto'])){
		$codigo=$_POST['codigo'];		$contacto=$_POST['contacto'];	$empresa=$_POST['empresa'];		
		$ciudad=$_POST['ciudad'];		$correo=$_POST['correo'];		$direccion=$_POST['direccion'];
		$telefono=$_POST['telefono'];	$celular=$_POST['celular'];		$obs=$_POST['obs'];
		
		
		$can=mysql_query("SELECT * FROM proveedor where codigo=$numero");
		if($dato=mysql_fetch_array($can)){
			if($boton=='Actualizar Proveedor'){
				$xSQL="Update usuarios Set  empresa='$empresa',
											nom='$contacto',
											dir='$direccion',
											ciudad='$ciudad',
											tel='$telefono',
											cel='$celular',
											correo='$correo',
											obs='$obs' 
				
							Where codigo=$numero";
				mysql_query($xSQL);
				echo '	<div class="alert alert-success">
						  <button type="button" class="close" data-dismiss="alert">X</button>
						  <strong>Proveedor! </strong> Actualizado con Exito
					</div>';
			}		
		}else{
			$sql = "INSERT INTO proveedor (empresa, nom, dir, ciudad, tel, cel, correo, obs, estado) 
			VALUES ('$empresa','$contacto','$direccion','$ciudad','$telefono','$celular','$correo','$obs','s')";
			mysql_query($sql);	
			echo '	<div class="alert alert-success">
					  <button type="button" class="close" data-dismiss="alert">X</button>
					  <strong>Proveedor! </strong> Guardado con Exito
					</div>';
			
			$can=mysql_query("SELECT MAX(codigo)as numero FROM proveedor");
			if($dato=mysql_fetch_array($can)){
				$numero=$dato['numero']+1;
				$codigo='';$contacto='';$empresa='';$ciudad='';$correo='';$direccion='';$telefono='';$celular='';$obs='';
			}
		}
	}
?>
<div align="center">
<div class="control-group info">
<form name="form1" method="post" action="">
  <table width="80%" border="0" class="table">
  <tr class="info">
    <td colspan="2"><center><strong>Crear Proveedor</strong></center></td>
    </tr>
  <tr>
    <td>
      	<label for="textfield">Codigo: </label><input type="text" name="codigo" id="codigo" value="<?php echo $numero; ?>" readonly>
    	<label for="textfield">Empresa: </label><input type="text" name="empresa" id="empresa" value="<?php echo $empresa; ?>" required>
      	<label for="textfield">Contacto: </label><input type="text" name="contacto" id="contacto" value="<?php echo $contacto; ?>" required>
        <label for="textfield">Observacion: </label>
        <label for="textarea"></label><textarea name="obs" id="obs" cols="45" rows="5"><?php echo $obs; ?></textarea><br>
        	<button class="btn btn-large btn-primary" type="submit"><?php echo $boton; ?></button>
         	<?php if($boton=='Actualizar Proveedor'){ ?> <a href="crear_proveedor.php" class="btn">Cancelar</a><?php } ?>

    </td>
    <td>
    	<label for="textfield">Direccion: </label><input type="text" name="direccion" id="direccion" value="<?php echo $direccion; ?>" required>
        <label for="textfield">Ciudad: </label><input type="text" name="ciudad" id="ciudad" value="<?php echo $ciudad; ?>" required>
        <label for="textfield">Telefono: </label><input type="text" name="telefono" id="telefono" value="<?php echo $telefono; ?>" >
        <label for="textfield">Celular: </label><input type="text" name="celular" id="celular" value="<?php echo $celular; ?>" >
        <label for="textfield">Correo: </label><input type="text" name="correo" id="correo" value="<?php echo $correo; ?>" >
    </td>
  </tr>
</table>
</form>
</div>
</div>
</body>
</html>