<?php
/*
 * Template Name: Bairro Conectado - Terminal Sapopemba
 */
?>
<?php
global $wpdb;
$results = $wpdb->get_results( "SELECT * FROM bairro_conectado_opcoes");

get_header();
?>
<link rel="stylesheet" href="https://openlayers.org/en/v3.20.1/css/ol.css" type="text/css">
<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-mapa-colaborativo.css"/>
<script src="https://openlayers.org/en/v3.20.1/build/ol.js"></script>
<script src="<?php echo bloginfo('template_url'); ?>/js/plataformaMapa/plataformaMapaBairro.js"></script>
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
          <!-- <div class="BotaoApoiarVermelho BotoesMenu popmake-351" id="botoesMenuPlataforma1">
              <label class="unselectable">Apresentação</label>
          </div>
          <div class="BotaoApoiarVermelho BotoesMenu popmake-353" id="botoesMenuPlataforma3">
              <label class="unselectable">Participe</label>
          </div> -->
          <div class="BotaoApoiarVermelho BotoesMenu" id="botoesMenuPlataforma4">
              <label class="unselectable">Legenda</label>
          </div>
          <div id="containerLegenda">
            <div id="containerSubmenu">
              <ul id="legenda" class="unselectable">
                <li id="rotaA">
                  <icone style="background-color: #f94668;" class="iconCircle"></icone>
                  <label>Rota A</label>
                </li>
                <li id="rotaB">
                  <icone style="background-color: #0a3299;" class="iconCircle"></icone>
                  <label>Rota B</label>
                </li>
                <li id="rotaC">
                  <icone style="background-color: #ed7d31;" class="iconCircle"></icone>
                  <label>Rota C</label>
                </li>
                <?php
                foreach ($results as $linha) {
                  continue;
                  ?>
                  <li id="icone<?= $linha->id_opcao ?>" data-id-opcao="<?= $linha->id_opcao ?>">
                    <img src="../wp-content/uploads/2024/08/<?= $linha->id_opcao ?>.png" alt="<?= $linha->opcao ?>">
                    <label><?= $linha->opcao ?></label>
                  </li>
                  <?php
                }
                ?>
                <li id="botoes">
                  <button id="botaoEnviarMapa" type="button">Enviar</button>
                  <button type="button">Cancelar</button>
                </li>
              </ul>
            </div>
            <div id="containerDescritivo" class="hidden">
              <img id="imgDescritivo" src="" alt="">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
	<div class="clear"></div>
  <div class="clear"></div>
  <div class="loadingMapaColaborativo" id="loadingMapaColaborativo"></div>
  <script type="text/javascript" src="<?php echo bloginfo('template_url'); ?>/js/plataformaMapa/bairroconectado.js"></script>
</div>

<style>
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

  .BotaoApoiarVermelho {
    width: 250px;
  }

  div#sidenav {
    min-width: 250px;
    max-width: 650px;
  }

  .hidden {
    display: none;
  }
</style>
<?php endwhile; ?>

<?php get_footer(); ?>