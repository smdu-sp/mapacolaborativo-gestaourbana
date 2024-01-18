<?php

// Aprovacao de Propostas - Bordas da cidade
// 13/05/2016
function bordasdacidade1_tabelaPropostas($tipo,$tipoColaborador) {
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
                
                function atualizarLatLon(id){
                        var lat = document.getElementById("txtLat"+id).value;
                        var lon = document.getElementById("txtLon"+id).value;
                        jQuery.ajax({
				type: 'POST',
				url: '<?php echo admin_url( 'admin-ajax.php' ) ?>',
				data: { action: 'plataformaApoio_latlon_change', lat: lat,lon:lon, id: id },
				success: function(html_ceu){
					//sucess!!
                                        alert("Latitude e Longitude atualizada com sucesso!");
				}
			});	
		}
                
    </script>
	
	<div class="MCPRwrapper">
	<!-- <link rel="stylesheet" href="http://openlayers.org/en/v3.15.0/css/ol.css" type="text/css">
    <script src="http://openlayers.org/en/v3.15.0/build/ol.js"></script> -->
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
        if (!$pag) {
            $pagCount = "1";
        } else {
            $pagCount = $pag;
        }
        $start = $pagCount - 1;
        $start = $start * $propostasPerPage;
        $limit = "LIMIT ".$start.",".$propostasPerPage;
        switch ($tipoColaborador){
            case 'produtor':         require_once dirname(dirname(dirname(__DIR__))).'/inc/persistence/bordas_da_cidade/AprovacaoColabProdutorDAO.php';
                                    $aprovacaoColab = new AprovacaoColabProdutorDAO('Bordas da Cidade - Produtor',
                                            'Plataforma Apoio - Formulário de Usuário');
                                    break;
            case 'turismo':          require_once dirname(dirname(dirname(__DIR__))).'/inc/persistence/bordas_da_cidade/AprovacaoColabTurismoDAO.php';
                                    $aprovacaoColab = new AprovacaoColabTurismoDAO('Bordas da Cidade - Turismo',
                                            'Plataforma Apoio - Formulário de Usuário');
                                    break;
            default: echo "erro ao chamar funcao";
                     wp_die();
        }

        switch($tipo){
            case 'moderar': $colaboracoes = $aprovacaoColab->getAllByStatus('N',$limit);
                            $regCount = $aprovacaoColab->getCountByStatus('N');
                             break;
            case 'moderados': $colaboracoes = $aprovacaoColab->getAllByStatus('S',$limit);
                            $regCount = $aprovacaoColab->getCountByStatus('S');
                             break;
            case 'arquivados': $colaboracoes = $aprovacaoColab->getAllByStatus('A',$limit);
                                $regCount = $aprovacaoColab->getCountByStatus('A');
                              break;
            default: echo "erro ao chamar funcao";
                        wp_die();
        }
    $contColaboracao = 0;
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
                                <p class="MCPRh6">Sexo</p>
    			</div>
    			<div class="MCPRcontentcell">
                                <p class="MCPRh5"><?php echo $colaboracao->usuario->sexo; ?></p>
    			</div>
                        <div class="MCPRidcell">
                                <p class="MCPRh6">Raça</p>
    			</div>
    			<div class="MCPRcontentcell">
                                <p class="MCPRh5"><?php echo $colaboracao->usuario->raca; ?></p>
    			</div>
                        <div class="MCPRidcell">
                                <p class="MCPRh6">Data de Nascimento</p>
    			</div>
    			<div class="MCPRcontentcell">
                                <p class="MCPRh5"><?php echo $colaboracao->usuario->datanasc; ?></p>
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

                        <?php if($tipoColaborador == 'produtor'){                     
                                    $idColab = $colaboracao->colabProdutor->id;
                                    $statusColab = $colaboracao->colabProdutor->status;?>
                        <div class="MCPRcoldois">
                            <div class="MCPRidcell">
                                <p class="MCPRh6">Área unidade produtiva</p>
                            </div>
                            <div class="MCPRcontentcell">
                                <p class="MCPRh5"><?php echo $colaboracao->colabProdutor->areaUnidadeProdutiva; ?></p>
                            </div>
                            <div class="MCPRidcell">
                                <p class="MCPRh6">Área da unidade cultivada</p>
                            </div>
                            <div class="MCPRcontentcell">
                                <p class="MCPRh5"><?php echo $colaboracao->colabProdutor->areaCultivada; ?></p>
                            </div>
                            <div class="MCPRidcell">
                                <p class="MCPRh6">Produtos cultivados</p>
                            </div>
                            <div class="MCPRcontentcell">
                                <p class="MCPRh5"><?php echo $colaboracao->colabProdutor->produtosCultivados; ?></p>
                            </div>
                                            <div class="MCPRidcell">
                                <p class="MCPRh6">Características da produção</p>
                            </div>
                            <div class="MCPRcontentcell">
                                <p class="MCPRh5"><?php echo $colaboracao->colabProdutor->caracteristicasProducao; ?></p>
                            </div>
                            <div class="MCPRidcell">
                                <p class="MCPRh6">Finalidade da produção</p>
                            </div>
                            <div class="MCPRcontentcell">
                                <p class="MCPRh5"><?php echo $colaboracao->colabProdutor->finalidadeProducao; ?></p>
                            </div>
                                        <div class="MCPRidcell">
                                <p class="MCPRh6">Colaboradores</p>
                            </div>
                            <div class="MCPRcontentcell">
                                <p class="MCPRh5"><?php echo $colaboracao->colabProdutor->colaboradores; ?></p>
                            </div>
                            <div class="MCPRidcell">
                                <p class="MCPRh6">Enviado em</p>
                            </div>
                            <div class="MCPRcontentcell">
                                <p class="MCPRh5"><?php echo $colaboracao->colabProdutor->submitted; ?></p>
                            </div>

                            <div class="MCPRcontentcell">
                                <button class="MCPRbutton" style="vertical-align:middle;" onclick='<?php
                                    echo 'drawPointMap(' . $colaboracao->colabProdutor->latitude . "," . $colaboracao->colabProdutor->longitude . ')';
                                ?>'><span>Mostrar no mapa </span></button>
                            </div>
                        </div>
                                <?php } ?>
                                 <?php if($tipoColaborador == 'turismo'){
                                    $idColab = $colaboracao->colabTurismo->id;
                                    $statusColab = $colaboracao->colabTurismo->status;?>
                        <div class="MCPRcoldois">
                            <div class="MCPRidcell">
                                <p class="MCPRh6">Tipo do empreendimento</p>
                            </div>
                            <div class="MCPRcontentcell">
                                <p class="MCPRh5"><?php echo $colaboracao->colabTurismo->tipoEmpreendimento; ?></p>
                            </div>
                            <div class="MCPRidcell">
                                <p class="MCPRh6">Tipo de acesso ao empreendimento</p>
                            </div>
                            <div class="MCPRcontentcell">
                                <p class="MCPRh5"><?php echo $colaboracao->colabTurismo->tipoAcessoEmpreendimento; ?></p>
                            </div>
                            <div class="MCPRidcell">
                                <p class="MCPRh6">Meio de acesso ao empreendimento</p>
                            </div>
                                        <div class="MCPRcontentcell">
                                <p class="MCPRh5"><?php echo $colaboracao->colabTurismo->meioAcessoEmpreendimento; ?></p>
                            </div>
                                        <div class="MCPRidcell">
                                <p class="MCPRh6">Colaboradores</p>
                            </div>
                            <div class="MCPRcontentcell">
                                <p class="MCPRh5"><?php echo $colaboracao->colabTurismo->colaboradores; ?></p>
                            </div>
                                        <div class="MCPRidcell">
                                <p class="MCPRh6">Principal fonte de renda</p>
                            </div>
                            <div class="MCPRcontentcell">
                                <p class="MCPRh5"><?php echo $colaboracao->colabTurismo->principalFonteRenda; ?></p>
                            </div>
                                        <div class="MCPRidcell">
                                <p class="MCPRh6">Enviado em</p>
                            </div>
                            <div class="MCPRcontentcell">
                                <p class="MCPRh5"><?php echo $colaboracao->colabTurismo->submitted; ?></p>
                            </div>

                            <div class="MCPRcontentcell">
                                <button class="MCPRbutton" style="vertical-align:middle;" onclick='<?php
                                    echo 'drawPointMap(' . $colaboracao->colabTurismo->latitude . "," . $colaboracao->colabTurismo->longitude . ')';
                                ?>'><span>Mostrar no mapa </span></button>
                            </div>
                        </div>
                                <?php } ?>
    		</div>
    		

            <?php if($tipoColaborador == 'produtor'){?>
             <div class="MCPRcoltres">
                <div class="MCPRidcell">
                    <p class="MCPRh6">Imagem da produção</p>
                </div>
                <div class="MCPRcontentcell">
                                <a href="<?php echo "../".$colaboracao->colabProdutor->imagem; ?>" target="_blank">
                                    <img src="<?php 
                                    if($colaboracao->colabProdutor->imagem != '')
                                        echo "../".$colaboracao->colabProdutor->imagem; 
                                    else
                                        echo "../wp-content/uploads/bordas_da_cidade/sub_usercolab.png";
                                    ?>" alt="Imagem da produção" width="100%"/>
                                </a>
                </div>
                <div class="MCPRidcell">
                    <p class="MCPRh6">Sugestões críticas e comentários</p>
                </div>
                <div class="MCPRcontentcell">
                    <p class="MCPRcommentText"><?php echo $colaboracao->colabProdutor->sugestoes_criticas_comentarios; ?></p>
                </div>
                <div class="MCPRidcell">
                    <p class="MCPRh6">Recebe assistência técnica</p>
                </div>
                <div class="MCPRcontentcell">
                    <p class="MCPRcommentText"><?php echo $colaboracao->colabProdutor->assistenciaTecnica; ?></p>
                </div>
                <div class="MCPRidcell">
                    <p class="MCPRh6">Propriedade aberta para visitantes</p>
                </div>
                <div class="MCPRcontentcell">
                    <p class="MCPRcommentText"><?php echo $colaboracao->colabProdutor->permiteVisitantes; ?></p>
                </div>
                <div class="MCPRidcell">
                    <p class="MCPRh6">Latitude</p>
                </div>
                <div class="MCPRcontentcell">
                    <input type="text" id="txtLat<?php echo $idColab; ?>" value="<?php echo $colaboracao->colabProdutor->latitude; ?>">
                </div>
                <div class="MCPRidcell">
                    <p class="MCPRh6">Longitude</p>
                </div>
                <div class="MCPRcontentcell">
                    <input type="text" id="txtLon<?php echo $idColab; ?>" value="<?php echo $colaboracao->colabProdutor->longitude; ?>">
                </div>
                <div class="MCPRidcell">
                    <p class="MCPRh6">Atualizar Latitude/Longitude</p>
                </div>
                <div class="MCPRcontentcell">
                    <button id="btnAtualizarLatLon<?php echo $idColab; ?>" onclick="atualizarLatLon(<?php echo $idColab; ?>);">
                        Atualizar </button>
                </div>
            </div>
                    <?php } ?>
            <?php if($tipoColaborador == 'turismo'){?>
            <div class="MCPRcoltres">
                <div class="MCPRidcell">
                    <p class="MCPRh6">Imagem do empreendimento</p>
                </div>
                <div class="MCPRcontentcell">
                                <a href="<?php echo "../".$colaboracao->colabTurismo->imagem; ?>" target="_blank">
                                    <img src="<?php 
                                    if($colaboracao->colabTurismo->imagem != '')
                                        echo "../".$colaboracao->colabTurismo->imagem; 
                                    else
                                        echo "../wp-content/uploads/bordas_da_cidade/sub_usercolab.png";
                                    ?>" alt="Imagem do empreendimento" width="100%"/>
                                </a>
                </div>
                            <div class="MCPRidcell">
                    <p class="MCPRh6">Principais atrativos ecoturísticos</p>
                </div>
                <div class="MCPRcontentcell">
                    <p class="MCPRcommentText"><?php echo $colaboracao->colabTurismo->principaisAtrativos; ?></p>
                </div>
                <div class="MCPRidcell">
                    <p class="MCPRh6">Latitude</p>
                </div>
                <div class="MCPRcontentcell">
                    <input type="text" id="txtLat<?php echo $idColab; ?>" value="<?php echo $colaboracao->colabTurismo->latitude; ?>">
                </div>
                <div class="MCPRidcell">
                    <p class="MCPRh6">Longitude</p>
                </div>
                <div class="MCPRcontentcell">
                    <input type="text" id="txtLon<?php echo $idColab; ?>" value="<?php echo $colaboracao->colabTurismo->longitude; ?>">
                </div>
                <div class="MCPRidcell">
                    <p class="MCPRh6">Atualizar Latitude/Longitude</p>
                </div>
                <div class="MCPRcontentcell">
                    <button id="btnAtualizarLatLon<?php echo $idColab; ?>" onclick="atualizarLatLon(<?php echo $idColab; ?>);">
                        Atualizar </button>
                </div>
            </div>
<!--        </div> -->
                <?php } ?>
                </div>
		<div class="MCPRcolquatro">
			<div class="MCPRapprovecell">
                <p class="MCPRapprovecell">
                    <div class="onoffswitch">
                        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch<?php echo $idColab; ?>"
                        <?php
                            $campoModeracao = $statusColab;
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

                        ?>onclick="click_botao('<?php echo $idColab; ?>', this);">
                        <label class="onoffswitch-label" for="myonoffswitch<?php echo $idColab; ?>">
                        <div id="onoffswitch-inner<?php echo $idColab; ?>" class="onoffswitch-inner
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
            echo " <a href='?pag=$ant&page=$pageName'><- Anterior</a> ";
        }
        echo "  | Página $pagCount | ";
        if ($pagCount<$totPage) {
            echo " <a href='?pag=$post&page=$pageName'>Próxima -></a>";
        }
    }
?>
    </div>	
    <?php
}
