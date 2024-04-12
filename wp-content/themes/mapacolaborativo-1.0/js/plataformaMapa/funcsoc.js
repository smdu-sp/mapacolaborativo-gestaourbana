/**
* VARIÁVEIS PARA MANIPULAR AS FEATURES DE COLABORAÇÕES
*/
var featuresPropostas,stylePointLayer;
jQuery(".tituloPlataforma").html("Mapa Colaborativo da Função Social da Propriedade");

var imageIconPropsLayer = '../wp-content/themes/mapacolaborativo-1.0/images/img-map-marker-icon.png';
var propsLayerIndicados = platMapAPI.createCustomVectorLayer('rgba(122, 172, 241, 1)',imageIconPropsLayer,0.95,0.7);
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

function carregarPropostas(featuresPropostas){
	featuresPropostas.forEach(objToFeature);	
	isHidden = false;
}
function objToFeature(infoFeature) {
    var lat = parseFloat(infoFeature.latlon.latitude);
    var lon = parseFloat(infoFeature.latlon.longitude);
        var featureProposta = new ol.Feature({
            geometry: new ol.geom.Point(ol.proj.transform([lon, lat], 'EPSG:4326', 'EPSG:3857'))		
        });
	featureProposta.set("DADOS_COLAB",infoFeature);
        
  propsLayerIndicados.getSource().addFeature(featureProposta);
	//propsLayer.getSource().addFeature(featureProposta);
}

/**
* AJAX PARA CHAMAR O SERVIÇO PARA TODAS AS PROPOSTAS APROVADAS
*/
jQuery.ajax({
	type: 'GET',
	url: '../wp-admin/admin-ajax.php',
	data: { action: 'plataformaApoio_funcaoSocial_getAllColaboracoes' },
	success: function(data){
            featuresPropostas = data;
            isAjaxLoaded = true;
            carregarPropostas(featuresPropostas);
	}
});

/*
 * Ajax para chamar o serviço de busca de endereço por lat/lon
 * 
 */
function getLogradouroLatLon(element,lat,lon){
 showLoading(true);  
 jQuery.ajax({
   type: 'GET',
   url: 'https://nominatim.openstreetmap.org/reverse',
   headers: {'X-Requested-With': 'XMLHttpRequest'},
   crossDomain: false,
   data: { format: 'json', lat:lat,lon:lon, 'accept-language': 'pt-BR' },
   success: function(data){
     showLoading(false);                
     jQuery(element).empty();
     if(data.display_name.search(',') > 10)
         jQuery(element).val(data.display_name.substring(0,data.display_name.search(',')));
     else
         jQuery(element).val(data.display_name);
   }
 });
 document.getElementById("sidenav").style.zIndex = 1;
// console.log("Done.");
}


/**
* BOTÕES LATERAIS
*/
jQuery(".BotoesMenu").on("mouseout",function(){
    jQuery(".BotoesMenu").removeClass('hoveredMenuBt');
});
jQuery("#botoesMenuPlataforma1").on("mouseover",function(){
    jQuery("#botoesMenuPlataforma1").addClass('hoveredMenuBt');
});
jQuery("#botoesMenuPlataforma2").on("mouseover",function(){
    jQuery("#botoesMenuPlataforma2").addClass('hoveredMenuBt');
});
jQuery("#botoesMenuPlataforma3").on("mouseover",function(){
    jQuery("#botoesMenuPlataforma3").addClass('hoveredMenuBt');
});
jQuery("#toggleCamadaIndicados").on("click",function(){
    platMapAPI.showHideCamada(propsLayerIndicados,jQuery("#toggleCamadaIndicados p"),"Exibir Imóveis Indicados","Imóveis Indicados");
    jQuery("#toggleCamadaIndicados input").prop("checked", !jQuery("#toggleCamadaIndicados input").prop("checked"));
});
/**
* FUNÇÕES A EXECUTAR QUANDO PÁGINA CARREGAR
*/
jQuery(document).ready(function(){
    VMasker(document.getElementById("CEP")).maskPattern('99999-999');
    /**
    * MOSTRA LEGENDAS
    */
    var isLegendaOn = false;
    var submenuHeight = jQuery('#containerSubmenu').height();
    jQuery('#containerSubmenu').height("0");
    jQuery('#containerSubmenu').removeClass("isHidden");
    jQuery('#botoesMenuPlataforma4').click(function(){
      if(isLegendaOn){
        jQuery('#containerSubmenu').animate({height: "0px"});
        isLegendaOn = false;
      }
      else {
        jQuery('#containerSubmenu').animate({height: submenuHeight});
        isLegendaOn = true;
      }
    });
    // Ativa/Oculta layers (tags: toggle layers toggle camadas)
    jQuery('#legenda > .legClicavel').click(function(){
        jQuery(this).toggleClass("legLayerInativa");
    });
});
var camada1,camada2,camada3;
var cam_OU_CENTRO,cam_OUC_AGUA_BRANCA,cam_ZEIS_2,cam_ZEIS_3,
    cam_ZEIS_5,cam_EETU_SANTO_AMARO,cam_SUB_SE_SUBMO;

