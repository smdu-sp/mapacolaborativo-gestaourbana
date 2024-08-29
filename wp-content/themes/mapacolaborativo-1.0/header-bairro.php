<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, minimumscale=1.0, maximum-scale=1.0" />
	<title>Plataforma Consulta Pública Mapas</title>
	<meta name="description" content=" Participe do planejamento de uma nova São Paulo">
	<link rel="stylesheet" type="text/css" href="<?php bloginfo( 'stylesheet_url' ); ?>?<?php echo time(); ?>"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-agenda-interna.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-agenda-sidebar.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-agenda.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-biblioteca.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-contato.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-entenda-etapas.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-entenda-introducao.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-entenda-perguntas.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-equipe.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-home.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-interna.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-noticias-interna.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-noticias-sidebar.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-noticias.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-comments.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/glDatePicker.flatwhite.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/jquery.fancybox.css"/>
	<!--script type="text/javascript" src="//misc.prefeitura.sp.gov.br/v2/startup.js"></script-->
	<!--[if lt IE 9]>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style.ie.css"/>
	<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<script type="text/javascript">
	  var template_url = "<?php echo bloginfo('template_url'); ?>";
	  var slider = 'slider';
	</script>

<?php
wp_enqueue_script('respond', get_stylesheet_directory_uri() . '/js/respond.min.js');
wp_enqueue_script('site-script', get_stylesheet_directory_uri() . '/js/script.js', array( 'jquery' ));
wp_enqueue_script('bjqs', get_stylesheet_directory_uri() . '/js/bjqs-1.3.js', array( 'jquery' ));
wp_enqueue_script('glDatePicker', get_stylesheet_directory_uri() . '/js/glDatePicker.js', array( 'jquery' ));
wp_enqueue_script('jquery.fancybox', get_stylesheet_directory_uri() . '/js/jquery.fancybox.js', array( 'jquery' ));

wp_head();

?>
</head>
<!--[if lt IE 7 ]> <body class="ie6"> <![endif]-->
<!--[if IE 7 ]> <body class="ie7"> <![endif]-->
<!--[if IE 8 ]> <body class="ie8"> <![endif]-->
<!--[if IE 9 ]> <body class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <body> <!--<![endif]-->
<!--
	<div id="asn-warning" style="display:none; position: fixed; left: 0px; border-bottom-style: solid; border-bottom-width: 1px; border-bottom-color: rgb(223, 221, 203); top: 0px; margin: 0px; width: 100%; z-index: 99999; color: #000; font-size: 10pt; padding: 0px; background-color: rgb(255, 252, 223); text-align: left; background-position: initial initial; background-repeat: initial initial;">
	    <a href="http://www.updateyourbrowser.net/" style="color: rgb(79, 77, 59); text-decoration: none; font-style: normal; font-variant: normal; font-weight: normal; font-size: 9pt; line-height: 14px; font-family: 'Trebuchet MS', Arial, Helvetica; padding-right: 30px; display: block;" target="_blank">
	        <span id="asn-outofdate" style="display: block;  color: #fff; float: left; padding: 10px 18px 10px 8px; background: #d7322f; ">Navegador desatualizado!</span>
	        <span style="display: block; padding: 10px 0 0 10px; float: left;">Para visualizar este site corretamente,</span>
	        <span style="display: block; padding: 10px 4px; float: left;text-decoration: underline;">faça a atualização.</span></a>
	</div>
	-->
	<header>
		<div id="header-banner"></div>
    </header>

	<style>
		#header-banner {
			background: url('../wp-content/uploads/2024/08/AZUL_cabecalho_consultsapublica_sapopemba_1.png') center no-repeat;
			height: 129px;
			width: 1436px;
			margin: 0 auto;
		}

		html {
			margin: 0 !important;
		}

		#containerDescritivo {
			background-color: rgba(255, 255, 255, 0.9);
			border-radius: 10px;
			margin-top: 20px;
			padding: 6px 3.5px;
			border: none;
			width: 340px;
			height: 180px;
			overflow: visible;
		}

		#containerDescritivo img {
			max-width: 100%;
			height: auto;
		}

		.descritivos {
			padding: 20px;
		}

		#containerLegenda {
			display: flex;
			flex-direction: column;
			max-width: 340px;
		}

		#containerSubmenu {
			width: 250px;
			margin: 0;
		}

		.BotoesMenu.tituloLegenda {
			width: 250px;
			background-color: #367BF1;
			display: inline-block;
			color: #FFF;
			padding: 10px 20px;
			text-decoration: none;
			box-sizing: border-box;
			font-size: 12px;
			font-weight: bold;
			border: 20px;
			max-width: 350px;
		}

		div#sidenav {
			min-width: 250px;
			max-width: 650px;
		}

		.hidden {
			display: none !important;
		}

		.modalContainer {
			position: absolute;
			top: 0;
			height: 100vh;
			width: 100vw;
			background-color: rgba(80, 80, 80, 0.7);
			z-index: 9998;
			display: flex;
			justify-content: center;
			align-items: center;
		}

		.modal {
			position: relative;
			display: inline-block;
			flex-direction: column;
			background-color: #fff;
			border-radius: 10px;
			z-index: 9999;
			margin: auto;
			vertical-align: middle;
			padding: 30px;
		}

		.centralizar {
			text-align: center;
		}

		.botoes {
			padding: 8px 16px;
			border: none;
			color: white;
			font-weight: 700;
			font-size: 20px;
			cursor: pointer;
			border-radius: 6px;
		}

		.botoes:disabled {
			opacity: 0.65;
		}

		.botoes:nth-child(n+2) {
			margin-left: 20px;
		}

		.botaoIniciar {
			background-color: #367BF1;
			;
		}

		.botaoEnviar {
			background-color: #5CD4C7;
		}

		.botaoEnviar:disabled {
			background-color: #5CD4C7;
			opacity: .4;
			cursor: default;
		}

		.botaoCancelar {
			background-color: #888;
		}

		#containerBotaoEnviar {
			position: absolute;
			left: 50%;
			transform: translateX(-50%);
			bottom: 50px;
			padding: 20px;
			z-index: 1;
			background-color: #fff;
			border-radius: 10px;
		}

		@media (min-width: 1436px) {
			div#sidenav {
				left: calc((100vw - 1436px) / 2) !important;
			}
		}

		#legenda li,
		#legenda li * {
			cursor: default !important;
		}

		#legenda li.clicavel,
		#legenda li.clicavel *,
		#botaoAjuda,
		#botaoAjuda * {
			cursor: pointer !important;
		}
	</style>
