<?php
/*
 * Template Name: Ferramenta Colaborativa - Planos Regionais
 */
?>
<?php
    get_header();
?>
<!-- <link rel="stylesheet" href="http://openlayers.org/en/v3.13.1/css/ol.css" type="text/css"> -->
<link rel="stylesheet" href="<?php echo bloginfo('template_url'); ?>/css/ol.css" type="text/css">
<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-mapa-colaborativo.css"/>
<!-- <script src="http://openlayers.org/en/v3.13.1/build/ol.js"></script> -->
<script src="<?php echo bloginfo('template_url'); ?>/js/ol.js"></script>
<script src="<?php echo bloginfo('template_url'); ?>/js/plataformaMapa/plataformaMapas.js"></script>
<script src="<?php echo bloginfo('template_url'); ?>/js/vanilla-masker.min.js"></script>
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
<div id="news-inner">
    <div class="left" style="width:100%;">
        <div class="inner floatComment" style="margin: 0px">
            <br /><br />
            <div id="display">
                <center><div class="prHeaderInfo">Clique no perímetro desejado para mais informações sobre o local.</div></center>
                <div style="padding: 2px;">&nbsp;</div>
                <center><div id="map" style="height: 600px; "></div></center>
                <div id="popup" class="ol-popup">
                    <div id="popup-content"></div>
                </div>
                <div id="sidenav">
                    <div class="BotaoApoiarVermelho BotoesMenu popmake-323" id="botoesMenuPlataforma1">
                        <label class="unselectable">Apresentação</label>
                    </div>
                    <div class="BotaoApoiarVermelho BotoesMenu popmake-159" id="botoesMenuPlataforma2">
                        <label class="unselectable">Cadastre-se</label>
                    </div>
                    <div class="BotaoApoiarVermelho BotoesMenu popmake-319" id="botoesMenuPlataforma3">
                        <label class="unselectable">Participe</label>
                    </div>
                    <div class="BotaoApoiarVermelho BotoesMenu" id="botoesMenuPlataforma4">
                        <label class="unselectable">Legenda</label>
                    </div>
                    <div id="containerSubmenu" class="isHidden">
                        <ul id="legenda" class="unselectable">
                            <li id="toggleBase" class="legClicavel">
                                <icone style="background-color: #ec7983;"></icone>
                                <label>Perímetros</label>
                            </li>
                            <li id="toggleCamada" class="legLayerInativa legClicavel">
                                <icone style="background-color: #FF9D3E;"></icone>
                                <label>Contribuições da população</label>
                            </li>                            
                        </ul>
                    </div>
                </div>
                <div style="padding: 4px;">&nbsp;</div>
                <div class="prHeaderInfo" style="padding-bottom: 15px">
                    <center><div id="prLineInfo" style="margin-bottom: 10px">Caso o local que procura não esteja dentro de um dos perímetros, clique no botão abaixo para fazer uma nova proposta:</div></center>
                    <center><div id="novaProposta" class="unselectable popmake-317"><label>Nova proposta</label></div></center>
                </div>
            </div>
        </div>
    </div>
    <div style="height: 1px;width:1px;position: absolute;" class="popmake-315" id="elementClickOpenPopUp1"></div>
    <div class="clear"></div>
    <div class="loadingMapaColaborativo" id="loadingMapaColaborativo"></div>
</div>
<style type="text/css">
    .pum-container {
        top: 3% !important;
        max-height: calc(100vh - 6%) !important;
        overflow: auto !important;
    }
</style>
<script src="<?php echo bloginfo('template_url'); ?>/js/plataformaMapa/planreg.js"></script>
<?php endwhile;
get_footer(); ?>