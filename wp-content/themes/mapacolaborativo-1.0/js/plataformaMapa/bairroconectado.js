/**
* VARIÁVEIS PARA MANIPULAR AS FEATURES DE COLABORAÇÕES
*/
var tooltips = {
  28: "Implantar melhorias nas calçadas",
  29: "Implantar melhorias nas escadas",
  30: "Implantar melhorias nas ruas sem saída, vielas e travessas",
  31: "Melhorar a microdrenagem urbana",
  32: "Melhorar a iluminação pública",
  33: "Qualificar praças e áreas de lazer",
  34: "Implantar refúgios climáticos",
  35: "Implantar mobiliário urbano",
  36: "Implantar elementos de segurança viária",
  37: "Implantar melhorias nos acessos e transposições de barreiras físicas",
  38: "Implantar melhorias no sistema cicloviário",
  39: "Preservar, recuperar e ampliar a cobertura vegetal",
  40: "Restringir a circulação de veículos",
  41: "Incentivar a arte urbana",
}

var featuresPropostas,stylePointLayer;
jQuery(".tituloPlataforma").html("Bairro Conectado: Terminal Sapopemba");

var imageIconPropsLayer = {};
var propsLayerIndicados = {};

for (let index = 28; index <= 41; index++) {
  imageIconPropsLayer[index] = `../wp-content/uploads/2024/08/${index}.png`;
  propsLayerIndicados[index] = platMapAPI.createCustomVectorLayer('rgba(255, 255, 255, 1)', imageIconPropsLayer[index], 1, 0.8);
}

var isAjaxLoaded = false;
var isHidden = false;
/**
* FUNÇÕES PARA MANIPULAR AS FEATURES DE COLABORAÇÕES
*/
function clearAllFeatures(mapLayer){
	mapLayer.getSource().clear();
}
function showLoading(val){
    document.getElementById('loadingMapaColaborativo').style.display = val ? 'block' : 'none';
}

