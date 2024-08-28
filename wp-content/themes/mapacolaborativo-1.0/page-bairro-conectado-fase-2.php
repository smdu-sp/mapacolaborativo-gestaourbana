<?php
/*
** Template Name: Bairro Conectado - Terminal Sapopemba
**/

global $wpdb;
$results = $wpdb->get_results( "SELECT * FROM bairro_conectado_fase2_opcoes" );

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
          <div class="tituloLegenda BotoesMenu" id="botoesMenuPlataforma4">
              <label class="unselectable">Legenda</label>
          </div>
          <div id="containerLegenda">
            <div id="containerSubmenu">
              <ul id="legenda" class="unselectable">
              <li id="rotaPerimetro">
                  <icone style="background-color: #000;" class="iconCircle"></icone>
                  <label>Perímetro do Projeto</label>
                </li>
                <li id="rotaA">
                  <icone style="background-color: #f94668;" class="iconCircle"></icone>
                  <label>Rota A (clique para selecionar)</label>
                </li>
                <li id="rotaB">
                  <icone style="background-color: #0a3299;" class="iconCircle"></icone>
                  <label>Rota B (clique para selecionar)</label>
                </li>
                <li id="rotaC">
                  <icone style="background-color: #ed7d31;" class="iconCircle"></icone>
                  <label>Rota C (clique para selecionar)</label>
                </li>
                <li id="terminalSapopemba">
                  <img src="../wp-content/uploads/2024/08/13.png" alt="Ícone terminal Sapopemba">
                  <label>Terminal Sapopemba</label>
                </li>
                <li id="escadarias">
                  <img src="https://maps.google.com/mapfiles/kml/pushpin/ylw-pushpin.png" alt="Ícone escadarias">
                  <label>Escadarias</label>
                </li>
                <li id="hospitais">
                  <img src="../wp-content/uploads/2024/08/11.png" alt="Ícone hospitais">
                  <label>Hospitais</label>
                </li>
                <li id="ceus">
                  <img src="../wp-content/uploads/2024/08/12.png" alt="Ícone CEUs">
                  <label>CEUs</label>
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
              </ul>
            </div>
            <div id="containerDescritivo" class="hidden">
              <img id="imgDescritivo" src="" alt="">
            </div>
          </div>
        </div>
        <div class="modalContainer">
          <div class="modal">
            <div id="modalInstrucoes">
              <p style="margin-top: 0;"><b>Queremos saber quais ações de melhoria você gostaria de sugerir para as escadarias nas rotas.</b></p>
              <div style="display: flex; flex-wrap: wrap; width: 620px;">
                <?php
                foreach ($results as $linha) {
                ?>
                  <div style="background: transparent; display: flex; flex: 0 0 33.3333%; height: 54px; border: none; padding: 5px 5px; box-sizing: border-box; align-items: center;">
                    <img src="../wp-content/uploads/2024/08/<?= $linha->id_opcao ?>.png">
                    <span style="text-align: left; margin-left: 6px;">
                      <?= $linha->descricao ?>
                    </span>
                  </div>
                <?php
                }
                ?>
              </div>
              <p><b>Passo 1:</b><br>Para cada rota, selecione a escadaria onde deseja sugerir melhorias.</p>
              <p><b>Passo 2:</b><br>Clique na foto da escadaria para marcar as ações de melhoria desejadas.</p>
              <p><b>Passo 3:</b><br>Confirme sua participação clicando em "Enviar".</p>
              <div class="centralizar">
                <button class="botoes botaoIniciar" type="button" onclick="iniciarFase()">Começar</button>
              </div>
            </div>
            <div id="modalEnviar" class="hidden">
              <p id="mensagemEnviar"><b>Enviando contribuição...</b></p>
              <div id="botoesEnviar" class="centralizar hidden">
                <button type="button" class="botoes botaoCancelar" onclick="iniciarFase()">Voltar</button>
              </div>
            </div>
            <div id="modalEscadarias" class="hidden">
              <div id="containerEscadarias">
                <div id="fotoEscadaria">
                  <img src="" alt="Foto da escadaria selecionada">
                </div>
                <div id="escolhasEscadaria">
                  <p style="margin-top: 0">
                    Escolha até 3 melhorias que esta escadaria necessita:
                  </p>
                  <div style="display: flex; flex-wrap: wrap; width: 620px; align-items: center; justify-content: space-between">
                  <?php
                  foreach ($results as $linha) {
                  ?>
                    <button id="melhoria-<?= $linha->id_opcao ?>" class="botaoEscadaria" style="display: flex; flex: 0 0 200px; height: 54px; border: none; padding: 5px 5px; box-sizing: border-box; align-items: center; cursor: pointer;" onclick="selecionarMelhoria(<?= $linha->id_opcao ?>)">
                      <img src="../wp-content/uploads/2024/08/<?= $linha->id_opcao ?>.png">
                      <span style="text-align: left; margin-left: 6px;">
                        <?= $linha->descricao ?>
                      </span>
                    </button>
                  <?php
                  }
                  ?>
                  </div>
                </div>
              </div>
              <div class="centralizar">
                <button type="button" class="botoes botaoIniciar" onclick="iniciarFase();">Confirmar</button>
              </div>
            </div>
          </div>
          </div>
        </div>
        <div id="containerBotaoEnviar">
          <button class="botoes botaoEnviar" type="button" onclick="modalEnviar()" disabled>Enviar 2ª Fase</button>
        </div>
      </div>
    </div>
  </div>
	<div class="clear"></div>
  <div class="clear"></div>
  <div class="loadingMapaColaborativo" id="loadingMapaColaborativo"></div>
  <script type="text/javascript" src="<?php echo bloginfo('template_url'); ?>/js/plataformaMapa/bairroconectadofase2.js"></script>
</div>

<?php endwhile; ?>

<style>
  .botaoEscadaria {
    background-color: transparent;
  }
  .botaoEscadaria:nth-child(n + 4) {
    margin-top: 10px;
  }

  .botaoEscadaria.selecionado {
    background-color: rgba(54, 123, 241, 0.5) ;
  }

  .botaoEscadaria:disabled {
    background-color: rgba(180, 180, 180, 0.5) !important;
  }

  #containerEscadarias {
    display: flex;
  }

  #fotoEscadaria, #fotoEscadaria img {
    height: 380px;
  }

  #fotoEscadaria {
    margin-right: 30px; 
  }
</style>