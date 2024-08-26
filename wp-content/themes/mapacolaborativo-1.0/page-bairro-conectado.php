<?php
/*
 * Template Name: Bairro Conectado - Terminal Sapopemba
 */
?>
<?php
global $wpdb;
$results = $wpdb->get_results( "SELECT * FROM bairro_conectado_opcoes");

get_header('bairro');
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
      <div id="display"><div id="map" style="height: calc(100vh - 129px); max-width: 1436px; margin: 0 auto; box-sizing: border-box; float: none;"></div>
        <div id="popup" class="ol-popup">
          <div id="popup-content"></div>
          <div class="popup-closer-iframe red" onclick="popupClose();"></div>
        </div>
        <div id="elementClickOpenPopUp" class="elementClickOpenPopUp popmake-134"></div>
        <div id="elementClickOpenPopUp1" class="elementClickOpenPopUp popmake-163"></div>
        <div id="sidenav">
          <!-- <div class="tituloLegenda BotoesMenu popmake-351" id="botoesMenuPlataforma1">
              <label class="unselectable">Apresentação</label>
          </div>
          <div class="tituloLegenda BotoesMenu popmake-353" id="botoesMenuPlataforma3">
              <label class="unselectable">Participe</label>
          </div> -->
          <div class="tituloLegenda BotoesMenu" id="botoesMenuPlataforma4">
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
        <div class="modalContainer">
          <div class="modal">
            <p style="margin-top: 0;"><b>Queremos saber quais ações de melhoria você gostaria de sugerir em cada rota.</b></p>
            <div style="display: flex; flex-wrap: wrap; width: 620px;">
              <?php
              foreach ($results as $linha) {
              ?>
                <div style="background: transparent; display: flex; flex: 0 0 33.3333%; height: 54px; border: none; padding: 5px 5px; box-sizing: border-box">
                  <img src="../wp-content/uploads/2024/08/<?= $linha->id_opcao ?>.png">
                  <span style="text-align: left; margin-left: 6px;">
                    <?= $linha->descricao ?>
                  </span>
              </div>
              <?php
              }
              ?>
            </div>
            <p><b>Passo 1:</b><br>Escolha uma rota no mapa.</p>
            <p><b>Passo 2:</b><br>Clique no mapa para marcar onde você acha que as melhorias devem ser feitas.</p>
            <p><b>Passo 3:</b><br>Confirme sua participação clicando em "Enviar".</p>
            <div class="centralizar">
            <button class="botoes botaoIniciar" type="button" onclick="iniciarFase()">Começar</button>
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

  .tituloLegenda {
    width: 250px;
    background-color: #14B0F2;
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

  .botaoIniciar {
    background-color: #14B0F2;
  }

  @media (min-width: 1436px) {
    div#sidenav {
      left: calc((100vw - 1436px) / 2);
    }
  }
</style>
<?php endwhile; ?>