camada1 = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/2016/07/MSP_Contorno.kml');

//camada2 = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/2016/07/_1_PRIORITARIA.kml');
// camada3 = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/2016/07/2_NAO_PRIORITARIA.kml');
// cam_OU_CENTRO = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/2016/08/Camadas_Etapas_AGO-2016/2014/OU_CENTRO.kml');
// cam_OUC_AGUA_BRANCA = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/2016/08/Camadas_Etapas_AGO-2016/2014/OUC_AGUA_BRANCA.kml');
// cam_ZEIS_2 = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/2016/08/Camadas_Etapas_AGO-2016/2014/ZEIS_2.kml');
// cam_ZEIS_3 = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/2016/08/Camadas_Etapas_AGO-2016/2014/ZEIS_3.kml');
// cam_ZEIS_5 = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/2016/08/Camadas_Etapas_AGO-2016/2014/ZEIS_5.kml');
// cam_EETU_SANTO_AMARO = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/2016/08/Camadas_Etapas_AGO-2016/2015/EETU_SANTO_AMARO.kml');
// cam_SUB_SE_SUBMO = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/2016/08/Camadas_Etapas_AGO-2016/2015/SUBSE_SUBMO.kml');

// Novas camadas (ADICIONADAS EM SETEMBRO DE 2020)
var c_MRVU = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/funcao_social/2024-04/MRVU.kml');
// var c_EETU = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/funcao_social/2020-09/EETU.kml');
// var c_MUC_MUQ = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/funcao_social/2020-09/MUC_MUQ.kml');
// var c_OPERACAO_URBANA_CENTRO = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/funcao_social/2020-09/OPERACAO_URBANA_CENTRO.kml');
// var c_OPERACOES_URBANAS_CONSORCIADAS = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/funcao_social/2020-09/OPERACOES_URBANAS_CONSORCIADAS.kml');
// var c_PDE_2A_SETORES_MEM = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/funcao_social/2020-09/PDE_2A_SETORES_MEM.kml');
// var c_SUB_SE_MOOCA = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/funcao_social/2020-09/SUB_SE_MOOCA.kml');
// var c_ZEIS_2 = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/funcao_social/2020-09/ZEIS_2.kml');
// var c_ZEIS_3 = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/funcao_social/2020-09/ZEIS_3.kml');
// var c_ZEIS_5 = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/funcao_social/2020-09/ZEIS_5.kml');

// Novas camadas (ADICIONADAS EM MARÇO DE 2024)
var c_MUC = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/funcao_social/2024-03/MUC.kml');
var c_MQU = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/funcao_social/2024-03/MQU.kml');
var c_MEM = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/funcao_social/2024-03/MEM.kml');

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
  center: [-5191207.638373509,-2698731.105121977],
  zoom: 11,
  minZoom: 10,
  maxZoom: 30
});
var map = new ol.Map({
  layers: [
    new ol.layer.Tile({
      source: new ol.source.OSM()
    }),
    c_MRVU,
    // c_EETU,
    // c_MUC_MUQ,
    c_MUC,
    c_MQU,
    // c_OPERACAO_URBANA_CENTRO,
    // c_OPERACOES_URBANAS_CONSORCIADAS,
    // c_PDE_2A_SETORES_MEM,
    c_MEM,
    // c_SUB_SE_MOOCA,
    // c_ZEIS_2,
    // c_ZEIS_3,
    // c_ZEIS_5,
    // cam_OU_CENTRO,
    // cam_OUC_AGUA_BRANCA,
    // cam_ZEIS_2,
    // cam_ZEIS_3,
    // cam_ZEIS_5,
    // cam_EETU_SANTO_AMARO,
    // cam_SUB_SE_SUBMO,
    // camada3,
    propsLayerIndicados,
    camada1
  ],
  overlays: [overlay],
  target: 'map',
  view: view
});
//popup Imóvel
var vectorLayerImovel = platMapAPI.createCustomVectorLayer('rgba(224, 8, 21, 1)',imageIconPropsLayer,0.95,0.7);
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
*   DESENHA PONTO NO MAPA (DO TIPO IMÓVEL)
*   RECEBE: LATITUDE, LONGITUDE
*/
function drawPointMapImovel(lat,lon){
        vectorLayerImovel.getSource().clear();
        var point = new ol.Feature({
            geometry: new ol.geom.Point(ol.proj.transform([lon, lat], 'EPSG:4326', 'EPSG:3857'))		
         });
        vectorLayerImovel.getSource().addFeature(point);
        mapImovel.getView().setZoom(15);
        mapImovel.getView().setCenter(ol.proj.transform([lon, lat], 'EPSG:4326', 'EPSG:3857'));
}

