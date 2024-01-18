/**
*   DECLARAÇÃO DE VARIÁVEIS
*/
var highlight;
var propsLayer;
var vectorLayer;
var cPropsLayer;
var clusterLayer;
var clusterSource;
var perimetrosInfo;
var featuresPropostas;
var isHidden = false;
var isAjaxLoaded = false;
//var formApoioComment = jQuery("#formApoioCommentContainer");
var formApoioHeight = 140;
var stylePropsLayer = new ol.style.Style({
    fill: new ol.style.Fill({
        color: 'rgba(255, 69, 0, 0.4)'
    }),
    stroke: new ol.style.Stroke({
      color: 'rgba(128, 159, 255, 1)',
      width: 3
    }),
    image: new ol.style.Icon({
        anchor: [0.5, 46],
        anchorXUnits: 'fraction',
        anchorYUnits: 'pixels',
        opacity: 0.95,
        scale: 0.7,
        color: 'rgba(255, 69, 0, 1)',                   
        src: '../wp-content/themes/mapacolaborativo-1.0/images/img-map-marker-icon.png'
    })
});
//jQuery("#formApoioCommentContainer").remove();
jQuery(".tituloPlataforma").html("Mapa Colaborativo dos Planos Regionais das Subprefeituras");

/**
*   EXIBE TELA DE LOADING
*/
function showLoading(val){
    document.getElementById('loadingMapaColaborativo').style.display = val ? 'block' : 'none';
}

/**
*   OCULTA CONTRIBUIÇÕES DA POPULAÇÃO
*/
function ocultaPropostas(){
    platMapAPI.showHideCamada([propsLayer, clusterLayer], "#toggleCamada p", "Exibir contribuições da população","Ocultar contribuições da população");    
}

/**
*   OCULTA CAMADA DE PERÍMETROS
*/
function ocultaKML(){
    platMapAPI.showHideCamada(vectorLayer, "#toggleBase p", "Exibir perímetros","Ocultar perímetros");
}
showLoading(true);		
jQuery.ajax({
	type: 'GET',
	url: "../wp-admin/admin-ajax.php",
	data: { action: 'plataformaApoio_getAllPropostas' },
	success: function(data){
		featuresPropostas = JSON.parse(data).propostas;
		isAjaxLoaded = true;
		carregarPropostas();
		ocultaPropostas();
	}
});
jQuery.ajax({
	type: 'GET',
	url: "../wp-admin/admin-ajax.php",
	data: { action: 'planosRegionais_getAllPerimetros' },
	success: function(data){
		perimetrosInfo = JSON.parse(data);
	}
});

/**
*   EXIBE FEATURES NO MAPA
*/
function carregarPropostas(){
	featuresPropostas.forEach(objToFeature);
    var cFeatures = cPropsLayer.getSource().getFeatures();
    cFeatures = platMapAPI.removeDoubles(cFeatures);
    clusterLayer = platMapAPI.createClusterFromFeatures(cFeatures, {fillColor: '#f50', strokeColor: 'rgba(128, 159, 255, 1)'});
    var layerList = map.getLayers();
    layerList.insertAt(1,clusterLayer);
}

/**
*   PREPARA OBJETO E INSERE COMO FEATURE NA DEVIDA CAMADA
*/
function objToFeature(feature) {
	var featureProposta = (new ol.format.GeoJSON()).readFeature(feature.coords.feature);
	featureProposta.set("ID",feature.id);
	featureProposta.set("NOME",feature.titulo);
	featureProposta.set("AUTHOR_NAME",feature.autor.nome);
	featureProposta.set("COLABORACAO",feature.colaboracao);
	featureProposta.set("SUBMITTED",feature.submitted);
    if(featureProposta.getGeometry().getType() == "Point")
        cPropsLayer.getSource().addFeature(featureProposta);
    else 
        propsLayer.getSource().addFeature(featureProposta);
}

/**
*   RECEBE INFORMAÇÕES DO PERÍMETRO
*/
function getPerimetroInfoById(id){
    for(var i = 0;i<perimetrosInfo.length;i++){
        if(perimetrosInfo[i].ID == id)
            return perimetrosInfo[i];
    }
}

