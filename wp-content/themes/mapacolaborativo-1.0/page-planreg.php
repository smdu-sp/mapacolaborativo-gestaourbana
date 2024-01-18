<?php
/*
 * Template Name: Plataforma Apoio - Planos Regionais
 */
?>
<?php
    get_header();
?>
<link rel="stylesheet" href="http://openlayers.org/en/v3.13.1/css/ol.css" type="text/css">
<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-mapa-colaborativo.css"/>
<script src="http://openlayers.org/en/v3.13.1/build/ol.js"></script>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
<div id="news-inner">
    <!-- <div class="wrapper"> -->
        <div class="left" style="width:100%;">
            <div class="inner floatComment" style="margin: 0px">
                <br/>
                <br/>
                <div id="display">

                    <center><div class="prHeaderInfo">Clique no perímetro desejado para mais informações sobre o local.</div></center>
                    <div style="padding: 2px;">&nbsp;</div>
                    <center><div id="map" style="height: 600px; "></div></center>
                    <div id="containerComment" class="animationPopUps">
                        <div class="popup-closer-iframe"></div>
                        <div class="topcontainer">
                           <div class="topo">
                                   <img src="../wp-content/themes/mapacolaborativo-1.0/images/img-comment.png" alt="Comentários" align="left">
                           </div>
                           <div class="topo" id="cabecalioComment">
                                   <h3>Comentários</h3>
                           </div>
                       </div>
                    <iframe name="commentsPerimetro" id="commentsPerimetro"></iframe></div>
                    <div id="containerFicha" class="animationPopUps">
                        <div class="popup-closer-iframe"></div>
                        <div class="topcontainer" >
                            <div class="topo" id="cabecalioFichaPerimetro">

                            </div>
                        </div>
                        <iframe name="fichaPerimetro" id="fichaPerimetro" style="overflow-x: hidden;"></iframe>
                    </div>
                    <div id="info"></div>
                    <div id="popup" class="ol-popup">
                        <div id="popup-content"></div>
                    </div>
                    <div id="sidenav">
                      <div class="BotaoApoiarVermelho BotoesMenu" id="botoesMenuPlataforma1">
                          <label class="unselectable">Apresentação</label>
                      </div>
                      <div class="BotaoApoiarVermelho BotoesMenu" id="botoesMenuPlataforma2">
                          <label class="unselectable">Cadastre-se</label>
                      </div>
                      <div class="BotaoApoiarVermelho BotoesMenu" id="botoesMenuPlataforma3">
                          <label class="unselectable">Participe</label>
                      </div>
                      <div class="checkbox-camadas" id="toggleCamada">
                        <p class="unselectable">Exibir contribuições da população</p>
                      </div>
                    </div>
							
<!--ADICIONAR BOTAO NOVA PROPOSTA-->
<!--                            <div class="BotaoApoiarVermelho BotoesMenu" id="botoesMenuPlataforma4">
                                <label class="unselectable">Nova proposta</label>
                            </div>-->
<!--QUARTO BOTAO-->
<!--                            <div class="BotaoApoiarVermelho BotoesMenu" id="botoesMenuPlataforma4">
                                <label class="unselectable">Tutorial</label>
                            </div>-->
					<div style="padding: 4px;">&nbsp;</div>
					<div class="prHeaderInfo" style="padding-bottom: 15px">
						<center><div id="prLineInfo" style="margin-bottom: 10px">Caso o local que procura não esteja dentro de um dos perímetros, clique no botão abaixo para fazer uma nova proposta:</div></center>
						<center><div id="novaProposta" class="unselectable"><label>Nova proposta</label></div></center>
						<!-- <center><div id="testaCamadas" class="unselectable"><label>Toggle Camada</label></div></center> TODO Remover-->
					</div>
				</div>
            </div>
        </div>
	<div class="clear"></div>
    <!-- </div> -->
</div>
<?php endwhile; ?>
<script type="text/javascript">
// author: Renan
//	DECLARA PROPRIEDADES DAS FEATURES PROPOSTAS
var featuresPropostas;
var vectorLayer;
var propsLayer;
var isAjaxLoaded = false;
var isHidden = false;

