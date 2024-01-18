<?php


function planosRegionais1_tabelaPropostas($tipo) {
?>
    <br/>
    <br/>
    <script type='text/javascript'>
		function click_botao(x, cb)
		{
			var myObj = document.getElementById("onoffswitch-inner"+x);
			
			if (cb.readOnly)
			{
				cb.readOnly = false;
				jQuery(myObj).removeClass('readonly');
			}			
			if (document.getElementById("myonoffswitch"+x).checked)
			{
				var a = 'S';
			}
			else
			{
				var a = 'A';
			}
			jQuery.ajax({
				type: 'POST',
				url: '<?php echo admin_url( 'admin-ajax.php' ) ?>',
				data: { action: 'plataformaApoio_status_change', status: a, id: x },
				success: function(html_ceu){
					//sucess!!
				}
			});
			
		}
    </script>
	
	<div class="MCPRwrapper">
	<!-- <link rel="stylesheet" href="http://openlayers.org/en/v3.15.0/css/ol.css" type="text/css"> -->
    <!-- <script src="http://openlayers.org/en/v3.15.0/build/ol.js"></script> -->
    <link rel="stylesheet" href="../wp-content/themes/mapacolaborativo-1.0/css/ol.css" type="text/css">
    <script src="../wp-content/themes/mapacolaborativo-1.0/js/ol.js"></script>
	<div id="MCPRmapContainer">
		<div id="MCPRmap" class="map"></div>
	<!-- DESENHA MAPA -->
		<script>
		var featuresStyle = new ol.style.Style({
			fill: new ol.style.Fill({
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

		var styleFunction = function(feature) {
			return styles[feature.getGeometry().getType()];
		};
		var selector = 'MCPRmap';
		// GERA MAPA LIMPO
		var vectorLayer = new ol.layer.Vector({
			name: 'my_vectorlayer',
			source: new ol.source.Vector(),
			style: featuresStyle,
		});
		var map = new ol.Map({
		  layers: [
			new ol.layer.Tile({
			  source: new ol.source.OSM()
			}),
			vectorLayer
		  ],
		  target: selector,
		  view: new ol.View({
			center: [-5191207.638373509,-2698731.105121977],
			zoom: 12,
			minZoom: 10,
			maxZoom: 30
		  })
		});
		
		
		function drawPointMap(lat,lon){
			vectorLayer.getSource().clear();
			// DESIGNA A FEATURE PONTO A PARTIR DA LATITUDE E LONGITUDE
			var ponto = new ol.Feature({
				geometry: new ol.geom.Point(ol.proj.transform([lon, lat], 'EPSG:4326', 'EPSG:3857')),
				style: featuresStyle
			});
			vectorLayer.getSource().addFeature(ponto);
                        map.getView().fit(vectorLayer.getSource().getExtent(), map.getSize());
                        map.getView().setZoom(15);
		}
		function drawPolyMap(coords){
			vectorLayer.getSource().clear();
			// DESENHA FEATURE A PARTIR DAS COORDENADAS			
			var featureProposta = (new ol.format.GeoJSON()).readFeatures(coords);
			vectorLayer.getSource().addFeatures(featureProposta);
			map.getView().fit(vectorLayer.getSource().getExtent(), map.getSize());
		}
		/*
		function showPropOnMap(coords){
		console.log(coords);
		<!--  var perimetroCoords = 	
		PERIMETRO DE EXEMPLO
		{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[-5190876.809360461,-2699622.074232926],[-5190112.43907761,-2698437.300294506],[-5188621.9170260485,-2698399.0817803633],[-5188660.135540191,-2699278.1076056426],[-5190341.7501624655,-2699927.822346067],[-5190876.809360461,-2699622.074232926]]]},"properties":null}
		-->		
		}
		*/
		</script>
	</div>
	<div class="MCPRpagecontainer">	
	<div class="MCPRheader">
		<p class="MCPRh2">Propostas</p>
	</div>        
        <?php
        $propostasPerPage = 10;
        $pag = $_GET['pag'];
        $pageName = $_GET['page'];
        if (!$pag) {
            $pagCount = "1";
        } else {
            $pagCount = $pag;
        }
        $start = $pagCount - 1;
        $start = $start * $propostasPerPage;
        $limit = "LIMIT ".$start.",".$propostasPerPage;
       require_once dirname(dirname(dirname(__DIR__))).'/inc/persistence/AprovacaoPropostaDAO.php';
        $aprovacaoPropostaDAO = new AprovacaoPropostaDAO('Planos Regionais - Formulário de Proposta','Plataforma Apoio - Formulário de Usuário');
        switch($tipo){
            case 'moderar': $propostas = $aprovacaoPropostaDAO->getAllByStatus('N',$limit);
                            $regCount = $aprovacaoPropostaDAO->getCountByStatus('N');
                             break;
            case 'moderados': $propostas = $aprovacaoPropostaDAO->getAllByStatus('S',$limit);
                                $regCount = $aprovacaoPropostaDAO->getCountByStatus('S');
                             break;
            case 'arquivados': $propostas = $aprovacaoPropostaDAO->getAllByStatus('A',$limit);
                                $regCount = $aprovacaoPropostaDAO->getCountByStatus('A');
                              break;
            default: echo "erro ao chamar funcao";
                        wp_die();
        }?>
        <div class="MCPRheader">
            <p class="MCPRh2">Total: <?php echo $regCount;?> propostas.</p>
	</div>
        
        <?php
    $contProposta = 0;
    $totPage =(int)($regCount / $propostasPerPage) + 1;
        // botões "Anterior e próximo"
    $ant = $pagCount -1;
    $post = $pagCount +1;
    if ($pagCount>1) {
        echo " <a href='?pag=1&page=$pageName'><< Primeira </a> &nbsp&nbsp&nbsp";
        echo " <a href='?pag=$ant&page=$pageName'>  < Anterior</a> ";
    }
    echo "  | Página $pagCount | ";
    if ($pagCount<$totPage) {
        echo " <a href='?pag=$post&page=$pageName'>Próxima >  </a> &nbsp&nbsp&nbsp";
        echo " <a href='?pag=$totPage&page=$pageName'>    Última >></a>";
    }
    foreach ($propostas as $proposta)
    {
        if($contProposta%2 == 0){
?>
        <div class="MCPRcommentcontainer"> <?php
        
        }else{ 
            ?>
                <div class="MCPRcommentcontainer odd">
            <?php 
            
        } ?>
		<div class="MCPRnester">
            <div class="MCPRcolum">
    			<div class="MCPRidcell">
                                <p class="MCPRh6">Autor</p>
    			</div>
    			<div class="MCPRcontentcell">
                                <p class="MCPRh5"><?php echo $proposta->usuario->nome; ?></p>
    			</div>
    			
    			<div class="MCPRidcell">
                                <p class="MCPRh6">E-mail do autor</p>
    			</div>
    			<div class="MCPRcontentcell">
                                <p class="MCPRh5"><?php echo $proposta->usuario->email; ?></p>
    			</div>
    			<div class="MCPRidcell">
                                <p class="MCPRh6">Endereço do autor</p>
    			</div>
    			<div class="MCPRcontentcell">
                                <p class="MCPRh5"> <?php echo $proposta->usuario->endereco; ?> <br><?php echo $proposta->usuario->cep; ?></p>
    			</div>
                <div class="MCPRcoldois">
                <div class="MCPRidcell">
                    <p class="MCPRh6">Título</p>
                </div>
                <div class="MCPRcontentcell">
                    <p class="MCPRh5"><?php echo $proposta->proposta->titulo; ?></p>
                </div>
                <div class="MCPRidcell">
                    <p class="MCPRh6">Enviado em</p>
                </div>
                <div class="MCPRcontentcell">
                    <p class="MCPRh5"><?php echo $proposta->proposta->submitted; ?></p>
                </div>
                <div class="MCPRidcell">
                    <p class="MCPRh6">Tipo de Marcação</p>
                </div>
                <div class="MCPRcontentcell">
                    <p class="MCPRh5"><?php                     
                        $lat = $proposta->proposta->latitude;                   
                        if($lat == '')
                        {
                            echo "Polígono";
                        }
                        else {
                            echo "Ponto";
                        }
                    ?></p>
                </div>
            </div>
    		</div>
    		
    		
    		<div class="MCPRcoltres">
                <div class="MCPRidcell">
                    <p class="MCPRh6">Colaboração (proposta)</p>
                </div>
                <div class="MCPRcontentcell">
                    <p class="MCPRcommentText"><?php echo $proposta->proposta->colaboracao; ?></p>
                </div>
            </div>
        </div>

        <div class="MCPRcolquatro">
            <div class="MCPRapprovecell">
                <p class="MCPRapprovecell">
                    <div class="onoffswitch">
                        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch<?php echo $proposta->proposta->id; ?>"
                        <?php
                            $campoModeracao = $proposta->proposta->status;
                            if($campoModeracao == "S")
                            {
                                echo " checked ";
                            }

                            if($campoModeracao == "N")
                            {
                                echo " readonly ";
                            }
                            
                            if($campoModeracao == "A")
                            {
                                echo "";
                            }

                        ?>onclick="click_botao('<?php echo $proposta->proposta->id; ?>', this);">
                        <label class="onoffswitch-label" for="myonoffswitch<?php echo $proposta->proposta->id; ?>">
                        <div id="onoffswitch-inner<?php echo $proposta->proposta->id; ?>" class="onoffswitch-inner
                        <?php
                            if($campoModeracao == 'N')
                            {
                                echo " readonly ";
                            }

                        ?>"></div>
                        <div class="onoffswitch-switch"></div>
                        </label>
                    </div>
                </p>
            </div>
        </div>
        <div class="MCPRcontentcell ralign_fix">
            <button class="MCPRbutton" style="vertical-align:middle;" onclick='<?php
            if($proposta->proposta->latitude != ''){
                echo 'drawPointMap(' . $proposta->proposta->latitude . "," . $proposta->proposta->longitude . ')';
            }
            else{
                echo 'drawPolyMap(' . $proposta->proposta->feature . ')';
            }
            // jQuery("#mapCoords").val(echo $comentario->comentario->coordsPerimetro;);
            ?>'><span>Mostrar no mapa </span></button>
        </div>
	</div>
	<!-- FIM DA PROPOSTA -->
	<br>
<?php
        $contProposta++;
    }
                    // botões "Anterior e próximo"
    $ant = $pagCount -1;
    $post = $pagCount +1;
    if($totPage > 1){
        if ($pagCount>1) {
            echo " <a href='?pag=$ant&page=$pageName'><- Anterior</a> ";
        }
        echo "  | Página $pagCount | ";
        if ($pagCount<$totPage) {
            echo " <a href='?pag=$post&page=$pageName'>Próxima -></a>";
        }
    }
?>
    </div>	

    <!--
    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery(".fancybox").fancybox();
        });
    </script>
	-->
    <?php
}