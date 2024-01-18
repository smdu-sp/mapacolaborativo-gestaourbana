/**
* VARIÁVEIS PARA MANIPULAR AS FEATURES DE COLABORAÇÕES
*/
var featuresPropostas;
var propsLayerProducao = new ol.layer.Vector({
			name: 'propsLayerProducao',
			source: new ol.source.Vector(),
			/** ESTILO DAS FEATURES PROPOSTAS */
			style: getStyleLayerWIcon("../wp-content/uploads/2016/06/pictogramas_produtor.png")
});
var propsLayerTurismo = new ol.layer.Vector({
			name: 'propsLayerTurismo',
			source: new ol.source.Vector(),
			/** ESTILO DAS FEATURES PROPOSTAS */
			style: getStyleLayerWIcon("../wp-content/uploads/2016/06/pictogramas_turismo.png")
});

/**
* CONFIGURA ESTILO DA CAMADA
* RECEBE: URL DO ÍCONE
* RETORNA: ESTILO
*/
function getStyleLayerWIcon(urlIcon){
  var stylePointLayer;
  var iconStyle = new ol.style.Icon({
    anchor: [0.5, 46],
    anchorXUnits: 'fraction',
    anchorYUnits: 'pixels',
    opacity: 0.95,
    scale: 1,
    src: urlIcon
  });
  stylePointLayer = new ol.style.Style({
    fill: new ol.style.Fill({
      color: 'rgba(128, 159, 255, 0.3)'
    }),
    stroke: new ol.style.Stroke({
      color: 'rgba(128, 159, 255, 1)',
      width: 1
    }),
    image: iconStyle
  });
  return stylePointLayer;
}

/**
* CONVERTE OBJETO EM FEATURE DO TIPO PONTO E A INSERE NA CAMADA
* RECEBE: DADOS DA FEATURE, CAMADA
*/
function objToPointFeature(infoFeature,layer) {
    var lat = parseFloat(infoFeature.latlon.latitude);
    var lon = parseFloat(infoFeature.latlon.longitude);
    var featureProposta = new ol.Feature({
        geometry: new ol.geom.Point(ol.proj.transform([lon, lat], 'EPSG:4326', 'EPSG:3857'))		
    });
	featureProposta.set("DADOS_COLAB",infoFeature);
	layer.getSource().addFeature(featureProposta);
}

//ajax para chamar o serviço para todas as propostas aprovadas
jQuery.ajax({
	type: 'GET',
	url: "../wp-admin/admin-ajax.php",
	data: { action: 'plataformaApoio_bordasDaCidade_getAllColaboracoes' },
	success: function(data){
    featuresPropostas = JSON.parse(data);
    var i;
    //loops para carregar as propostas em suas respectivas layers
    for(i=0;i<featuresPropostas.colaboracoes_produtor.length;i++){
        objToPointFeature(featuresPropostas.colaboracoes_produtor[i],propsLayerProducao);
    }
    for(i=0;i<featuresPropostas.colaboracoes_turismo.length;i++){
        objToPointFeature(featuresPropostas.colaboracoes_turismo[i],propsLayerTurismo);
    }
	}
});

// BOTÕES LATERAIS E SUAS ANIMAÇÕES
jQuery("#toggleCamadaFeiras").on("click",function(){
    platMapAPI.showHideCamada(camadaFeiras,null,null,null);
});
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
jQuery("#toggleCamadaProducao").on("click",function(){
    platMapAPI.showHideCamada(propsLayerProducao,null,null,null);    
});
jQuery("#toggleCamadaTurismo").on("click",function(){
    platMapAPI.showHideCamada(propsLayerTurismo,null,null,null);    
});
jQuery("#toggleCamadasPontosTuristicos").on("click",function(){
    platMapAPI.showHideCamada(camadaPatrimonio,null,null,null);
    platMapAPI.showHideCamada(camadaAtrativos,null,null,null);    
});
jQuery("#toggleCamadaCicloturismo").on("click",function(){
    platMapAPI.showHideCamada(camadaRotasCicloTurismo,null,null,null);    
});
jQuery(".tituloPlataforma").html("Mapa Colaborativo do Desenvolvimento Rural Sustentável");

// CAMADAS
var camadaMSP,camadaZRural,camadaMercado,camadaFeiras,camadaAgricultor,camadaPoloEcoturistico,camadaPatrimonio,camadaAtrativos,camadaRotasCicloTurismo;
camadaMSP = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/2016/06/MSP.kml');
camadaZRural = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/2016/06/ZRURAL.kml');
camadaPoloEcoturistico = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/2016/06/perimetro_polo_ecoturismo.kml');
// camadaMercado = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/2016/06/Mercados.kml');
camadaFeiras = platMapAPI.createClusterLayerFromKML('../wp-content/uploads/2016/06/Feiras.kml');
camadaPatrimonio = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/2016/06/Patrimonio_historico.kml');
camadaAtrativos = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/2016/06/atrativos.kml');
camadaRotasCicloTurismo = platMapAPI.createVectorLayerFromKML('../wp-content/uploads/2016/06/rotas_de_cicloturismo_das_APAS.kml');
camadaFeiras.setVisible(false);
propsLayerProducao.setVisible(false);
propsLayerTurismo.setVisible(false);
camadaPatrimonio.setVisible(false);
camadaAtrativos.setVisible(false);
camadaRotasCicloTurismo.setVisible(false);