function clearAllFeatures(mapLayer){
	mapLayer.getSource().clear();
}

function ocultaCamada(camada){
	if(isAjaxLoaded){
		if(!isHidden){
			var features = camada.getSource().getFeatures();			
			for(var i = 0; i < features.length; i++ ) {
				features[i].style = { visibility: 'hidden' };				
			}
			clearAllFeatures(camada);
			jQuery("#toggleCamada p").html("Exibir contribuições da população");
			isHidden = true;
		}
		
		else {
			jQuery("#toggleCamada p").html("Ocultar contribuições da população");
			carregarPropostas();			
		}
	}
	else
		console.log("Features not loaded yet!")
}	
		
jQuery.ajax({
	type: 'GET',
	url: '<?php echo admin_url( 'admin-ajax.php' ) ?>',
	data: { action: 'plataformaApoio_getAllPropostas' },
	success: function(data){
		featuresPropostas = JSON.parse(data).propostas;
		isAjaxLoaded = true;
		carregarPropostas();
		ocultaCamada(propsLayer);
                console.log(JSON.parse(data));
	}
});
jQuery.ajax({
	type: 'GET',
	url: '<?php echo admin_url( 'admin-ajax.php' ) ?>',
	data: { action: 'planosRegionais_getAllPerimetros' },
	success: function(data){
		var perimetros = JSON.parse(data);
                console.log("TODOS OS PERIMETROS: ", perimetros);
	}
});

// FAZER EXIBIR FEATURES NO MAPA
function carregarPropostas(){
	featuresPropostas.forEach(objToFeature);	
	isHidden = false;
}
function objToFeature(feature) {
	var featureProposta = (new ol.format.GeoJSON()).readFeature(feature.coords.feature);	
	featureProposta.set("ID",feature.id);
	featureProposta.set("NOME",feature.titulo);
	featureProposta.set("AUTHOR_NAME",feature.autor.nome);
	featureProposta.set("COLABORACAO",feature.colaboracao);
	featureProposta.set("SUBMITTED",feature.submitted);
	propsLayer.getSource().addFeature(featureProposta);
}
// END author: Renan

var headerFichaPerimetro = document.getElementById("cabecalioFichaPerimetro");
var headerComments = document.getElementById("cabecalioComment");
function showAllFrames(){
    jQuery('#containerComment').css('width','25%');
    jQuery('#containerComment').css('height','500px');
    jQuery('#containerFicha').css('width','35%');
    jQuery('#containerFicha').css('height','500px');
    jQuery('#containerComment, #containerFicha').css('opacity','1');
}
function hideCommentFrame(){
    jQuery('#containerComment').css('width','1px');
    jQuery('#containerComment').css('height','1px');
    jQuery('#containerComment').css('opacity','0');
}
function hideAllFrames(){
    jQuery('#containerComment, #containerFicha').css('width','1px');
    jQuery('#containerComment, #containerFicha').css('height','1px');
    jQuery('#containerComment, #containerFicha').css('opacity','0');
}
function setHeaderFichaPopUp(content,container){
            while (container.firstChild) {
                container.removeChild(container.firstChild);
            }
            var divTitulo = document.createElement('div');
            divTitulo.className = 'topo';
            divTitulo.innerHTML = "<h3>"+content+"</h3>";
            container.appendChild(divTitulo);
}
jQuery(".popup-closer-iframe").on("click",function(){
    hideAllFrames();
});
jQuery(".BotoesMenu").on("mouseout",function(){
    jQuery(".BotoesMenu").css('background-color','#E00808');
    jQuery(".BotoesMenu").css('width','225px');
});
jQuery("#botoesMenuPlataforma1").on("mouseover",function(){
    jQuery("#botoesMenuPlataforma1").css('background-color','#E66E89');
    jQuery("#botoesMenuPlataforma1").css('width','255px');
});

