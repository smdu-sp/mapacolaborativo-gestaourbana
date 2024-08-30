/**
* VARIÁVEIS PARA MANIPULAR AS FEATURES DE COLABORAÇÕES
*/
var contribuicoesEscadarias = [];

for (let indexEscadaria = 1; indexEscadaria <= 9; indexEscadaria++) {
  const obj = {
    idEscadaria: indexEscadaria,
    escolhas: new Set(),
  }

  contribuicoesEscadarias.push(obj);
}

console.log(contribuicoesEscadarias)

var featuresPropostas,stylePointLayer;
jQuery(".tituloPlataforma").html("Bairro Conectado: Terminal Sapopemba");

var isAjaxLoaded = false;
var isHidden = false;
/**
* FUNÇÕES PARA MANIPULAR AS FEATURES DE COLABORAÇÕES
*/
function clearAllFeatures(mapLayer) {
	mapLayer.getSource().clear();
}
function showLoading(val){
    document.getElementById('loadingMapaColaborativo').style.display = val ? 'block' : 'none';
}

/**
* BOTÕES LATERAIS
*/
jQuery("#botaoAjuda").on("click", function() {
  modalInstrucoes();
});
jQuery("[id^=rota]").on("click",function(){
    var id = jQuery(this).attr("id");
    zoomRota(id);
});
jQuery("[id^=melhoria]").hover(
  function() {
    var id = jQuery(this).attr("data-id-opcao");
    jQuery("#imgDescritivo").attr("src", `/wp-content/uploads/2024/08/frame-${id}.png`);

    jQuery("#containerDescritivoFase2").removeClass('hidden');

  }, function() {
    jQuery("#containerDescritivoFase2").addClass('hidden');
  }
);

var camada1 = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/bairro_conectado/2024-08/MSP_Contorno_bairro_conectado_fase_2.kml');
var c_Perimetro = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/bairro_conectado/2024-08/perimetro_fase_2.kml');
var c_RotaA = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/bairro_conectado/2024-08/rota_a_fase_2.kml');
var c_RotaB = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/bairro_conectado/2024-08/rota_b_fase_2.kml');
var c_RotaC = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/bairro_conectado/2024-08/rota_c_fase_2.kml');
var c_Escadarias = platMapAPI.createDistinctPointLayerFromKML('../wp-content/uploads/bairro_conectado/2024-08/escadarias_pontos.kml', "../wp-content/uploads/2024/08/icone-escadaria-", "png", 1, 0.75);
var c_Hospitais = platMapAPI.createCustomVectorLayerFromKML('../wp-content/uploads/bairro_conectado/2024-08/hospitais.kml', 'rgba(255, 255, 255, 1)', '../wp-content/uploads/2024/08/11.png', 1, 0.6);
var c_Ceus = platMapAPI.createCustomVectorLayerFromKML('../wp-content/uploads/bairro_conectado/2024-08/ceus.kml', 'rgba(255, 255, 255, 1)', '../wp-content/uploads/2024/08/12.png', 1, 0.6);
var c_TerminalSapopemba = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/bairro_conectado/2024-08/terminal_sapopemba_poligono.kml');
var c_Metro = platMapAPI.createCustomVectorLayerFromKML('../wp-content/uploads/bairro_conectado/2024-08/terminal_sapopemba_ponto.kml', 'rgba(255, 255, 255, 1)', '../wp-content/uploads/2024/08/14.png', 1, 0.6);

c_Escadarias.setZIndex(2);
c_TerminalSapopemba.setZIndex(1);
c_Metro.setZIndex(2);

/* HOVER POPUP */
var container = document.getElementById('popup');
var content = document.getElementById('popup-content');
var overlay = new ol.Overlay( /** @type {olx.OverlayOptions} */ ({
  element: container,
  autoPan: true,
  autoPanAnimation: {
    duration: 250
  }
}));

function popupClose() {
    overlay.setPosition(undefined);
    return false;
}

/**
* CONFIGURA MAPA PARA EXIBIÇÃO
*/
var camadaAtualRuas = true;

function toggleLayer() {
  if (camadaAtualRuas) {
    map.removeLayer(c_Ruas);
    map.addLayer(c_Satellite);
  } else {
    map.removeLayer(c_Satellite);
    map.addLayer(c_Ruas);
  }

  camadaAtualRuas = !camadaAtualRuas;

  if (camadaAtualRuas) {
    jQuery("#botaoSatelite").html("Satélite");
  } else {
    jQuery("#botaoSatelite").html("Ruas");
  }
}

var view = new ol.View({
  center: [-5176476.419686802, -2706742.884678815],
  zoom: 15,
  minZoom: 11.5,
  maxZoom: 22
});

const key = 'Get your own API key at https://www.maptiler.com/cloud/';
const attributions =
  '<a href="https://www.maptiler.com/copyright/" target="_blank">&copy; MapTiler</a> ' +
  '<a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>';