/**
*   EXIBE CAMPO PARA COMENTÁRIO

function toggleFormComment(id,element){
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
*   DEFINE CLASSES PARA ANIMAÇÕES DOS BOTÕES DE MENU
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
jQuery("#botoesMenuPlataforma4").on("mouseover",function(){
    jQuery("#botoesMenuPlataforma4").addClass('hoveredMenuBt');
});

/**
*   GERENCIA FUNCIONALIDADES DOS BOTÕES 'EXIBIR CONTRIBUIÇÕES' E 'OCULTAR PERÍMETROS'
*/
jQuery("#toggleCamada").on("click",function(){
    ocultaPropostas();
	jQuery("#toggleCamada input").prop("checked", !jQuery("#toggleCamada input").prop("checked"));
});
jQuery("#toggleBase").on("click",function(){
    ocultaKML();
    jQuery("#toggleBase input").prop("checked", !jQuery("#toggleBase input").prop("checked"));
})

/**
*   CRIA UMA SOBREPOSIÇÃO PARA ANCORAR A POPUP NO MAPA
*/
var container = document.getElementById('popup');
var content = document.getElementById('popup-content');
var overlay = new ol.Overlay({
	element: container,
	autoPan: true,
	autoPanAnimation: {duration: 250}
});


/**
*   GERENCIA CLIQUE PARA FECHAR POPUP
*/
function popupClose() {
    overlay.setPosition(undefined);
    return false;
}

/**
*   DEFINE CAMADAS DO MAPA
*/
cPropsLayer = new ol.layer.Vector({
    name: 'cPropsLayer',
    source: new ol.source.Vector()
});
propsLayer = new ol.layer.Vector({
	name: 'propsLayer',
	source: new ol.source.Vector(),
	style: stylePropsLayer
});
vectorLayer = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/2016/06/PRS_OFICINAS_160627.kml');

/**
*   CONFIGURA MAPA
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
  propsLayer,vectorLayer
],
overlays: [overlay],
target: 'map',
view: view
});
var vectorLayerInformacoes = new ol.layer.Vector({
        name: 'vectorLayerInformacoes',
        source: new ol.source.Vector(),
        style: stylePropsLayer,
});

var mapInformacoes = new ol.Map({
    layers: [
          new ol.layer.Tile({
            source: new ol.source.OSM()
          }),vectorLayerInformacoes
    ],
    view: new ol.View({
          center: [-5191207.638373509,-2698731.105121977],
          zoom: 10,
          minZoom: 10,
          maxZoom: 30
      })
});

/**
*   VERIFICA SE KML JÁ FOI CARREGADO
*/
var kmlFile = vectorLayer.getSource();
var key = kmlFile.on('change', function() {
    if (kmlFile.getState() == 'ready') {
        showLoading(false);
    }
});