jQuery("#botoesMenuPlataforma2").on("mouseover",function(){
    jQuery("#botoesMenuPlataforma2").css('background-color','#E66E89');
    jQuery("#botoesMenuPlataforma2").css('width','255px');
});

jQuery("#botoesMenuPlataforma3").on("mouseover",function(){
    jQuery("#botoesMenuPlataforma3").css('background-color','#E66E89');
    jQuery("#botoesMenuPlataforma3").css('width','255px');
});
jQuery("#botoesMenuPlataforma4").on("mouseover",function(){
    jQuery("#botoesMenuPlataforma4").css('background-color','#E66E89');
    jQuery("#botoesMenuPlataforma4").css('width','255px');
});
//      BOTOES MENU LATERAL
jQuery("#botoesMenuPlataforma1").on("click",function(){
    showAllFrames();
    hideCommentFrame();
    setHeaderFichaPopUp("Apresentação",headerFichaPerimetro);
    window.open('../plataforma/apresentacao/ ','fichaPerimetro');
});

jQuery("#botoesMenuPlataforma2").on("click",function(){
    showAllFrames();
    hideCommentFrame();
    setHeaderFichaPopUp("Novo Usuário", headerFichaPerimetro);
    setHeaderFichaPopUp("Comentários Recentes",headerComments);
    window.open('../plataforma/cadastrar-novo-usuario/','fichaPerimetro');
});

jQuery("#botoesMenuPlataforma3").on("click",function(){
    showAllFrames();
    hideCommentFrame();
    setHeaderFichaPopUp("Participe",headerFichaPerimetro);
    window.open('../plataforma/tutorial/ ','fichaPerimetro');
});

jQuery("#botoesMenuPlataforma4").on("click",function(){
    showAllFrames();
    hideCommentFrame();
    setHeaderFichaPopUp("Nova Proposta",headerFichaPerimetro);
    window.open('../plataforma/nova-proposta/ ','fichaPerimetro');
});

jQuery("#novaProposta").on("click",function(){
    showAllFrames();
    hideCommentFrame();
    setHeaderFichaPopUp("Nova Proposta",headerFichaPerimetro);
    window.open('../plataforma/nova-proposta/ ','fichaPerimetro');
});
jQuery( "#CEP" ).keypress(function() {
        if (jQuery( "#CEP" ).val().length == 5)
        {
            jQuery( "#CEP" ).val(jQuery( "#CEP" ).val() + '-');
        }
});

jQuery("#toggleCamada").on("click",function(){
    ocultaCamada(propsLayer);
	jQuery("#toggleCamada input").prop("checked", !jQuery("#toggleCamada input").prop("checked"));
});

var style = new ol.style.Style({
	fill: new ol.style.Fill({
		color: 'rgba(255, 255, 255, 0.6)'
	}),
	stroke: new ol.style.Stroke({
		color: '#319FD3',
		width: 1
	}),
	text: new ol.style.Text({
		font: '12px Calibri,sans-serif',
		fill: new ol.style.Fill({
			color: '#000'
		}),
		stroke: new ol.style.Stroke({
			color: '#fff',
			width: 3
		})
	})
});