/**
* HOVER POPUP
*/
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
* CONFIGURA MAPA
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
    //source: new ol.source.MapQuest({layer: 'sat'})
    source: new ol.source.OSM()
  }),
  camadaMSP,camadaZRural,camadaPoloEcoturistico,camadaFeiras,propsLayerProducao,
  propsLayerTurismo,camadaPatrimonio,camadaAtrativos,camadaRotasCicloTurismo
],
overlays: [overlay],
target: 'map',
view: view
});
//popup Imóvel
var vectorLayerBordas = new ol.layer.Vector({
        name: 'vectorLayerBordas',
        source: new ol.source.Vector(),
        style: getStyleLayerWIcon("../wp-content/themes/mapacolaborativo-1.0/images/img-map-marker-icon.png")
});
var mapImovel = new ol.Map({
  layers: [
        new ol.layer.Tile({
          source: new ol.source.OSM()
        }),vectorLayerBordas
  ],
  view: new ol.View({
        center: [-5191207.638373509,-2698731.105121977],
        zoom: 10,
        minZoom: 10,
        maxZoom: 30
    })
});

/**
* DESENHA PONTO NO MAPA
* RECEBE: LATITUDE, LONGITUDE
*/
function drawPointMap(lat,lon){
        vectorLayerBordas.getSource().clear();
        var point = new ol.Feature({
            geometry: new ol.geom.Point(ol.proj.transform([lon, lat], 'EPSG:4326', 'EPSG:3857'))		
         });
        vectorLayerBordas.getSource().addFeature(point);
        mapImovel.getView().setZoom(15);
        mapImovel.getView().setCenter(ol.proj.transform([lon, lat], 'EPSG:4326', 'EPSG:3857'));
        //mapImovel.getView().fit(vectorLayerImovel.getSource().getExtent(), mapImovel.getSize());
}

/**
* ABRE POPUP COM FORMULÁRIO
*/
var highlight;
function openPopupForm(element){
    jQuery(element).click();
    popupClose();
}
// EXIBRE INFORMAÇÕES DA FEATURE
var displayFeatureInfo = function(pixel,evt) {
    var feature = getFeatureAtPixelX(pixel,map);
    var coordinate = evt.coordinate;
};

/**
* ATRIBUI INFORMAÇÕES DAS FEATURES DE COLABORAÇÃO
*/
function bindValuesProducao(producaoData){
    //<div id="popupProdutorInfo"></div>
    var fixedImgUrl = producaoData.urlImagem;
    if(producaoData.urlImagem == '')
        fixedImgUrl = "wp-content/uploads/bordas_da_cidade/sub_usercolab.png";
    var htmlData = "<a href='../"+fixedImgUrl+"' target='_blank'>\n\
                    <img src='../"+fixedImgUrl+"' alt='Imagem da Produção' style='max-width: 100% !important;padding: 30px;'/></a>\n\
                    <br/><br/><h4>Produtos Cultivados</h4>\n\
                    <p>"+producaoData.produtosCultivados+"</p>\n\
                     <h4>Características da Produção</h4>\n\
                     <p>"+producaoData.caracteristicasProducao+"</p>\n\
                    <br/><h4>Finalidade da Produção</h4>\n\
                    <p>"+producaoData.finalidadeProducao+"</p>\n\
                     <h4>Principal fonte de renda?</h4>\n\
                     <p>"+producaoData.principalFonteRenda+"</p>\n\
                     <h4>Propriedade aberta para visitantes?</h4>\n\
                     <p>"+producaoData.permiteVisitantes+"</p>";
                     jQuery("#popupProdutorInfo").html(htmlData);       
}

/**
* ATRIBUI INFORMAÇÕES DAS FEATURES DE TURISMO
*/
function bindValuesTurismo(turismoData){
  var fixedImgUrl = turismoData.urlImagem;
  if(turismoData.urlImagem == '')
    fixedImgUrl = "wp-content/uploads/bordas_da_cidade/sub_usercolab.png";
  var htmlData = "<a href='../"+fixedImgUrl+"' target='_blank'>\n\
    <img src='../"+fixedImgUrl+"' alt='Imagem do Empreendimento' style='max-width: 100% !important;padding: 30px;'/></a>\n\
    <br/><br/><h4>Tipo do Empreendimento</h4>\n\
    <p>"+turismoData.tipoEmpreendimento+"</p>\n\
     <h4>Tipo de acesso ao Empreendimento</h4>\n\
     <p>"+turismoData.tipoAcessoEmpreendimento+"</p>\n\
    <br/><h4>Meio de acesso ao Empreendimento</h4>\n\
    <p>"+turismoData.meioAcessoEmpreendimento+"</p>\n\
     <h4>Principal fonte de renda?</h4>\n\
     <p>"+turismoData.principalFonteRenda+"</p>\n\
     <h4>Principais atrativos ecoturísticos</h4>\n\
     <p>"+turismoData.principaisAtrativos+"</p>";
     jQuery("#popupTurismoInfo").html(htmlData);  
}