function objToFeature(infoFeature) {
  console.log(infoFeature);
  var lat = parseFloat(infoFeature.latlon.latitude);
  var lon = parseFloat(infoFeature.latlon.longitude);
  var index = infoFeature.index;
  console.log(lat, lon)
  var featureProposta = new ol.Feature({
      geometry: new ol.geom.Point(ol.proj.transform([lon, lat], 'EPSG:4326', 'EPSG:3857'))		
  });
	featureProposta.set("DADOS_COLAB",infoFeature);

  console.log(index)
  console.log(propsLayerIndicados[index])
        
  propsLayerIndicados[index].getSource().addFeature(featureProposta);
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

var camada1 = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/bairro_conectado/2024-08/MSP_Contorno_bairro_conectado.kml');
var c_Perimetro = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/bairro_conectado/2024-08/perimetro.kml');
var c_RotaA = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/bairro_conectado/2024-08/rota_a.kml');
var c_RotaB = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/bairro_conectado/2024-08/rota_b.kml');
var c_RotaC = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/bairro_conectado/2024-08/rota_c.kml');
var c_TerminalSapopemba = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/bairro_conectado/2024-08/terminal_sapopemba_poligono.kml');
var c_Hospitais = platMapAPI.createCustomVectorLayerFromKML('../wp-content/uploads/bairro_conectado/2024-08/hospitais.kml', 'rgba(255, 255, 255, 1)', '../wp-content/uploads/2024/08/11.png', 1, 0.6); 
var c_Ceus = platMapAPI.createCustomVectorLayerFromKML('../wp-content/uploads/bairro_conectado/2024-08/ceus.kml', 'rgba(255, 255, 255, 1)', '../wp-content/uploads/2024/08/12.png', 1, 0.6); 
var c_Metro = platMapAPI.createCustomVectorLayerFromKML('../wp-content/uploads/bairro_conectado/2024-08/terminal_sapopemba_ponto.kml', 'rgba(255, 255, 255, 1)', '../wp-content/uploads/2024/08/14.png', 1, 0.6);

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
var view = new ol.View({
  center: [-5176476.419686802, -2706742.884678815],
  zoom: 15,
  minZoom: 11.5,
  maxZoom: 22
});
var map = new ol.Map({
  layers: [
    new ol.layer.Tile({
      source: new ol.source.OSM()
    }),
    c_RotaA,
    c_RotaB,
    c_RotaC,
    propsLayerIndicados[28],
    propsLayerIndicados[29],
    propsLayerIndicados[30],
    propsLayerIndicados[31],
    propsLayerIndicados[32],
    propsLayerIndicados[33],
    propsLayerIndicados[34],
    propsLayerIndicados[35],
    propsLayerIndicados[36],
    propsLayerIndicados[37],
    propsLayerIndicados[38],
    propsLayerIndicados[39],
    propsLayerIndicados[40],
    propsLayerIndicados[41],
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
//popup Imóvel
var vectorLayerImovel = platMapAPI.createCustomVectorLayer('rgba(224, 8, 21, 1)',imageIconPropsLayer[28],0.95,0.7);
var mapImovel = new ol.Map({
  layers: [
        new ol.layer.Tile({
          source: new ol.source.OSM()
        }),vectorLayerImovel
  ],
  view: new ol.View({
        center: [-5191207.638373509,-2698731.105121977],
        zoom: 10,
        minZoom: 10,
        maxZoom: 30
    })
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
      if(features[i].get("DADOS_COLAB") != null){
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

var escolhas = [];

function adicionarPin(rota, lat, lon, opcao) {
  var idPin = escolhas.length + 1;
  escolhas.push([rota, lat, lon, opcao, idPin]);

  if (escolhas.length > 0) {
    jQuery("#containerBotaoEnviar .botaoEnviar").removeAttr("disabled");
  }

  const infoFeature = {
    latlon: {
      longitude: lon,
      latitude: lat,
    },
    index: opcao,
    id: idPin,
  }

  objToFeature(infoFeature);
  popupClose();
}

var displayFeatureInfo = function(pixel,evt) {
  var feature = getFeatureAtPixelX(pixel,map);
  var coordinate = evt.coordinate;
  var lonlat = ol.proj.transform(evt.coordinate, 'EPSG:3857', 'EPSG:4326');
  var lon = lonlat[0];
  var lat = lonlat[1];
  
  if (feature) {
    // DEBUG
    console.log("Feature: ");
    console.log(feature.get("DADOS_COLAB"));
    console.log(feature.get("DESCRICAO"));
    console.warn(feature.get('CAMADA'));
    if(feature.get("DADOS_COLAB") == null){
      var rota = feature.get("ROTA");
      var popupHtml = '<p style="margin-top: 0; text-align: left;">'
      popupHtml += feature.get('DESCRICAO');
      popupHtml += '</p>'
      if(feature.get('CAMADA') == 1 || feature.get('sg_macro_d') == "EETU"){
        popupHtml += '<div style="display: flex; flex-wrap: wrap; width: 620px;">'

        for (const index in imageIconPropsLayer) {
          popupHtml += `
            <button type="button" id="btn-estrategia-${index}" data-id-opcao="${index}" style="background: transparent; display: flex; flex: 0 0 33.3333%; height: 48px; border: none; cursor: pointer; align-items: center;" onclick="adicionarPin('${rota}', ${lat}, ${lon}, ${index})">
              <img src=${imageIconPropsLayer[index]}>
              <span style="text-align: left; margin-left: 6px;">
                ${tooltips[index]}
              </span>
            </button>`
        }

        popupHtml += '</div>';
      }
      content.innerHTML = popupHtml;
      overlay.setPosition(coordinate);
      jQuery("[id^=btn-estrategia]").hover(
        function() {
          var id = jQuery(this).attr("data-id-opcao");
          jQuery("#imgDescritivo").attr("src", `/wp-content/uploads/2024/08/frame-${id}.png`);

          jQuery("#containerDescritivo").removeClass('hidden');
      
        }, function() {
          jQuery("#containerDescritivo").addClass('hidden');
        }
      );      
    }
    else {
      return;
      setTimeout(function(){
        mapImovel.setTarget('mapaImovel');
        bindValuesImovel(feature.get("DADOS_COLAB"));
      },1000); 
      jQuery('#elementClickOpenPopUp1').click();
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
  jQuery("#modal2aFase").addClass("hidden");
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

function modal2aFase() {
  jQuery(".modalContainer").removeClass("hidden");
  jQuery("#modal2aFase").removeClass("hidden");  
}

function proximaFase() {
  window.location.href = "/bairro-conectado-terminal-sapopemba-fase-2/"
}

async function enviarFormulario() {
  fd = new FormData();
  fd.append("escolhas", JSON.stringify(escolhas));

  try {
    const res = await fetch(
      '/enviar-bairro',
      {
        method: 'POST',
        body: fd,
      },
    );

    const resData = await res.json();

    if (resData.status == 200) {
      jQuery("#mensagemEnviar").html("<b>Contribuição enviada com sucesso! Aguarde para prosseguir para a próxima fase.</b>");

      setTimeout(() => {
        proximaFase();
      }, 3000);
    } else {
      jQuery("#mensagemEnviar").html("<b>Erro no envio da contribuição, por favor tente novamente mais tarde.</b>");
      jQuery("#botoesEnviar").removeClass("hidden");
    }
  } catch (err) {
    console.error(err);
  }
}
