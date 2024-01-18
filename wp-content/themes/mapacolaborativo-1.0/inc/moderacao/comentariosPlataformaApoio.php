<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function comentariosPlataformaApoio_tabela($tipo,$formComment) {
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
    <div class="MCPRpagecontainer">
	<div class="MCPRheader">
		<p class="MCPRh2">Comentários</p>
	</div>
        
        <?php
       // require_once 'inc/persistence/aprovacaoDAO.php';
	require_once dirname(dirname(__DIR__)).'/inc/persistence/AprovacaoComentarioDAO.php';
        //$aprovacaoDAO = new AprovacaoComentarioDAO('Planos Regionais - Formulário de Colaboração','Plataforma Apoio - Formulário de Usuário');
        $commentsPerPage = 10;
        $pag = $_GET['pag'];
        $pageName = $_GET['page'];
        if (!$pag) {
            $pagCount = "1";
        } else {
            $pagCount = $pag;
        }
        $start = $pagCount - 1;
        $start = $start * $commentsPerPage;
        $limit = "LIMIT ".$start.",".$commentsPerPage;
        $aprovacaoDAO = new AprovacaoComentarioDAO($formComment,'Plataforma Apoio - Formulário de Usuário');
        switch($tipo){
            case 'moderar': $comentarios = $aprovacaoDAO->getAllByStatus('N',$limit);
                              $regCount = $aprovacaoDAO->getCountByStatus('N');
                             break;
            case 'moderados': $comentarios = $aprovacaoDAO->getAllByStatus('S',$limit);
                              $regCount = $aprovacaoDAO->getCountByStatus('S');
                             break;
            case 'arquivados': $comentarios = $aprovacaoDAO->getAllByStatus('A',$limit);
                              $regCount = $aprovacaoDAO->getCountByStatus('A');
                              break;
            default: echo "erro ao chamar funcao";
                        wp_die();
        }?>
        <div class="MCPRheader">
            <p class="MCPRh2">Total: <?php echo $regCount;?> comentários.</p>
	</div>
        
        <?php
    $contComment = 0;
    $totPage =(int)($regCount / $commentsPerPage) + 1;
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
    foreach ($comentarios as $comentario)
    {
        if($contComment%2 == 0){
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
                                <p class="MCPRh5"><?php echo $comentario->usuario->nome; ?></p>
        		</div>
        		
        		<div class="MCPRidcell">
                                <p class="MCPRh6">E-mail do autor</p>
        		</div>
        		<div class="MCPRcontentcell">
                                <p class="MCPRh5"><?php echo $comentario->usuario->email; ?></p>
        		</div>
        		<div class="MCPRidcell">
                                <p class="MCPRh6">Endereço do autor</p>
        		</div>
        		<div class="MCPRcontentcell">
                                <p class="MCPRh5"> <?php echo $comentario->usuario->endereco; ?> <br><?php echo $comentario->usuario->cep; ?></p>
        		</div>
                        	<div class="MCPRidcell">
                                <p class="MCPRh6">Posicionamento do autor</p>
        		</div>
        		<div class="MCPRcontentcell">
                                <p class="MCPRh5"> <?php echo $comentario->comentario->posicionamento; ?></p>
        		</div>

                <div class="MCPRcoldois">
                    <div class="MCPRidcell">
                        <p class="MCPRh6">Título</p>
                    </div>
                    <div class="MCPRcontentcell">
                        <p class="MCPRh5"><?php echo $comentario->comentario->titulo; ?></p>
                    </div>
                    <div class="MCPRidcell">
                        <p class="MCPRh6">Enviado em</p>
                    </div>
                    <div class="MCPRcontentcell">
                        <p class="MCPRh5"><?php echo $comentario->comentario->submitted; ?></p>
                    </div>
                    <div class="MCPRidcell">
                        <p class="MCPRh6">ID do perímetro</p>
                    </div>
                    <div class="MCPRcontentcell">
                        <p class="MCPRh5"><?php echo $comentario->comentario->idFeature; ?></p>
                    </div>
                                <?php if($comentario->comentario->regiaoDescricaoPerimetro != null){?>
                                    <div class="MCPRidcell">
                                            <p class="MCPRh6">Sessão comentada</p>
                                    </div>
                                    <div class="MCPRcontentcell">
                                            <p class="MCPRh5"><?php echo $comentario->comentario->regiaoDescricaoPerimetro; ?></p>
                                    </div>
                                <?php } ?>
                </div>
        	</div>
        	
            <div class="MCPRcoltres">
                <div class="MCPRidcell">
                    <p class="MCPRh6">Número de Apoios</p>
                </div>
                <div class="MCPRcontentcell">
                    <p class="MCPRcommentText"><?php echo $comentario->comentario->numApoios; ?></p>
                </div>
                <div class="MCPRidcell">
                    <p class="MCPRh6">Colaboração (comentário)</p>
                </div>
                <div class="MCPRcontentcell">
                    <p class="MCPRcommentText"><?php echo $comentario->comentario->comentario; ?></p>
                </div>
            </div>
        </div>
		
		<div class="MCPRcolquatro">
			<div class="MCPRapprovecell">
                <p class="MCPRapprovecell">
                    <div class="onoffswitch">
                        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch<?php echo $comentario->comentario->id; ?>"
                        <?php
                            $campoModeracao = $comentario->comentario->status;
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

                        ?>onclick="click_botao('<?php echo $comentario->comentario->id; ?>', this);">
                        <label class="onoffswitch-label" for="myonoffswitch<?php echo $comentario->comentario->id; ?>">
                        <div id="onoffswitch-inner<?php echo $comentario->comentario->id; ?>" class="onoffswitch-inner
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
	<!-- FIM DO COMENTARIO -->
	<br>
<?php
        $contComment++;

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
    </table>

    
    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery(".fancybox").fancybox();
        });
    </script>
    <?php
}