/**
*   EXIBE CAMPO PARA COMENTÁRIO
*   RECEBE: ID, ELEMENTO
*/
/*function toggleFormComment(id,element){
    setTimeout(function(){
        jQuery(formApoioComment).animate({height: 0});
        setTimeout(function(){
            jQuery("#formApoioCommentContainer").remove();
            jQuery(element).after(formApoioComment);
            jQuery(formApoioComment).height("0");
            jQuery(formApoioComment).animate({height: formApoioHeight});
            jQuery("#idColab").val(id);
        }, 200);
    },100);
}*/

/**
*   ATRIBUI INFORMAÇÕES AO IMÓVEL
*   RECEBE: DADOS DO IMÓVEL(FEATURE)
*/
function bindValuesImovel(dataFeature){
  var lat = parseFloat(dataFeature.latlon.latitude);
  var lon = parseFloat(dataFeature.latlon.longitude);
  jQuery("#caracteristicasImovel").text(dataFeature.caracteristicas);
  jQuery("#enderecoImovel").text(dataFeature.logradouro+", "+dataFeature.numero);
  jQuery("#referenciaImovel").text(dataFeature.ponto_referencia);
  jQuery("#possesImovel").text(dataFeature.possesDoImovel);
  jQuery("#tempoInutilizadoImovel").text(dataFeature.tempo_inutilizado);
  jQuery("#idFeature").val(dataFeature.id);
  showLoading(true);
  jQuery.ajax({
    type: 'POST',
    url: '../wp-admin/admin-ajax.php',
    data: { action: 'plataformaApoio_getAllComments', id:dataFeature.id,tag:1},
    success: function(data){
      showLoading(false);                
      jQuery("#commentsContainer").empty();
      jQuery("#commentsContainer").append(platMapAPI.bindValuesComments(JSON.parse(data),false));
    }
  });
  drawPointMapImovel(lat,lon);    
}

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
var displayFeatureInfo = function(pixel,evt) {
  var feature = getFeatureAtPixelX(pixel,map);
  var coordinate = evt.coordinate;
  if (feature) {
    // DEBUG
    console.log("Feature: ");
    console.log(feature.get("DADOS_COLAB"));
    console.log(feature.get("DESCRICAO"));
    console.warn(feature.get('CAMADA'));
    if(feature.get("DADOS_COLAB") == null){
      var popupHtml = feature.get('DESCRICAO');
      if(feature.get('CAMADA') == 1 || feature.get('sg_macro_d') == "EETU"){
         popupHtml += '<br><br><div class="popmake-134 red" style="cursor: pointer;" onclick="openPopupForm();">Clique aqui para marcar um imóvel.</div>';
      }
      content.innerHTML = popupHtml;
      overlay.setPosition(coordinate);
    }
    else {
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
  if(feature){
    var lonlat = ol.proj.transform(evt.coordinate, 'EPSG:3857', 'EPSG:4326');
    var lon = lonlat[0];
    var lat = lonlat[1];
    var lograField = document.getElementsByName('logradouro');
    getLogradouroLatLon(lograField,lat,lon)
    jQuery("#pinpointLatitude").val(lat);
    jQuery("#pinpointLongitude").val(lon);
  }
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
//    iconStyle.setScale(0.2 + (zoomAtual*0.12 -1)); // ALTERAR FATOR DE MULTIPLICACAO
  }
});
