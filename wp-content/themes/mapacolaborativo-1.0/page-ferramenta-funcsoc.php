<?php
/*
 * Template Name: Ferramenta Colaborativa - Função Social
 */
?>
<?php
    get_header();
?>
<link rel="stylesheet" href="https://openlayers.org/en/v3.20.1/css/ol.css" type="text/css">
<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-mapa-colaborativo.css"/>
<script src="https://openlayers.org/en/v3.20.1/build/ol.js"></script>
<script src="<?php echo bloginfo('template_url'); ?>/js/plataformaMapa/plataformaMapas.js"></script>
<script src="<?php echo bloginfo('template_url'); ?>/js/vanilla-masker.min.js"></script>
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
<div id="news-inner">
  <div class="left" style="width:100%;">
    <div class="inner floatComment" style="margin: 0px">
      <br/>
      <br/>
      <div id="display">
        <center><div class="prHeaderInfo">Clique em algum ponto do mapa demarcado para obter informações da área.</div></center>
        <div style="padding: 2px;">&nbsp;</div>
        <center><div id="map" style="height: 800px; "></div></center>
        <div id="info"></div>
        <div id="popup" class="ol-popup">
          <div id="popup-content"></div>
          <div class="popup-closer-iframe red" onclick="popupClose();"></div>
        </div>
        <div id="elementClickOpenPopUp" class="elementClickOpenPopUp popmake-134"></div>
        <div id="elementClickOpenPopUp1" class="elementClickOpenPopUp popmake-163"></div>
        <div id="sidenav">
          <div class="BotaoApoiarVermelho BotoesMenu popmake-351" id="botoesMenuPlataforma1">
              <label class="unselectable">Apresentação</label>
          </div>
          <div class="BotaoApoiarVermelho BotoesMenu popmake-353" id="botoesMenuPlataforma3">
              <label class="unselectable">Participe</label>
          </div>
          <div class="BotaoApoiarVermelho BotoesMenu" id="botoesMenuPlataforma4">
              <label class="unselectable">Legenda</label>
          </div>
          <div id="containerSubmenu" class="isHidden">
            <ul id="legenda" class="unselectable">
              <li id="msp">
                <icone style="background-color: #B3B3B3;" class="iconCircle"></icone>
                <label>MSP</label>
              </li>
              <li id="macroareasNotificacao">
                <icone style="background-color: rgb(255, 212, 214);" class="iconCircle"></icone>
                <label>Macroáreas de Notificação (art. 95, §4º do PDE)</label>
              </li>
              <li id="toggleCamadaIndicados" class="legClicavel">
                <icone style="background-color: #7E9FFF;" class="iconCircle"></icone>
                <label>Imóveis Indicados</label>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
	<div class="clear"></div>
  <div class="clear"></div>
  <div class="loadingMapaColaborativo" id="loadingMapaColaborativo"></div>
  <script type="text/javascript" src="<?php echo bloginfo('template_url'); ?>/js/plataformaMapa/funcsoc.js"></script>
</div>
<?php endwhile; ?>

<?php get_footer(); ?>