<?php
/*
 * Template Name: Ferramenta Colaborativa - Bordas da Cidade
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
            <br /><br />
            <div id="display">
                <center><div class="prHeaderInfo">Clique em algum ponto do mapa para colaborar.</div></center>
                <div style="padding: 2px;">&nbsp;</div>
                <div id="sidenav">
                    <div class="BotaoApoiarVermelho BotoesMenu popmake-252" id="botoesMenuPlataforma1">
                        <label class="unselectable">Apresentação</label>
                    </div>
                    <div class="BotaoApoiarVermelho BotoesMenu popmake-159" id="botoesMenuPlataforma2">
                        <label class="unselectable">Cadastre-se</label>
                    </div>
                    <div class="BotaoApoiarVermelho BotoesMenu popmake-254" id="botoesMenuPlataforma3">
                        <label class="unselectable">Participe</label>
                    </div>
                    <div class="BotaoApoiarVermelho BotoesMenu" id="botoesMenuPlataforma4">
                        <label class="unselectable">Legenda</label>
                    </div>
                    <div id="containerSubmenu" class="isHidden">
                        <ul id="legenda" class="unselectable">
                            <li>
                                <img src="../wp-content/uploads/2016/06/mspIcon.png" />
                                <label>Município de São Paulo</label>
                            </li> 
                            <li>
                                <img src="../wp-content/uploads/2016/06/zruralIcon.png" />
                                <label>Zona Rural</label>
                            </li>
                            <li>
                                <img src="../wp-content/uploads/2016/06/poloEcoIcon.png" />
                                <label>Polo Ecoturismo</label>
                            </li>
                            <li id="toggleCamadaProducao" class="legLayerInativa legClicavel">
                                <img src="../wp-content/uploads/2016/06/pictogramas_produtor.png" />
                                <label>Colaboração Produtores</label>
                            </li>
                            <li id="toggleCamadaTurismo" class="legLayerInativa legClicavel">
                                <img src="../wp-content/uploads/2016/06/pictogramas_turismo.png" />
                                <label>Colaboração Turismo</label>
                            </li>
                            <li id="toggleCamadaFeiras" class="legLayerInativa legClicavel">
                                <img src="../wp-content/uploads/2016/06/groceries.png" />
                                <label>Feiras Livres</label>
                            </li>
                            <li id="toggleCamadasPontosTuristicos" class="legLayerInativa legClicavel">
                                <img src="../wp-content/uploads/2016/06/luggage.png" />
                                <label>Pontos de Referência</label>
                            </li>
                            <li id="toggleCamadaCicloturismo" class="legLayerInativa legClicavel">
                                <img src="../wp-content/uploads/2016/06/compass.png" />
                                <label>Rotas Cicloturismo</label>
                            </li>
                        </ul>
                    </div>
                </div>
                <div id="map"></div>
                <div id="base_to_remove">
                    <li id="inserted_logos">
                        <img src="../wp-content/themes/mapacolaborativo-1.0/images/logo_agric_paulist_wide.png" alt="Agricultura Paulistana" />
                        <img src="../wp-content/themes/mapacolaborativo-1.0/images/logo_polo_ecotursp.svg" onerror="this.onerror=null; this.src='../wp-content/themes/mapacolaborativo-1.0/images/logo_polo_ecotursp.png'" alt="Polo de Ecoturismo de São Paulo" />
                    </li>
                </div>
                <div id="info"></div>
                <div id="popup" class="ol-popup">
                    <div id="popup-content"></div>
                    <div class="popup-closer-iframe red" onclick="popupClose();"></div>
                </div>
                <div id="elementClickOpenPopUp" class="elementClickOpenPopUp popmake-236"></div>
                <div id="elementClickOpenPopUp1" class="elementClickOpenPopUp popmake-241"></div>
                <div id="elementClickOpenPopUp2" class="elementClickOpenPopUp popmake-243"></div>
                <div id="elementClickOpenPopUp3" class="elementClickOpenPopUp popmake-245"></div>
            </div>
        </div>
    </div>
	<div class="clear"></div>
</div>
<?php endwhile; ?>
<script src="<?php echo bloginfo('template_url'); ?>/js/plataformaMapa/borcid.js"></script>
<?php get_footer(); ?>