<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8"/>
<meta name="description" content="Jornadas de capacitación 2015, Conricyt. ">
<link rel="shortcut icon" href="favicon.ico" >
<title>CONRICYT- Capacitaciones Virtuales</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- CSS de Bootstrap -->
<link href="<?php echo base_url('css/bootstrap.min.css'); ?>" rel="stylesheet" media="screen">
<!-- librerías opcionales que activan el soporte de HTML5 para IE8 -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<link rel=stylesheet href="<?php echo base_url('css/estilos.css'); ?>" type="text/css"/>
<link href='http://fonts.googleapis.com/css?family=Scada:400,700' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="<?php echo base_url('scripts/jquery-1.11.0.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('scripts/bootstrap.min.js'); ?>"></script>
</head>
<body>
<div id="header-pleca-superior"></div>
<!-- contenedor 1-->
<div  class="container">
<!-- header logotipos -->
<div class="row">
  <div class="col-xs-2" > <a href="http://www.conricyt.mx/" target="_blank"><img id="header-logo1" src="<?php echo base_url('images/header-conricyt.png'); ?>" /></a> </div>
  <div class="col-xs-8" > <a href="http://www.conricyt.mx/" target="_blank"><img class="img-responsive img-center" id="header-logo2" src="<?php echo base_url('images/header-conricyt-text.png'); ?>" /></a> </div>
  <div class="col-xs-2" > <a href="http://www.conacyt.mx/" target="_blank"><img id="header-logo3" src="<?php echo base_url('images/header-conacyt.png'); ?>" /></a> </div>
</div>
<!--Fin  header logotipos -->
<!-- navegacion &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&-->
<div class="row">
  <nav class="navbar navbar-default" role="navigation" >
    <!-- El logotipo y el icono que despliega el menú se agrupan
       para mostrarlos mejor en los dispositivos móviles -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse"
            data-target=".navbar-ex1-collapse" style=""> Menu de navegación <span class="sr-only">Desplegar navegación</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <a class="navbar-brand" href="<?php echo base_url(); ?>"><span class="glyphicon glyphicon-home"></span></a> </div>
    <!-- Agrupar los enlaces de navegación, los formularios y cualquier
       otro elemento que se pueda ocultar al minimizar la barra -->
    <div class="collapse navbar-collapse navbar-ex1-collapse" >
      <ul class="nav navbar-nav">
        <!--<li  style=" border-left:white 1px solid;"><a href="#">Registro</a></li>-->
        <li><a href="<?php echo base_url('jornadas'); ?>">Jornadas</a></li>
        <li><a href="<?php echo base_url('carta-invitacion'); ?>">Carta invitación</a></li>
        <li><a href="http://www.conricyt.mx/" target="_blank">CONRICYT</a></li>
        <!--<li ><a href="#">Constancia</a></li>-->
      </ul>
    </div>
  </nav>
</div>
<!-- Fin navegacion &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&-->
<!-- banner principal y botones redes &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&-->
<div class="row" style=" position:relative;"  > <img id="banner-fondo" src="<?php echo base_url('images/header-banner2.png'); ?>" />
  <div id="banner-redes" >
    <div  class="banner-redes-img"  > <a href="http://blog.conricyt.mx/" target="_blank"><img src="<?php echo base_url('images/nav-redes.png'); ?>" ></a> </div>
    <div  class="banner-redes-img" > <a href="https://www.youtube.com/user/CONRICYT" target="_blank"><img src="<?php echo base_url('images/nav-redes.png'); ?>" style="left:-29px;" /></a> </div>
    <div  class="banner-redes-img" > <a href="https://twitter.com/conricyt" target="_blank"> <img src="<?php echo base_url('images/nav-redes.png'); ?>" style="left:-60px;" /></a> </div>
    <div  class="banner-redes-img"> <a href="https://www.facebook.com/CONRICYT" target="_blank"><img src="<?php echo base_url('images/nav-redes.png'); ?>" style="left:-90px;" /></a> </div>
  </div>
</div>
<!-- Fin banner principal &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&-->
<!-- barra gris formulario &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&-->
<div class="row formulario" >
  <!-- 4 login -->
  <div id="banner-login">
  	<?php
	$attr = array(
				'id'	=>	'logeo',
				'name'	=>	'logeo'
			);
	echo form_open(base_url('capacitacion/acceder'), $attr);
    echo '<div id="banner-login-2">';
	echo form_label('correo electrónico: ');
	$attr = array(
				'id'	=>	'correo_moodle',
				'name'	=>	'correo_moodle'
			);
	echo form_input($attr);
	
	$attr = array(
				'id'	=>	'enviar',
				'name'	=>	'enviar',
				'value'	=>	'Enviar'
			);
	echo form_submit($attr);
	echo '</div>';

	echo form_close();
	?>
  </div>
  <!-- 4 Fin login -->
</div>
<!-- Fin barra gris formulario &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&-->