<?php
/*
 * Template Name: Bairro Conectado Fase 2 - Terminal Sapopemba
 */

global $wpdb;
$results = $wpdb->get_results( "SELECT * FROM bairro_conectado_opcoes WHERE fase=2" );

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
        <div id="sidenav" class="sidenavBairroConectado">
          <div class="tituloLegenda BotoesMenu" id="botaoAjuda">
              <label>Instruções</label>
          </div>
          <div class="tituloLegenda BotoesMenu" id="botoesMenuPlataforma4">
              <label class="unselectable">Legenda</label>
          </div>
          <div id="containerLegenda">
            <div id="containerSubmenu">
              <ul id="legenda" class="unselectable">
                <li id="rotaPerimetro" class="clicavel" style="display: flex;">
                  <icone style="background-color: #000; width: 15px; flex-shrink: 0; line-height: 0;" class="iconCircle"></icone>
                  <div>
                    <label>Perímetro do Projeto (clique para centralizar no mapa)</label>
                  </div>
                </li>
                <li id="rotaA" class="clicavel" >
                  <icone style="background-color: #FA4569;" class="iconCircle"></icone>
                  <label>Rota A (clique para selecionar)</label>
                </li>
                <li id="rotaB" class="clicavel" >
                  <icone style="background-color: #527AED;" class="iconCircle"></icone>
                  <label>Rota B (clique para selecionar)</label>
                </li>
                <li id="rotaC" class="clicavel" >
                  <icone style="background-color: #F26E14;" class="iconCircle"></icone>
                  <label>Rota C (clique para selecionar)</label>
                </li>
                <li id="terminalSapopemba">
                  <icone style="background-color: #0A3399;" class="iconCircle"></icone>
                  <label>Terminal Sapopemba</label>
                </li>
                <li id="metro">
                  <img src="../wp-content/uploads/2024/08/14.png" alt="Ícone metro">
                  <label>Estação Sapopemba do Metrô</label>
                </li>
                <li id="escadariasA">
                  <img src="../wp-content/uploads/2024/08/EscadariasA.png" alt="Ícone escadarias da rota A">
                  <label>Escadarias da Rota A</label>
                </li>
                <li id="escadariasB">
                  <img src="../wp-content/uploads/2024/08/EscadariasB.png" alt="Ícone escadarias da rota B">
                  <label>Escadarias da Rota B</label>
                </li>
                <li id="escadariasC">
                  <img src="../wp-content/uploads/2024/08/EscadariasC.png" alt="Ícone escadarias da rota C">
                  <label>Escadarias da Rota C</label>
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
          </div>
        </div>
        <div class="modalContainer">
          <div class="modal">
            <div id="modalInstrucoes">
              <p class="tituloModal"><b>Queremos saber quais ações de melhoria você gostaria de sugerir para as escadarias nas rotas.</b></p>
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
              <p class="instrucoes"><b>Passo 1:</b><br>Para cada rota, selecione as escadarias onde deseja sugerir melhorias.</p>
              <p class="instrucoes"><b>Passo 2:</b><br>Clique no ícone da escadaria para marcar as ações de melhoria desejadas. <br>Você pode propor melhorias em quantas escadarias quiser.</p>
              <p class="instrucoes"><b>Passo 3:</b><br>Confirme sua participação clicando em "Enviar Proposta".</p>
              <div class="centralizar">
                <button class="botoes botaoIniciar" type="button" onclick="iniciarFase()">Começar</button>
              </div>
            </div>
            <div id="modalConfirmarEnvio" class="hidden">
              <p class="tituloPopup"><b>Tem certeza que não deseja fazer mais nenhuma proposta de melhoria, e prosseguir com o envio?</b></p>
              <div class="centralizar">
                <button type="button" class="botoes botaoIniciar" onclick="modalEnviar()">Enviar</button>
                <button type="button" class="botoes botaoCancelar" onclick="iniciarFase()">Voltar</button>
              </div>
            </div>
            <div id="modalEnviar" class="hidden">
              <p class="tituloPopup" id="mensagemEnviar"><b>Enviando contribuição...</b></p>
              <div id="botoesEnviar" class="centralizar hidden">
                <button type="button" class="botoes botaoCancelar" onclick="iniciarFase()">Voltar</button>
              </div>
            </div>
            <div id="modalSucesso" class="hidden">
              <p class="tituloPopup"><b>Contribuição enviada com sucesso!</b></p>
              <div class="centralizar">
                <button type="button" class="botoes botaoIniciar" onclick="window.location.href = 'https://gestaourbana.prefeitura.sp.gov.br/projetos-urbanos/bairro-conectado-terminal-sapopemba/'">Encerrar</button>
              </div>
            </div>
            <div id="modalEscadarias" class="hidden">
              <div id="containerBannerEscadarias" style="margin-bottom: 20px;">
                <img src="/wp-content/uploads/2024/08/banner_escadarias.png"  style="max-width: 900px;" alt="Consideramos que a escadaria deve ter, no mínimo: piso seguro; corrimão; drenagem eficiente; iluminação; sinalização; lixeira; e trilho para bicicleta. Além dessas ações, escolha até 3 melhorias que esta escadaria necessita:">
              </div>
              <div id="containerEscadarias">
                <div id="fotoEscadaria">
                  <img src="" alt="Foto da escadaria selecionada">
                </div>
                <div id="escolhasEscadaria">
                  <div style="display: flex; flex-wrap: wrap; width: 620px; align-items: center; justify-content: space-between">
                  <?php
                  foreach ($results as $linha) {
                  ?>
                    <button id="melhoria-<?= $linha->id_opcao ?>" data-id-opcao="<?= $linha->id_opcao ?>" class="botaoEscadaria" style="display: flex; flex: 0 0 200px; height: 54px; border: none; padding: 5px 5px; box-sizing: border-box; align-items: center; cursor: pointer;" onclick="selecionarMelhoria(<?= $linha->id_opcao ?>)">
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
                <button type="button" class="botoes botaoCancelar" onclick="fecharPopupEscadarias()">Cancelar</button>
              </div>
              <div id="containerDescritivoFase2" class="hidden">
                <img id="imgDescritivo" src="" alt="">
              </div>
            </div>
            <div id="modalConfirmarCancelamento" class="hidden">
              <p class="tituloPopup"><b>Tem certeza que deseja descartar as seleções realizadas para esta escadaria?</b></p>
              <div class="centralizar">
                <button type="button" class="botoes botaoIniciar" onclick="descartarEscolhas()">Descartar</button>
                <button type="button" class="botoes botaoCancelar" onclick="modalEscadarias()">Voltar</button>
              </div>
            </div>
          </div>
          </div>
        </div>
        <div id="containerBotaoEnviar">
          <button class="botoes botaoEnviar" type="button" onclick="modalConfirmarEnvio()" disabled title="É necessário realizar ao menos uma proposta para realizar o envio">Enviar Propostas</button>
        </div>
        <div id="containerSatelite">
          <button id="botaoSatelite" onclick="toggleLayer()">Satélite</button>
        </div>
        <a id="logoMaptiler" href="https://www.maptiler.com/"><img src="https://api.maptiler.com/resources/logo.svg" alt="MapTiler logo"></a>
      </div>
    </div>
  </div>
	<div class="clear"></div>
  <div class="clear"></div>
  <div class="loadingMapaColaborativo" id="loadingMapaColaborativo"></div>
  <script type="text/javascript" src="<?php echo bloginfo('template_url'); ?>/js/plataformaMapa/bairroconectadofase2.js"></script>
</div>

<?php endwhile; ?>
