<?php
 		session_start();
		include('php_conexion.php'); 
		$usu=$_SESSION['username'];
		if(!$_SESSION['tipo_usu']=='a' or !$_SESSION['tipo_usu']=='ca'){
			header('location:error.php');
		}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Listado Producto</title>
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
<table width="100%" border="0">
  <tr>
    <td>
    <div class="btn-group" data-toggle="buttons-checkbox">
        <button type="button" class="btn btn-primary" onClick="window.location='PDFproducto.php'">Reporte PDF</button>
        <button type="button" class="btn btn-primary" onClick="window.location='crear_producto.php'">Ingresar Nuevo</button>
    </div>
    </td>
    <td>
    <div align="right">
    <form method="post" action="" enctype="multipart/form-data" name="form1" id="form1">
      <div class="input-append">
             <input name="bus" type="text" class="span2" size="60" list="characters" placeholder="Buscar">
              <datalist id="characters">
              <?php
                $buscar=$_POST['bus'];
                $can=mysql_query("SELECT * FROM producto");	
                while($dato=mysql_fetch_array($can)){
                    echo '<option value="'.$dato['nom'].'">';
                    echo '<option value="'.$dato['cod'].'">';
					echo '<option value="'.$dato['cprov'].'"';
                }
              ?>
              
               <option value="Bart">
               <option value="Fred Flinstone">
          </datalist>
            <button class="btn" type="submit">Buscar por Nombre!</button>
      </div>
    </form>
    </div>
    </td>
  </tr>
</table>
</div>
<div align="center">
<table width="80%" border="0" class="table">
  <tr class="info">
    <td colspan="7"><center><strong>Listado de Clientes / Usuarios Registrados</strong></center></td>
  </tr>
  <tr>
    <td width="5%"><strong>Codigo</strong></td>
    <td width="6%">&nbsp;</td>
    <td width="26%"><strong>Nombre del Producto</strong></td>
    <td width="7%"><strong>Estado</strong></td>
    <td width="22%"><strong>Proveedor</strong></td>
    <td width="19%"><strong>Cod. del Proveedor</strong></td>
    <td width="15%"><strong>Valor Venta</strong></td>
  </tr>
    <?php 
	if(empty($_POST['bus'])){
		$can=mysql_query("SELECT * FROM producto");
	}else{
		$buscar=$_POST['bus'];
		$can=mysql_query("SELECT * FROM producto where nom LIKE '$buscar%' or cod LIKE '$buscar%' or cprov LIKE '$buscar%'");
	}	
	while($dato=mysql_fetch_array($can)){
		$codigo=$dato['cod'];
		if($dato['estado']=="n"){
			$estado='<span class="label label-important">Inactivo</span>';
		}else{
			$estado='<span class="label label-success">Activo</span>';
		}
		$cprov=$dato['prov'];
		$cann=mysql_query("SELECT * FROM proveedor where codigo=$cprov");	
		if($datos=mysql_fetch_array($cann)){
			$n_prov=$datos['empresa'];	
		}
	?>
  <tr>
    <td>
    <?php
		if (file_exists("articulo/".$codigo.".jpg")){
			echo '<img src="articulo/'.$codigo.'.jpg" width="50" height="50">';
		}else{ 
			echo '<img src="articulo/producto.png" width="50" height="50">';
		}
	?>
    </td>
    <td><?php echo $dato['cod']; ?></td>
    <td><a href="crear_producto.php?codigo=<?php echo $dato['cod']; ?>"><?php echo $dato['nom']; ?> </a></td>
    <td><a href="php_estado_producto.php?id=<?php echo $dato['cod']; ?>"><?php echo $estado; ?></a></td>
    <td><?php echo $n_prov; ?></td>
    <td><?php echo $dato['cprov']; ?></td>
    <td>$ <?php echo number_format($dato['venta'],2,",","."); ?></td>
    </tr>
    <?php } ?>
</table>
</div>
</body>
</html>