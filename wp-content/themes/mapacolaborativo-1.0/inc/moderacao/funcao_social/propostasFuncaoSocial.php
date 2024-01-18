<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function funcaosocial1_tabelaPropostas($tipo) {
?>
    <br/>
    <br/>
    <script type='text/javascript'>
		function click_botao(x, cb)
		{
			var myObj = document.getElementById("onoffswitch-inner"+x);
			var dataObj = { action: 'plataformaApoio_status_change',  id: x };
			if (cb.readOnly)
			{
				cb.readOnly = false;
				jQuery(myObj).removeClass('readonly');
			}			
			if (document.getElementById("myonoffswitch"+x).checked)
			{
				dataObj.status = 'S';
			}
			else
			{
				dataObj.status = 'A';
			}
                        var categorias = document.getElementsByName('categorizacao'+x);
                        var cat;
                        for(var i = 0; i < categorias.length; i++){
                            if(categorias[i].checked){
                                cat = categorias[i].value;
                            }
                        }
                        if(cat !== null)
                            dataObj.cat = cat;
                        
                        console.log(dataObj);
			jQuery.ajax({
				type: 'POST',
				url: '<?php echo admin_url( 'admin-ajax.php' ) ?>',
				data: dataObj,
				success: function(html_ceu){
					//sucess!!
				}
			});
		}
                function atualizarLatLon(id){
                        var lat = document.getElementById("txtLat"+id).value;
                        var lon = document.getElementById("txtLon"+id).value;
                        jQuery.ajax({
				type: 'POST',
				url: '<?php echo admin_url( 'admin-ajax.php' ) ?>',
				data: { action: 'plataformaApoio_latlon_change', lat: lat,lon:lon, id: id },
				success: function(html_ceu){
                                        alert("Latitude e Longitude atualizada com sucesso!");
				}
			});	
		}
    </script>
	
	<div class="MCPRwrapper">
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
		// ESTILO ANTIGO
		var image = new ol.style.Circle({
			radius: 20,
			fill: new ol.style.Fill({
				color: 'rgba(255,0,0, 0.5)'
			}),
			stroke: new ol.style.Stroke({color: 'red', width: 1})
		});
		var styles = {
			'Point': new ol.style.Style({
			image: image
		}),        
		'Polygon': new ol.style.Style({
			stroke: new ol.style.Stroke({
				color: 'purple',
				width: 1
			}),
			fill: new ol.style.Fill({
				color: 'rgba(0, 0, 255, 0.1)'
				})
			})
		};

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
				
			});
			vectorLayer.getSource().addFeature(ponto);
			map.getView().setZoom(15);
			map.getView().setCenter(ol.proj.transform([lon, lat], 'EPSG:4326', 'EPSG:3857'));
			// map.getView().fit(vectorLayer.getSource().getExtent(), map.getSize());
		}
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
        $tipo_aprovacao = $_GET['aprovacao'];
        switch($tipo_aprovacao){
            case 'Apontado': 
                            $tipo_aprovacao_query = " AND a.tipo_aprovacao = 'Apontado'";
                            break;
            case 'Cadastrado': 
                                $tipo_aprovacao_query = " AND a.tipo_aprovacao = 'Cadastrado'";
                                break;
            default: $tipo_aprovacao_query = "";
                     break;
        }
        if (!$pag) {
            $pagCount = "1";
        } else {
            $pagCount = $pag;
        }
        $start = $pagCount - 1;
        $start = $start * $propostasPerPage;
        $limit = "LIMIT ".$start.",".$propostasPerPage;
        require_once dirname(dirname(dirname(__DIR__))).'/inc/persistence/funcao_social/AprovacaoColabImovelDAO.php';
        $aprovacaoColabImovelDAO = new AprovacaoColabImovelDAO('Função Social - Formulário Marcação Imóvel','Plataforma Apoio - Formulário de Usuário');
        switch($tipo){
            case 'moderar': $colaboracoes = $aprovacaoColabImovelDAO->getAllByStatus('N',$limit);
                                            $regCount = $aprovacaoColabImovelDAO->getCountByStatus('N');
                             break;
            case 'moderados': $colaboracoes = $aprovacaoColabImovelDAO->getAllByStatus('S',$limit, $tipo_aprovacao_query);
                                                $regCount = $aprovacaoColabImovelDAO->getCountByStatus('S', $tipo_aprovacao_query);
                             break;
            case 'arquivados': $colaboracoes = $aprovacaoColabImovelDAO->getAllByStatus('A',$limit);
                                                $regCount = $aprovacaoColabImovelDAO->getCountByStatus('A');
                              break;
            default: echo "erro ao chamar funcao";
                        wp_die();
        }
    $totPage =(int)($regCount / $propostasPerPage) + 1;
        // botões "Anterior e próximo"
    $ant = $pagCount -1;
    $post = $pagCount +1;
    if($pageName == 'funcaoSocial_propsModeradas'){
        echo " <a href='?page=$pageName&aprovacao=Apontado'> Filtrar por Apontado </a> &nbsp&nbsp&nbsp || &nbsp&nbsp&nbsp";
        echo " <a href='?page=$pageName&aprovacao=Cadastrado'> Filtrar por Cadastrado</a> <br><br>";
    }
    if ($pagCount>1) {
        echo " <a href='?pag=1&page=$pageName&aprovacao=$tipo_aprovacao'><< Primeira </a> &nbsp&nbsp&nbsp";
        echo " <a href='?pag=$ant&page=$pageName&aprovacao=$tipo_aprovacao'>  < Anterior</a> ";
    }
    echo "  | Página $pagCount | ";
    if ($pagCount<$totPage) {
        echo " <a href='?pag=$post&page=$pageName&aprovacao=$tipo_aprovacao'>Próxima >  </a> &nbsp&nbsp&nbsp";
        echo " <a href='?pag=$totPage&page=$pageName&aprovacao=$tipo_aprovacao'>    Última >></a>";
    }
    foreach ($colaboracoes as $colaboracao)
    {
        if($contColaboracao%2 == 0){
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
                                <p class="MCPRh5"><?php echo $colaboracao->usuario->nome; ?></p>
    			</div>
    			
    			<div class="MCPRidcell">
                                <p class="MCPRh6">E-mail do autor</p>
    			</div>
    			<div class="MCPRcontentcell">
                                <p class="MCPRh5"><?php echo $colaboracao->usuario->email; ?></p>
    			</div>
    			<div class="MCPRidcell">
                                <p class="MCPRh6">Endereço do autor</p>
    			</div>
    			<div class="MCPRcontentcell">
                                <p class="MCPRh5"> <?php echo $colaboracao->usuario->endereco; ?> <br><?php echo $colaboracao->usuario->cep; ?></p>
    			</div>
                <div class="MCPRcoldois">
                    <div class="MCPRidcell">
                        <p class="MCPRh6">Endereço</p>
                    </div>
                    <div class="MCPRcontentcell">
                        <p class="MCPRh5"><?php echo $colaboracao->colabImovel->logradouro; ?></p>
                    </div>
                    <div class="MCPRidcell">
                        <p class="MCPRh6">Número</p>
                    </div>
                    <div class="MCPRcontentcell">
                        <p class="MCPRh5"><?php echo $colaboracao->colabImovel->numero; ?></p>
                    </div>
                    <div class="MCPRidcell">
                        <p class="MCPRh6">Ponto de Referência</p>
                    </div>
                    <div class="MCPRcontentcell">
                        <p class="MCPRh5"><?php echo $colaboracao->colabImovel->pontoReferencia; ?></p>
                    </div>
                    <div class="MCPRidcell">
                        <p class="MCPRh6">Enviado em</p>
                    </div>
                    <div class="MCPRcontentcell">
                        <p class="MCPRh5"><?php echo $colaboracao->colabImovel->submitted; ?></p>
                    </div>

                    <div class="MCPRcontentcell">
                        <button class="MCPRbutton" style="vertical-align:middle;" onclick='<?php
                            echo 'drawPointMap(' . $colaboracao->colabImovel->latitude . "," . $colaboracao->colabImovel->longitude . ')';
                        ?>'><span>Mostrar no mapa </span></button>
                    </div>
                </div>
    		</div>
    		
    		
    		<div class="MCPRcoltres">
                    <?php if($tipo !== 'moderar'){?>
                        <div class="MCPRidcell">
                                <p class="MCPRh6">Quantidade de apoios</p>
                        </div>
                        <div class="MCPRcontentcell">
                                <p class="MCPRh5"><?php echo $colaboracao->colabImovel->numApoios; ?></p>
                        </div>
                    <?php }?>
                <div class="MCPRidcell">
                    <p class="MCPRh6">Como é o Imóvel</p>
                </div>
                <div class="MCPRcontentcell">
                    <p class="MCPRcommentText"><?php echo $colaboracao->colabImovel->caracteristicaImovel; ?></p>
                </div>
                            <div class="MCPRidcell">
                    <p class="MCPRh6">O imóvel Possui</p>
                </div>
                <div class="MCPRcontentcell">
                    <p class="MCPRcommentText"><?php echo $colaboracao->colabImovel->possesDoImovel; ?></p>
                </div>
                                <div class="MCPRidcell">
                    <p class="MCPRh6">Tempo sem utilizacao</p>
                </div>
                <div class="MCPRcontentcell">
                    <p class="MCPRcommentText"><?php echo $colaboracao->colabImovel->tempoInutilizado; ?></p>
                </div>
                <div class="MCPRidcell">
                    <p class="MCPRh6">Latitude</p>
                </div>
                <div class="MCPRcontentcell">
                    <input type="text" id="txtLat<?php echo $colaboracao->colabImovel->id; ?>" value="<?php echo $colaboracao->colabImovel->latitude; ?>">
                </div>
                <div class="MCPRidcell">
                    <p class="MCPRh6">Longitude</p>
                </div>
                <div class="MCPRcontentcell">
                    <input type="text" id="txtLon<?php echo $colaboracao->colabImovel->id; ?>" value="<?php echo $colaboracao->colabImovel->longitude; ?>">
                </div>
                <div class="MCPRidcell">
                    <p class="MCPRh6">Atualizar Latitude/Longitude</p>
                </div>
                <div class="MCPRcontentcell">
                    <button id="btnAtualizarLatLon<?php echo $colaboracao->colabImovel->id; ?>" onclick="atualizarLatLon(<?php echo $colaboracao->colabImovel->id; ?>);">
                        Atualizar </button>
                </div>
                <?php if($tipo !== 'moderados'){?>
                <div class="MCPRidcell">
                    <p class="MCPRh6">Categorização</p>
                </div>
                <div class="MCPRcontentcell">
                    <input type="radio" name="categorizacao<?php echo $colaboracao->colabImovel->id; ?>"  value="Apontado">Apontado<br>
                    <input type="radio" name="categorizacao<?php echo $colaboracao->colabImovel->id; ?>" value="Cadastrado">Cadastrado
                </div>
                <?php } ?>    
            </div>
        </div>
		<div class="MCPRcolquatro">
			<div class="MCPRapprovecell">
                <p class="MCPRapprovecell">
                    <div class="onoffswitch">
                        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch<?php echo $colaboracao->colabImovel->id; ?>"
                        <?php
                            $campoModeracao = $colaboracao->colabImovel->status;
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

                        ?>onclick="click_botao('<?php echo $colaboracao->colabImovel->id; ?>', this);">
                        <label class="onoffswitch-label" for="myonoffswitch<?php echo $colaboracao->colabImovel->id; ?>">
                        <div id="onoffswitch-inner<?php echo $colaboracao->colabImovel->id; ?>" class="onoffswitch-inner
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
	</div>
	<!-- FIM DA PROPOSTA -->
	<br>
<?php
        $contColaboracao++;
    }
                // botões "Anterior e próximo"
    $ant = $pagCount -1;
    $post = $pagCount +1;
    if($totPage > 1){
        if ($pagCount>1) {
            echo " <a href='?pag=$ant&page=$pageName&aprovacao=$tipo_aprovacao'><- Anterior</a> ";
        }
        echo "  | Página $pagCount | ";
        if ($pagCount<$totPage) {
            echo " <a href='?pag=$post&page=$pageName&aprovacao=$tipo_aprovacao'>Próxima -></a>";
        }
    }
?>
    </div>	
    <?php
}