/**
* GERENCIA CLIQUE NO MAPA
*/
map.on('singleclick', function(evt) {
  var coordinate = evt.coordinate;
  var pixelClick = map.getEventPixel(evt.originalEvent);
  //displayFeatureInfo(pixelClick,evt);
  var hdms = ol.coordinate.toStringHDMS(ol.proj.transform(
      coordinate, 'EPSG:3857', 'EPSG:4326'));
  var feature = platMapAPI.getFeatureAtPixelX(pixelClick,map,0);
  
  if(feature){
    /// Verifica se feature é um cluster de features FEIRA LIVRE
    if(feature.get('features')){
      var featuresFeira = feature.get('features');
      var feirasQtde = featuresFeira.length;
      var isMostraResumo = false;
      var resumoFeiras = '';
      for (var i = feirasQtde - 1; i >= 0; i--) {
        var classe = featuresFeira[i].get('NM_CLASSE_');
        // Atribui informações das feiras
        if(classe == "FEIRAS LIVRES"){
          var popupHtml = '';
          if(feirasQtde == 1){
            var feira = featuresFeira[i];
            popupHtml = "<div align='center'><h4>Feira Livre</h4>" +
                        "Endereço: " + feira.get("TX_ENDEREC")+
                        "<br>"+
                        "Bairro: " + feira.get("NM_BAIRRO_")+"</div>";
            content.innerHTML = popupHtml;
            overlay.setPosition(coordinate);
          }
          // Delimita quantidade de feiras a exibir 
          else if (feirasQtde > 5){
            platMapAPI.flyToPoint(coordinate, 800, map.getView().getZoom()+2);
            break;
          }
          else {
            resumoFeiras += featuresFeira[i].get("TX_ENDEREC")+"<br>";
            isMostraResumo = true;
          }
        }
      }
      // Exibe resumo das feiras na área
      if(isMostraResumo) {
        var popupHtml = "<div align='center'><h4>Feiras nessa área</h4>" + resumoFeiras;
        content.innerHTML = popupHtml;
        overlay.setPosition(coordinate);
      }
    }
    // Exibe informações da feira
    else if(!feature.get("DADOS_COLAB")){
      var lonlat = ol.proj.transform(evt.coordinate, 'EPSG:3857', 'EPSG:4326');
      var lon = lonlat[0];
      var lat = lonlat[1];
      jQuery(".pinpointLatitude").val(lat);
      jQuery(".pinpointLongitude").val(lon);
      var popupHtml = "";
      popupHtml += "<div align='center'><h4>O que voce é?</h4>";
      popupHtml += '<div class="popmake-236" style="cursor: pointer; color:green;" onclick="openPopupForm(\'#elementClickOpenPopUp\');">Sou Produtor.</div>';
      popupHtml += '<br><br><div class="popmake-234" style="cursor: pointer; color:orange;" onclick="openPopupForm(\'#elementClickOpenPopUp1\');">Trabalho com Turismo.</div></div>';
      if(feature.get("NM_CLASSE_")){
        popupHtml = "<div align='center'><h4>Feira Livre</h4>" +
                    "Endereço: " + feature.get("TX_ENDEREC")+
                    "<br>"+
                    "Bairro: " + feature.get("NM_BAIRRO_")+"</div>";
      }
      if(feature.get('Name')){
        var desc = "";
        if(feature.get("Descriptio"))
          desc += feature.get("Descriptio");
        popupHtml = "<div align='center'><h4>"+feature.get('Name')+"</h4>"+desc+'</div>';
      }
      content.innerHTML = popupHtml;
      overlay.setPosition(coordinate);
    }
    else {
      //Feature de colaboração
      var featureInfoValues = feature.get("DADOS_COLAB")
      if(featureInfoValues.produtosCultivados){
        //producao
        bindValuesProducao(featureInfoValues);
        openPopupForm('#elementClickOpenPopUp2');
      }else{
        //turismo
        bindValuesTurismo(featureInfoValues);
        openPopupForm('#elementClickOpenPopUp3');
      }
    }
  }
  // Transição suave ao mover para outro ponto
  var duration = 2000;
  var start = +new Date();
  var pan = ol.animation.pan({
    duration: duration,
    source: /** @type {ol.Coordinate} */ (view.getCenter()),
    start: start
  });
});

/**
* MOSTRA LEGENDAS
*/
jQuery(document).ready(function(){
  VMasker(document.getElementById("CEP")).maskPattern('99999-999');
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

// Corrige bug do logo
jQuery("#second").after(jQuery("#inserted_logos"));
jQuery("#base_to_remove").remove();