/**
*   CONFIGURA MAPA DE NOVAS PROPOSTAS
*/
jQuery(document).ready(function(){
    VMasker(document.getElementById("CEP")).maskPattern('99999-999');
    var vector_layer_mapProposta = new ol.layer.Vector({
    	name: 'vector_layer_mapProposta',
    	source: new ol.source.Vector(),
    	style: stylePropsLayer,
    });
    // CRIA O MAPA
    var mapProposta = new ol.Map({
      layers: [
        new ol.layer.Tile({
          source: new ol.source.OSM()
        }),
        vector_layer_mapProposta
      ],
      view: new ol.View({
        center: [-5191207.638373509,-2698731.105121977],
        zoom: 12,
        minZoom: 10,
        maxZoom: 30
      })
    });
    // DECLARA AS VARIAVEIS GLOBAIS
    var select_interaction,
        draw_interaction,
        modify_interaction,
        featureComplete;	
        featureComplete = false;				
    // ESCOLHE O TIPO DE GEOMETRIA (PONTO OU POLIGONO)
    var $geom_type = jQuery('#geom_type');
    jQuery("#pinpointLocationContainer").css('display', 'none');
    // RECONSTROI INTERACAO QUANDO ALTERA O TIPO
    $geom_type.on('change', function(e) {
        mapProposta.removeInteraction(draw_interaction);
        addDrawInteraction();
        jQuery("#pinpointLocationContainer").css('display', 'none');  
    });
    /**
    *   CRIA A INTERAÇÃO DRAW
    */
    function addDrawInteraction() {
            // REMOVE OUTRAS INTERACOES
            mapProposta.removeInteraction(select_interaction);
            mapProposta.removeInteraction(modify_interaction);  
            // CRIA A INTERACAO
            draw_interaction = new ol.interaction.Draw({
                source: vector_layer_mapProposta.getSource(),
                type: /** @type {ol.geom.GeometryType} */ ($geom_type.val())
            });
            // ADICIONA AO MAPA
            mapProposta.addInteraction(draw_interaction);
            // QUANDO A FEATURE FOR DESENHADA...
            draw_interaction.on('drawend', function(event) {
            // CRIA UMA ID
            var id = uid();
            // ATRIBUI ID NA FEATURE
            event.feature.setId(id);
            // SALVA OS DADOS ALTERADOS E ATUALIZA BOOLEANA PRA CONFIRMAR QUE A FEATURE FOI CONCLUIDA
            setTimeout(saveData, 1);
            featureComplete = true;
            jQuery("#anymark").val("1");
            });
    }
    // ADICIONA A INTERACAO DRAW QUANDO A PAGINA CARREGAR
    addDrawInteraction();
    var pontoMarcado = false;
    // replace this function by what you need
    function saveData() {
      // define a format the data shall be converted to
            var format = new ol.format['GeoJSON'](),
      // this will be the data in the chosen format
            drawRegionData;
      try {
            // convert the data of the vector_layer into the chosen format				
            var data = format.writeFeaturesObject(vector_layer_mapProposta.getSource().getFeatures());				
      } catch (e) {
            // at time of creation there is an error in the GPX format (18.7.2014)
            //$('#data').val(e.name + ": " + e.message);
            return;
      }
            // $('#data').val(JSON.stringify(data, null, 4));
            // ENVIA COORDENADAS DO PERIMETRO DESENHADO PARA O FORM				
            jQuery("#coordsPerimetro").val(JSON.stringify(data.features[0]));
    }
    /**
    *   LIMPA O MAPA
    */
    function clearMap() {
        vector_layer_mapProposta.getSource().clear();
        if (select_interaction) {
                select_interaction.getFeatures().clear();
        }
        if(latFinal){
                jQuery("#pinpointLatitude").val("");
                jQuery("#pinpointLongitude").val("");
        }
        pontoMarcado = false;
        featureComplete = false;
        jQuery("#anymark").val("");
        jQuery("#coordsPerimetro").val("");
    }            
    function uid(){
        var id = 0;
        return function() {
            if (arguments[0] === 0)
                id = 0;
            return id++;
        }
    }
    //LATLONG
    var pontoMarcado = false;
    //PEGA LATITUDE E LONGITUDE DO PONTO SELECIONADO
    var latFinal = 0;
    var longFinal = 0;
    mapProposta.on('click', function(evt) {
        var lonlat = ol.proj.transform(evt.coordinate, 'EPSG:3857', 'EPSG:4326');
        var lon = lonlat[0];
        var lat = lonlat[1];
        if(!pontoMarcado && ($geom_type.val())=='Point'){
                latFinal = lat;
                longFinal = lon;
                pontoMarcado = true;
                jQuery("#pinpointLatitude").val(latFinal);
                jQuery("#pinpointLongitude").val(longFinal);
        }	
    });
    jQuery("#deleteMark").on("click", function(){                
        clearMap();
    });
    jQuery("#novaProposta").on("click", function(){
         setTimeout(function(){
             mapProposta.setTarget('mapProposta');
        },1000);
    });
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
// FIM DO ondocumentready
        
/**
* RECEBE TODOS OS COMENTÁRIOS POR ID.
*/
function getCommentsById(id){
    showLoading(true);
    jQuery.ajax({
        type: 'POST',
        url: "../wp-admin/admin-ajax.php",
        data: { action: 'plataformaApoio_getAllComments', id:id,tag:0},
        success: function(comments){
                showLoading(false);
                jQuery("#commentsContainer").empty();
                jQuery("#commentsContainer").append(platMapAPI.bindValuesComments(JSON.parse(comments),true));
        }
    });
}

/**
*   ATRIBUI INFORMAÇÕES DO PERÍMETRO
*/
function bindValuesPerimetro(objInfoPerimetro,feature){
    var htmlStruct = '<div style="width:90%;"><h5>Localização</h5><p id="localizacaoPerimetro" class="infoPerimetroStyle"></p>'+
                    '<br /><h5>Caracterização</h5><p id="caracterizacaoPerimetro" class="infoPerimetroStyle"></p>'+
                    '<br /><h5>Objetivos</h5><p id="objetivosPerimetro" class="infoPerimetroStyle"></p>'+
                    '<br /><h5>Diretrizes</h5><p id="diretrizesPerimetro" class="infoPerimetroStyle"></p></div>';
    jQuery(".perimeterInfo").empty();
    jQuery(".perimeterinfo").html(htmlStruct);
    jQuery("#caracterizacaoPerimetro").html(objInfoPerimetro.CARACTERIZACAO);
    jQuery("#objetivosPerimetro").html(objInfoPerimetro.OBJETIVOS);
    jQuery("#localizacaoPerimetro").html(objInfoPerimetro.LOCALIZACAO);
    jQuery("#diretrizesPerimetro").html(objInfoPerimetro.DIRETRIZES);
   // document.getElementById("idFeature").value = objInfoPerimetro.ID;
   // jQuery("#regiaoComentadaContainer").show();
    getCommentsById(objInfoPerimetro.ID);
}

/**
*   ATRIBUI INFORMAÇÕES DA PROPOSTA(CONTRIBUIÇÃO)
*/
function bindValuesPerimetroProposta(feature){
var htmlStruct = '<h5>Nome</h5><p id="nomeColaboracaoFeature"></p>'+
        '<br /><h5>Autor</h5><p id="autorColaboracaoFeature"></p>'+
        '<br /><h5>Colaboração</h5><p id="descricaoColaboracaoFeature"></p>'+
        '<br /><h5>Enviada em</h5><p id="dataEnvioColaboracaoFeature"></p>';
    jQuery(".perimeterInfo").empty();
    //jQuery("#regiaoComentadaContainer").hide();
    jQuery(".perimeterinfo").html(htmlStruct);
    jQuery("#nomeColaboracaoFeature").html(feature.get("NOME"));
    jQuery("#autorColaboracaoFeature").html(feature.get("AUTHOR_NAME"));
    jQuery("#descricaoColaboracaoFeature").html(feature.get("COLABORACAO"));
    jQuery("#dataEnvioColaboracaoFeature").html(feature.get("SUBMITTED"));
    //document.getElementById("idFeature").value = feature.get("ID");
    getCommentsById(feature.get("ID"));
}

/**
*   PEGA A FEATURE NO LOCAL DO CLIQUE
*/
function getFeatureAtPixelX(pixel,map){
    var features = [];
    var feature;
    map.forEachFeatureAtPixel(pixel, function(feature) {
        // Exceção: Feature CTGU003 sobrepunha as demais devido à sua área.
        // Antes de retornar feature, verifica se há outras no mesmo local. Se não houver, retorna CTGU003. Se houver, retorna outra.
        if(feature.get("ID") == "CTGU003"){
            if(features.length < 2)
                features.push(feature);
        }
        else
            features.push(feature);
    });

    // console.log(features.length);
    return features[features.length-1];
}

/**
*   AO CLICAR, IDENTIFICA TIPO DE FEATURE E ATRIBUI OS VALORES DE ACORDO COM O TIPO
*/
map.on('singleclick', function(evt) {
    var coordinate = evt.coordinate;
    var pixelClick = map.getEventPixel(evt.originalEvent);
    var hdms = ol.coordinate.toStringHDMS(ol.proj.transform(
        coordinate, 'EPSG:3857', 'EPSG:4326'));
    var feature = getFeatureAtPixelX(pixelClick,map);
    if(feature){
        feature = clusterCheck(feature,evt);
        if(feature != undefined){
            var id = feature.get("description");
            var GEOJSON_PARSER = new ol.format.GeoJSON();
            var fullFeature = GEOJSON_PARSER.writeFeature(feature);
            setTimeout(function(){
                mapInformacoes.setTarget('mapInformacoes');
                if(!feature.get("COLABORACAO")){
                    //Não é colaboração
                    bindValuesPerimetro(getPerimetroInfoById(id),fullFeature);
                    vectorLayerInformacoes.getSource().clear();
                    vectorLayerInformacoes.getSource().addFeature(feature);
                }
                else {
                    //É Colaboração
                     bindValuesPerimetroProposta(feature);
                     vectorLayerInformacoes.getSource().clear();
                     vectorLayerInformacoes.getSource().addFeature(feature);
                }
                mapInformacoes.getView().fit(vectorLayerInformacoes.getSource().getExtent(), mapInformacoes.getSize());
                if(mapInformacoes.getView().getZoom() > 23)
                    mapInformacoes.getView().setZoom(15);
                },1000);
            jQuery("#elementClickOpenPopUp1").click();
        }
    }
    var duration = 2000;
    var start = +new Date();
    var pan = ol.animation.pan({
        duration: duration,
        source: (view.getCenter()),
        start: start
    });
});

/**
*   ILUMINA FEATURE AO PASSAR O MOUSE POR ELA E EXIBE INFORMAÇÕES NO POPUP
*/
var highlightStyleCache = {};
var featureOverlay = new ol.layer.Vector({
    source: new ol.source.Vector(),
    map: map,
    style: function(feature, resolution) {
        var text = resolution < 5000 ? feature.get('NOME') : feature.get('NOME');          
        if (!highlightStyleCache[text]) {
        highlightStyleCache[text] = new ol.style.Style({
          stroke: new ol.style.Stroke({
            color: 'rgba(31, 60, 147, 0.2)',
            width: 2
          }),
          fill: new ol.style.Fill({
            color: 'rgba(31, 60, 147, 0.2)'
          }),
          text: new ol.style.Text({
            font: '12px Calibri,sans-serif',
            // text: text,
            fill: new ol.style.Fill({
              color: '#000'
            }),
            stroke: new ol.style.Stroke({
              color: '#f00',
              width: 3
            })
          })
        });
        }
        return highlightStyleCache[text];
    }
});

/**
* VERIFICA SE FEATURE É UM CLUSTER E RETORNA AS INFORMAÇÕES SE ELA NÃO ESTIVER AGRUPADA
*/
function clusterCheck(feature,evt){
    if(feature.get('features')){
        var featuresProp = feature.get('features');
        var propsQtde = featuresProp.length;
        if(propsQtde>1){
            platMapAPI.flyToPoint(evt.coordinate, 800, map.getView().getZoom()+2);
            return;
        }
        else
            feature = featuresProp[0];
    }
    return feature;
}

/**
*   EXIBE INFORMAÇÕES DA FEATURE
*/
var displayFeatureInfo = function(pixel,evt) {
    var feature = getFeatureAtPixelX(pixel,map);
    var coordinate = evt.coordinate;
    if (feature) {
        var cArray = feature.get("features");
        var ainfo = "";
        var isAgrupada = false;
        var popupHtml = "";
        // Verifica se a feature é um cluster de features
        if(jQuery.isArray(cArray)){
            if(cArray.length == 1){
                // Se possui só 1 feature no cluster, atribui seu valor como feature em si em vez de array
                feature = cArray[0];
            }
            else{
                // Se possui mais de 1, determina quantas features há na área.
                ainfo = (cArray.length+" contribuições nessa área.<br>(clique para aproximar)");
                isAgrupada = true;
            }
        }
        // Se for cluster(agrupamento de features)
        if(isAgrupada){
            popupHtml = ainfo +  '<br>';                
        }
        else if(!feature.get("COLABORACAO")){
            var featureInfo = getPerimetroInfoById(feature.get("description"));
            popupHtml = '<br>' + featureInfo.NOME +  '<br>' + featureInfo.ID;
        }
        else {
            popupHtml = '<br>' + feature.get("NOME") +  '<br>';
        }
        content.innerHTML = popupHtml;
        overlay.setPosition(coordinate);
    }else {
        content.innerHTML = '&nbsp;';
        popupClose();
    }
    // Sobrescreve feature para exibir highlight
    if (feature !== highlight) {
      if (highlight) {
        featureOverlay.getSource().removeFeature(highlight);
      }
      if (feature) {
        featureOverlay.getSource().addFeature(feature);
      }
      highlight = feature;
    }
};

/**
*   DETECTA MOUSEOVER PARA EXIBIR INFORMAÇÕES DA FEATURE
*/
map.on('pointermove', function(evt) {
    if (evt.dragging) {
      return;
    }
    var pixel = map.getEventPixel(evt.originalEvent);
    displayFeatureInfo(pixel,evt);
});