var container = document.getElementById('popup');
var content = document.getElementById('popup-content');
/**
* Create an overlay to anchor the popup to the map.
*/
var overlay = new ol.Overlay( /** @type {olx.OverlayOptions} */ ({
	element: container,
	autoPan: true,
	autoPanAnimation: {
	  duration: 250
	}
}));


      /**
       * Add a click handler to hide the popup.
       * @return {boolean} Don't follow the href.
       */

        function popupClose() {
            overlay.setPosition(undefined);
            return false;
        }

		propsLayer = new ol.layer.Vector({
			name: 'propsLayer',
			source: new ol.source.Vector(),
			/** ESTILO DAS FEATURES PROPOSTAS */
			style: new ol.style.Style({
				fill: new ol.style.Fill({
					// color: 'rgba(150, 100, 255, 0.3)'
					color: 'rgba(128, 159, 255, 0.3)'
				}),
				stroke: new ol.style.Stroke({
				  color: 'rgba(128, 159, 255, 1)',
				  width: 1
				}),
				image: new ol.style.Icon({
					anchor: [0.5, 46],
					anchorXUnits: 'fraction',
					anchorYUnits: 'pixels',
					opacity: 0.95,
					scale: 0.7,
					color: 'rgba(128, 159, 255, 1)',					
					src: '../wp-content/themes/mapacolaborativo-1.0/images/img-map-marker-icon.png'
				})
			})
		});
		vectorLayer = new ol.layer.Vector({
		source: new ol.source.Vector({
		  url: '../wp-content/uploads/2016/03/perimetros.kml',
		  format: new ol.format.KML({
				extractStyles: true,
				extractAttributes: true,
				maxDepth: 2
			})
		}),
		style: function(feature, resolution) {
		  style.getText().setText(resolution < 5000 ? feature.get('name') : '');
		  return style;
		}
		});
	  

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
          vectorLayer, propsLayer
        ],
        overlays: [overlay],
        target: 'map',
        view: view
      });
       /**
       * Add a click handler to the map to render the popup.
       */
      function getFeatureAtPixelX(pixel,map){
        var features = [];
        var feature;
        map.forEachFeatureAtPixel(pixel, function(feature) {
            features.push(feature);
        });
        if(features.length>1){
            feature = features[features.length-1];
        }else{
            feature = features[0];
        }
        return feature;
      }
      map.on('singleclick', function(evt) {
        var coordinate = evt.coordinate;
        var pixelClick = map.getEventPixel(evt.originalEvent);
        var hdms = ol.coordinate.toStringHDMS(ol.proj.transform(
            coordinate, 'EPSG:3857', 'EPSG:4326'));
        var feature = getFeatureAtPixelX(pixelClick,map);
        if(feature){
            console.log("Feature",feature);
            showAllFrames();
            var id = feature.get("ID");
            var nome = feature.get("NOME");			
			var GEOJSON_PARSER = new ol.format.GeoJSON();
			var fullFeature = GEOJSON_PARSER.writeFeature(feature);
            sessionStorage.setItem("feature",fullFeature);
			sessionStorage.setItem("coordinate",coordinate);
            sessionStorage.setItem("perimetro",id);			
            setHeaderFichaPopUp("Comentários", headerComments);
            setHeaderFichaPopUp(id+" - "+nome,headerFichaPerimetro);
            window.open('../plataforma/ficha-do-perimetro/?hd='+id,'fichaPerimetro');
            window.open('../plataforma/comentarios-do-perimetro/?hd='+id,'commentsPerimetro');

        }else{
            hideAllFrames();
        }
        var duration = 2000;
        var start = +new Date();
        var pan = ol.animation.pan({
          duration: duration,
          source: /** @type {ol.Coordinate} */ (view.getCenter()),
          start: start
        });

      });

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

      var highlight;
      var displayFeatureInfo = function(pixel,evt) {
        var feature = getFeatureAtPixelX(pixel,map);
        var coordinate = evt.coordinate;
        if (feature) {			
            var popupHtml = feature.get("NOME") +  '<br><br>' + feature.get("ID");
                // popupHtml += '<br><br>Tipo de Ação: <p>' + feature.get('TIPO_ACAO');
            content.innerHTML = popupHtml;
            overlay.setPosition(coordinate);
        }else {
            content.innerHTML = '&nbsp;';
            popupClose();
        }

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

      map.on('pointermove', function(evt) {
        if (evt.dragging) {
          return;
        }
        var pixel = map.getEventPixel(evt.originalEvent);
        displayFeatureInfo(pixel,evt);
      });
//DA ZOOM PARA CABEREM TODOS OS PERIMETROS DESENHADOS NA LAYER VECTORLAYER
        setTimeout(function(){
            var extent = vectorLayer.getSource().getExtent();
            map.getView().fit(extent, map.getSize(), {padding: [20,20,80,20]});
        }, 2000);
//FIMZOOM
</script>
<?php get_footer(); ?>