c_Ruas = new ol.layer.Tile({
  source: new ol.source.XYZ({
    attributions: attributions,
    url: 'https://api.maptiler.com/maps/streets-v2/{z}/{x}/{y}.png?key=' + key,
    tileSize: [512, 512],
    maxZoom: 22,
  }),
});
c_Satellite = new ol.layer.Tile({
  source: new ol.source.XYZ({
    attributions: attributions,
    url: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
    maxZoom: 22,
  }),
});
c_Ruas.setZIndex(-1);
c_Satellite.setZIndex(-1);

var map = new ol.Map({
  layers: [
    c_Ruas,
    c_Escadarias,
    c_RotaA,
    c_RotaB,
    c_RotaC,
    c_Hospitais,
    c_Ceus,
    c_TerminalSapopemba,
    c_Metro,
    camada1,
    c_Perimetro,
  ],
  overlays: [overlay],
  target: 'map',
  view: view
});

/**
*   IDENTIFICA FEATURE NO PONTO ONDE O CURSOR SE ENCONTRA
*/
function getFeatureAtPixelX(pixel,map){
  var features = [];
  var feature;
  map.forEachFeatureAtPixel(pixel, function(feature) {
    features.push(feature);
  });
  if(features.length>1){
    feature = features[features.length-1];
    for(var i = 0;i<features.length;i++){
      if(features[i].get("Escadaria") != null){
          feature = features[i];
      }
    }
  }
  else{
    feature = features[0];
  }
  return feature;
}

/**
*   ABRE POPUP COM INFORMAÇÕES DA FEATURE CLICADA
*/
var highlight;
function openPopupForm(){
    jQuery('#elementClickOpenPopUp').click();
}

var displayFeatureInfo = function(pixel,evt) {
  var feature = getFeatureAtPixelX(pixel,map);
  var coordinate = evt.coordinate;
  console.log(coordinate);
  var lonlat = ol.proj.transform(evt.coordinate, 'EPSG:3857', 'EPSG:4326');
  var lon = lonlat[0];
  var lat = lonlat[1];
  
  if (feature) {
    // DEBUG
    console.log("Feature: ");
    console.log(feature.get("DADOS_COLAB"));
    console.log(feature.get("DESCRICAO"));
    console.warn(feature.get('CAMADA'));
    if(feature.get("Escadaria") == null){
      var rota = feature.get("ROTA");
      var popupHtml = '<p class="tituloPopup">'
      popupHtml += feature.get('DESCRICAO');
      popupHtml += '</p>'
      content.innerHTML = popupHtml;
      overlay.setPosition(coordinate);   
    } else {
      popupClose();
      for (const obj of contribuicoesEscadarias) {
        obj['atual'] = false;
      }
      var idEscadaria = feature.get("id");
      var rotaEscadaria = feature.get("Rota");
      var numEscadaria = parseInt(feature.get("Escadaria"));
      var objEscadaria = contribuicoesEscadarias.find(x => x["idEscadaria"] == idEscadaria);
      objEscadaria['atual'] = true;
      objEscadaria['rota'] = rotaEscadaria;
      objEscadaria['numEscadaria'] = numEscadaria;
      
      jQuery("#fotoEscadaria img").attr("src", `../wp-content/uploads/2024/08/Escadaria${idEscadaria}.jpeg`);
      atualizarEstadoEscolhas();
      modalEscadarias();
    }
  }
  else {
    content.innerHTML = '&nbsp;';
    popupClose();
  }
};

/**
*   GERENCIA EVENTO DE CLIQUE
*/
map.on('singleclick', function(evt) {
  var coordinate = evt.coordinate;
  var pixelClick = map.getEventPixel(evt.originalEvent);
  displayFeatureInfo(pixelClick,evt);
  var hdms = ol.coordinate.toStringHDMS(ol.proj.transform(coordinate, 'EPSG:3857', 'EPSG:4326'));
  var feature = getFeatureAtPixelX(pixelClick,map);
  var duration = 2000;
  var start = +new Date();
  var pan = ol.animation.pan({
    duration: duration,
    source: /** @type {ol.Coordinate} */ (view.getCenter()),
    start: start
  });
});

/**
*   REDIMENSIONA ÍCONES CONFORME ZOOM
*/
map.getView().on('propertychange', function(e){
  if(e.key == "resolution"){
    var zoomAtual = map.getView().getZoom();
  }
});

function zoomRota(rota) {
  let rotas = {
    rotaA: {
      center: [-5176691.927215281, -2705986.4424095075],
      zoom: 16,
      camada: c_RotaA,
    },
    rotaB: {
      center: [-5175972.398025264, -2706185.6144031277],
      zoom: 17,
      camada: c_RotaB,
    },
    rotaC: {
      center: [-5176350.742012504, -2707134.0471588774],
      zoom: 16.2,
      camada: c_RotaC,
    },
    rotaPerimetro: {
      center: [-5176476.419686802, -2706742.884678815],
      zoom: 15,
      camada: c_Perimetro,
    },
  }

  for (const item of Object.keys(rotas)) {
    if (item !== rota && item != "rotaPerimetro" && rota !== "rotaPerimetro") {
      rotas[item]["camada"].setOpacity(0.5);
      jQuery(`#${item}`).addClass("legLayerInativa");
    } else {
      rotas[item]["camada"].setOpacity(1);
      jQuery(`#${item}`).removeClass("legLayerInativa");
    }
  }

  view.setZoom(rotas[rota]["zoom"]);
  view.setCenter(rotas[rota]["center"]);
}

function iniciarFase() {
  jQuery(".modalContainer").addClass("hidden");
  jQuery("#modalInstrucoes").addClass("hidden");
  jQuery("#modalConfirmarEnvio").addClass("hidden");
  jQuery("#modalEnviar").addClass("hidden");
  jQuery("#modalEscadarias").addClass("hidden");
  jQuery("#modalSucesso").addClass("hidden");
}

function modalInstrucoes() {
  jQuery(".modalContainer").removeClass("hidden");
  jQuery("#modalInstrucoes").removeClass("hidden");
}

function modalConfirmarEnvio() {
  jQuery(".modalContainer").removeClass("hidden");
  jQuery("#modalConfirmarEnvio").removeClass("hidden");
}

function modalEnviar() {
  jQuery(".modalContainer").removeClass("hidden");
  jQuery("#modalEnviar").removeClass("hidden");
  jQuery("#modalConfirmarEnvio").addClass("hidden");
  jQuery("#botoesEnviar").addClass("hidden");
  jQuery("#mensagemEnviar").html("<b>Enviando contribuição...</b>")
  setTimeout(() => {
    enviarFormulario();
  }, 2000)
}

function modalEscadarias() {
  jQuery(".modalContainer").removeClass("hidden");
  jQuery("#modalEscadarias").removeClass("hidden");
}

function modalSucesso() {
  jQuery("#modalEnviar").addClass("hidden");
  jQuery("#modalSucesso").removeClass("hidden");
}

function selecionarMelhoria(idOpcao) {
  var objEscadariaAtual = contribuicoesEscadarias.find(x => x["atual"]);
  var setEscolhas = objEscadariaAtual['escolhas'];
  toggleMelhoria(setEscolhas, idOpcao);

  atualizarEstadoEscolhas();
  verificarEscolhas();
}

function atualizarEstadoEscolhas() {
  var objEscadariaAtual = contribuicoesEscadarias.find(x => x["atual"]);
  var setEscolhas = objEscadariaAtual['escolhas'];
  
  for (let idOpcao = 1; idOpcao <= 10; idOpcao++) {
    jQuery(`#melhoria-${idOpcao}`).removeAttr("disabled");

    if (setEscolhas.has(idOpcao)) {
      jQuery(`#melhoria-${idOpcao}`).addClass("selecionado");
    } else {
      jQuery(`#melhoria-${idOpcao}`).removeClass("selecionado");
    }

    if (setEscolhas.size >= 3) {
      if (! setEscolhas.has(idOpcao)) {
        jQuery(`#melhoria-${idOpcao}`).attr("disabled", "disabled")
      }
    }
  }
}

function verificarEscolhas() {
  let contribuiu = false;
  jQuery("#containerBotaoEnviar .botaoEnviar").attr("disabled", "disabled");
  jQuery("#containerBotaoEnviar .botaoEnviar").attr("title", "É necessário realizar ao menos uma proposta para realizar o envio");
  
  for (obj of contribuicoesEscadarias) {
    if (obj["escolhas"].size > 0) {
      contribuiu = true;
      continue;
    }
  }
  
  if (contribuiu) {
    jQuery("#containerBotaoEnviar .botaoEnviar").removeAttr("disabled");    
    jQuery("#containerBotaoEnviar .botaoEnviar").removeAttr("title");    
  }
}

function toggleMelhoria(setEscolhas, idOpcao) {
  return setEscolhas.delete(idOpcao) || setEscolhas.add(idOpcao);
}

async function enviarFormulario() {
  var contribuicoes = [];
  for (obj of contribuicoesEscadarias) {
    if (obj["escolhas"].size > 0) {
      obj["escolhas"] = [...obj["escolhas"]].sort();
      contribuicoes.push({
        "idEscadaria": obj["idEscadaria"],
        "rota": obj["rota"],
        "escolhas": obj["escolhas"],
        "numEscadaria": obj["numEscadaria"],
      });
    }
  }
  fd = new FormData();
  fd.append("contribuicoes", JSON.stringify(contribuicoes));

  console.log("contribuicoes", contribuicoes);

  try {
    const res = await fetch(
      '/enviar-bairro-fase-2',
      {
        method: 'POST',
        body: fd,
      },
    );
    
    const resData = await res.json();

    if (resData.status == 200) {
      modalSucesso();
    } else {
      jQuery("#mensagemEnviar").html("<b>Erro no envio da contribuição, por favor tente novamente mais tarde.</b>");
      jQuery("#botoesEnviar").removeClass("hidden");
    }
  } catch (err) {
    console.error(err);
  }
}
