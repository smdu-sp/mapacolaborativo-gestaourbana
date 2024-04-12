
<?php

  add_editor_style('style.css');
  add_editor_style('css/style-entenda-introducao.css');
  add_editor_style('css/style-entenda-etapas.css');


add_action( 'admin_enqueue_scripts', 'my_enqueue_css' );
function my_enqueue_css() {
    //if( 'edit.php' != $hook )
        //return;
//echo '<link rel="stylesheet" type="text/css" href="stylefs.css">'."\n";
    global $hook_suffix;
    if (
            $hook_suffix == 'toplevel_page_planosRegionais_moderacao'
            || $hook_suffix == 'planos-regionais_page_planosRegionais_pendentes'
            || $hook_suffix == 'planos-regionais_page_planosRegionais_moderadas'
            || $hook_suffix == 'planos-regionais_page_planosRegionais_arquivados'
            || $hook_suffix == 'planos-regionais_page_planosRegionais_propsPendentes'
            || $hook_suffix == 'planos-regionais_page_planosRegionais_propsModeradas'
            || $hook_suffix == 'planos-regionais_page_planosRegionais_propsArquivados'
            || $hook_suffix == 'toplevel_page_funcaoSocial_moderacao'
            || $hook_suffix == 'funcao-social_page_funcaoSocial_pendentes'
            || $hook_suffix == 'funcao-social_page_funcaoSocial_moderadas'
            || $hook_suffix == 'funcao-social_page_funcaoSocial_arquivados'
            || $hook_suffix == 'funcao-social_page_funcaoSocial_propsPendentes'
            || $hook_suffix == 'funcao-social_page_funcaoSocial_propsModeradas'
            || $hook_suffix == 'funcao-social_page_funcaoSocial_propsArquivados'
            || $hook_suffix == 'toplevel_page_bordasdacidade_propsModeracao'
            || $hook_suffix == 'bordas-da-cidade_page_bordasdacidade_produtor_propsPendentes'
            || $hook_suffix == 'bordas-da-cidade_page_bordasdacidade_produtor_propsModeradas'
            || $hook_suffix == 'bordas-da-cidade_page_bordasdacidade_produtor_propsArquivados'
            || $hook_suffix == 'bordas-da-cidade_page_bordasdacidade_turismo_propsPendentes'
            || $hook_suffix == 'bordas-da-cidade_page_bordasdacidade_turismo_propsModeradas'
            || $hook_suffix == 'bordas-da-cidade_page_bordasdacidade_turismo_propsArquivados'
        )
    {
?>
        <style type='text/css'>
        @import url('<?php echo bloginfo('template_url'); ?>/css/style-mapa-colaborativo.css');
        </style>		
<?php
    }
}

// Menu de Moderação da Plataforma Mapa Colaborativo - Gestão Urbana
add_action( 'wp_ajax_plataformaApoio_status_change', 'plataformaApoio_status_change_ajax' );
function plataformaApoio_status_change_ajax()
{
    require_once dirname(__FILE__).'/inc/persistence/AprovacaoDAO.php';
    //$comentarioDAO = new ComentarioDAO();
    if(isset($_POST['id']) && isset($_POST['status']))
    {
        $id = (string)$_POST['id'];
        $status = (string)$_POST['status'];
        AprovacaoDAO::updateColaboracaoStatus($status,$id);
        if(isset($_POST['cat'])){
            require_once dirname(__FILE__).'/inc/persistence/funcao_social/AprovacaoColabImovelDAO.php';
            $aprovacaoColabImovelDAO = new AprovacaoColabImovelDAO('Função Social - Formulário Marcação Imóvel','Plataforma Apoio - Formulário de Usuário');
            $categoria = (string)$_POST['cat'];
            $aprovacaoColabImovelDAO->insertCategoriaProposta($id, $categoria);
        }
        die();
    }

}
add_action( 'wp_ajax_plataformaApoio_latlon_change', 'plataformaApoio_latlon_change_ajax' );
function plataformaApoio_latlon_change_ajax()
{
    require_once dirname(__FILE__).'/inc/persistence/AprovacaoDAO.php';
    //$comentarioDAO = new ComentarioDAO();
    if(isset($_POST['id']) && isset($_POST['lat']) && isset($_POST['lon']))
    {
        $id = (string)$_POST['id'];
        $lat = (string)$_POST['lat'];
        $lon = (string)$_POST['lon'];
        AprovacaoDAO::updateLatitudeLongitude($id, $lat, $lon);
        die();
    }
}

add_action('admin_menu', 'plataformaApoio_menu_pages');
function plataformaApoio_menu_pages() {
    /*Menu Models:
       ** To add a menu:
     * add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function);  
        ** To add a submenu
     * add_submenu_page($parent_menu_slug, $page_title, $sub_menu_title, $capability, $menu_slug, $function);   */
    // Add the top-level admin menu
    add_menu_page('Planos Regionais - Moderação', 'Planos Regionais', 'planosregionais', 'planosRegionais_moderacao',
                    'planosRegionais_pendentes');
    add_menu_page('Função Social - Moderação', 'Função Social', 'funcaosocial', 'funcaoSocial_moderacao',
                    'funcaoSocial_pendentes');
    add_menu_page('Bordas da Cidade - Moderação', 'Bordas da Cidade', 'bordas_da_cidade', 'bordasdacidade_propsModeracao',
                    'bordasdacidade_produtor_propsPendentes');
    
    add_submenu_page('planosRegionais_moderacao', 'Comentários Pendentes', 'Comentários Pendentes',
                    'planosregionais', 'planosRegionais_pendentes', 'planosRegionais_pendentes');
    add_submenu_page('planosRegionais_moderacao', 'Comentários Aprovados', 'Comentários Aprovados',
                    'planosregionais', 'planosRegionais_moderadas', 'planosRegionais_moderadas');
    add_submenu_page('planosRegionais_moderacao', 'Comentários Arquivados', 'Comentários Arquivados',
                    'planosregionais', 'planosRegionais_arquivados', 'planosRegionais_arquivados');
    //Moderação Comentários Função Social
    add_submenu_page('funcaoSocial_moderacao', 'Comentários Pendentes', 'Comentários Pendentes',
                    'funcaosocial', 'funcaoSocial_pendentes', 'funcaoSocial_pendentes');
    add_submenu_page('funcaoSocial_moderacao', 'Comentários Aprovados', 'Comentários Aprovados',
                    'funcaosocial', 'funcaoSocial_moderadas', 'funcaoSocial_moderadas');
    add_submenu_page('funcaoSocial_moderacao', 'Comentários Arquivados', 'Comentários Arquivados',
                    'funcaosocial', 'funcaoSocial_arquivados', 'funcaoSocial_arquivados');
	
    add_submenu_page('planosRegionais_moderacao', 'Propostas Pendentes', 'Propostas Pendentes',
                    'planosregionais', 'planosRegionais_propsPendentes', 'planosRegionais_propsPendentes');
    add_submenu_page('planosRegionais_moderacao', 'Propostas Aprovadas', 'Propostas Aprovadas',
                    'planosregionais', 'planosRegionais_propsModeradas', 'planosRegionais_propsModeradas');
    add_submenu_page('planosRegionais_moderacao', 'Propostas Arquivadas', 'Propostas Arquivadas',
                    'planosregionais', 'planosRegionais_propsArquivados', 'planosRegionais_propsArquivados');

	// endauthor Renan
    	// author: Lucas - 13/05/2016 - Menu Propostas(funcão social)
    add_submenu_page('funcaoSocial_moderacao', 'Propostas Pendentes', 'Propostas Pendentes',
                    'funcaosocial', 'funcaoSocial_propsPendentes', 'funcaoSocial_propsPendentes');
    add_submenu_page('funcaoSocial_moderacao', 'Propostas Aprovadas', 'Propostas Aprovadas',
                    'funcaosocial', 'funcaoSocial_propsModeradas', 'funcaoSocial_propsModeradas');
    add_submenu_page('funcaoSocial_moderacao', 'Propostas Arquivadas', 'Propostas Arquivadas',
                    'funcaosocial', 'funcaoSocial_propsArquivados', 'funcaoSocial_propsArquivados');
    
        	// 08/06/2016 - Menu Propostas(Bordas da Cidade - Produção)
    add_submenu_page('bordasdacidade_propsModeracao', 'Propostas Pendentes', 'Produtor Propostas Pendentes',
                    'bordas_da_cidade', 'bordasdacidade_produtor_propsPendentes', 'bordasdacidade_produtor_propsPendentes');
    add_submenu_page('bordasdacidade_propsModeracao', 'Propostas Aprovadas', 'Produtor Propostas Aprovadas',
                    'bordas_da_cidade', 'bordasdacidade_produtor_propsModeradas', 'bordasdacidade_produtor_propsModeradas');
    add_submenu_page('bordasdacidade_propsModeracao', 'Propostas Arquivadas', 'Produtor Propostas Arquivadas',
                    'bordas_da_cidade', 'bordasdacidade_produtor_propsArquivados', 'bordasdacidade_produtor_propsArquivados');
    
        	// 08/06/2016 - Menu Propostas(Bordas da Cidade - Turismo)
    add_submenu_page('bordasdacidade_propsModeracao', 'Propostas Pendentes', 'Turismo Propostas Pendentes',
                    'bordas_da_cidade', 'bordasdacidade_turismo_propsPendentes', 'bordasdacidade_turismo_propsPendentes');
    add_submenu_page('bordasdacidade_propsModeracao', 'Propostas Aprovadas', 'Turismo Propostas Aprovadas',
                    'bordas_da_cidade', 'bordasdacidade_turismo_propsModeradas', 'bordasdacidade_turismo_propsModeradas');
    add_submenu_page('bordasdacidade_propsModeracao', 'Propostas Arquivadas', 'Turismo Propostas Arquivadas',
                    'bordas_da_cidade', 'bordasdacidade_turismo_propsArquivados', 'bordasdacidade_turismo_propsArquivados');
    
    
	// endauthor Lucas
}

function planosRegionais_pendentes()
{	
	//capability
    if (!current_user_can('planosregionais'))
    {
        wp_die('Você não tem permissão para acessar esta página');
    }
    include_once (dirname(__FILE__).'/inc/moderacao/comentariosPlataformaApoio.php');
    comentariosPlataformaApoio_tabela('moderar','Planos Regionais - Formulário de Colaboração');
}


function planosRegionais_moderadas()
{
	//capability
    if (!current_user_can('planosregionais'))
    {
        wp_die('Você não tem permissão para acessar esta página');
    }
    include_once (dirname(__FILE__).'/inc/moderacao/comentariosPlataformaApoio.php');
    comentariosPlataformaApoio_tabela('moderados','Planos Regionais - Formulário de Colaboração');
}

function planosRegionais_arquivados()
{
	//capability
    if (!current_user_can('planosregionais'))
    {
        wp_die('Você não tem permissão para acessar esta página');
    }
    include_once (dirname(__FILE__).'/inc/moderacao/comentariosPlataformaApoio.php');
    comentariosPlataformaApoio_tabela('arquivados','Planos Regionais - Formulário de Colaboração');
}
/*Função social - Comentários*/
function funcaoSocial_pendentes()
{	
	//capability
    if (!current_user_can('funcaosocial'))
    {
        wp_die('Você não tem permissão para acessar esta página');
    }
    include_once (dirname(__FILE__).'/inc/moderacao/comentariosPlataformaApoio.php');
    comentariosPlataformaApoio_tabela('moderar','Função Social - Formulário de Comentários');
}


function funcaoSocial_moderadas()
{
	//capability
    if (!current_user_can('funcaosocial'))
    {
        wp_die('Você não tem permissão para acessar esta página');
    }
    include_once (dirname(__FILE__).'/inc/moderacao/comentariosPlataformaApoio.php');
    comentariosPlataformaApoio_tabela('moderados','Função Social - Formulário de Comentários');
}

function funcaoSocial_arquivados()
{
	//capability
    if (!current_user_can('funcaosocial'))
    {
        wp_die('Você não tem permissão para acessar esta página');
    }
    include_once (dirname(__FILE__).'/inc/moderacao/comentariosPlataformaApoio.php');
    comentariosPlataformaApoio_tabela('arquivados','Função Social - Formulário de Comentários');
}


// author: Renan - 14/04/2016
function planosRegionais_propsPendentes()
{	
	//capability
    if (!current_user_can('planosregionais'))
    {
        wp_die('Você não tem permissão para acessar esta página');
    }
    include_once (dirname(__FILE__).'/inc/moderacao/planos_regionais/propostasPlanosRegionais.php');
    planosRegionais1_tabelaPropostas('moderar');
}


function planosRegionais_propsModeradas()
{
	//capability
    if (!current_user_can('planosregionais'))
    {
        wp_die('Você não tem permissão para acessar esta página');
    }
    include_once (dirname(__FILE__).'/inc/moderacao/planos_regionais/propostasPlanosRegionais.php');
    planosRegionais1_tabelaPropostas('moderados');
}

function planosRegionais_propsArquivados()
{
	//capability
    if (!current_user_can('planosregionais'))
    {
        wp_die('Você não tem permissão para acessar esta página');
    }
    include_once (dirname(__FILE__).'/inc/moderacao/planos_regionais/propostasPlanosRegionais.php');
    planosRegionais1_tabelaPropostas('arquivados');
}
// endauthor Renan

// author: Lucas - 13/05/2016
function funcaoSocial_propsPendentes()
{	
	//capability
    if (!current_user_can('funcaosocial'))
    {
        wp_die('Você não tem permissão para acessar esta página');
    }
    include_once (dirname(__FILE__).'/inc/moderacao/funcao_social/propostasFuncaoSocial.php');
    funcaosocial1_tabelaPropostas('moderar');
}


function funcaoSocial_propsModeradas()
{
	//capability
    if (!current_user_can('funcaosocial'))
    {
        wp_die('Você não tem permissão para acessar esta página');
    }
    include_once (dirname(__FILE__).'/inc/moderacao/funcao_social/propostasFuncaoSocial.php');
    funcaosocial1_tabelaPropostas('moderados');
}

function funcaoSocial_propsArquivados()
{
	//capability
    if (!current_user_can('funcaosocial'))
    {
        wp_die('Você não tem permissão para acessar esta página');
    }
    include_once (dirname(__FILE__).'/inc/moderacao/funcao_social/propostasFuncaoSocial.php');
    funcaosocial1_tabelaPropostas('arquivados');
}

// author: Lucas - 08/06
function bordasdacidade_produtor_propsPendentes()
{	
	//capability
    if (!current_user_can('bordas_da_cidade'))
    {
        wp_die('Você não tem permissão para acessar esta página');
    }
    include_once (dirname(__FILE__).'/inc/moderacao/bordas_da_cidade/propostasBordasDaCidade.php');
    bordasdacidade1_tabelaPropostas('moderar','produtor');
}


function bordasdacidade_produtor_propsModeradas()
{
	//capability
    if (!current_user_can('bordas_da_cidade'))
    {
        wp_die('Você não tem permissão para acessar esta página');
    }
    include_once (dirname(__FILE__).'/inc/moderacao/bordas_da_cidade/propostasBordasDaCidade.php');
    bordasdacidade1_tabelaPropostas('moderados','produtor');
}

function bordasdacidade_produtor_propsArquivados()
{
	//capability
    if (!current_user_can('bordas_da_cidade'))
    {
        wp_die('Você não tem permissão para acessar esta página');
    }
    include_once (dirname(__FILE__).'/inc/moderacao/bordas_da_cidade/propostasBordasDaCidade.php');
    bordasdacidade1_tabelaPropostas('arquivados','produtor');
}

// author: Lucas - 08/06
function bordasdacidade_turismo_propsPendentes()
{	
	//capability
    if (!current_user_can('bordas_da_cidade'))
    {
        wp_die('Você não tem permissão para acessar esta página');
    }
    include_once (dirname(__FILE__).'/inc/moderacao/bordas_da_cidade/propostasBordasDaCidade.php');
    bordasdacidade1_tabelaPropostas('moderar','turismo');
}


function bordasdacidade_turismo_propsModeradas()
{
	//capability
    if (!current_user_can('bordas_da_cidade'))
    {
        wp_die('Você não tem permissão para acessar esta página');
    }
    include_once (dirname(__FILE__).'/inc/moderacao/bordas_da_cidade/propostasBordasDaCidade.php');
    bordasdacidade1_tabelaPropostas('moderados','turismo');
}

function bordasdacidade_turismo_propsArquivados()
{
	//capability
    if (!current_user_can('bordas_da_cidade'))
    {
        wp_die('Você não tem permissão para acessar esta página');
    }
    include_once (dirname(__FILE__).'/inc/moderacao/bordas_da_cidade/propostasBordasDaCidade.php');
    bordasdacidade1_tabelaPropostas('arquivados','turismo');
}


add_filter('wpcf7_validate_text*', 'cf7_custom_form_validation', 10, 2); // Req. text field
function cf7_custom_form_validation( $result, $tag )
{
        $tag = new WPCF7_Shortcode( $tag );
        
        //validando CEP
        if ( $tag->name == 'minhocaoCep' || $tag->name == 'wifiCep' || $tag->name == 'wifiCepProposta' || $tag->name == 'CEP' )
        {
            $campoCep = $tag->name;
           // echo $campoCep;
            if ( isset( $_POST[$campoCep] ) )
            {
                $c = trim( $_POST[$campoCep] );
                $cep =  preg_replace("/[^0-9]/", "", $c);
                $cont = (int) strlen($cep);
                
                if($cont != 8)
                {
                    $result->invalidate( $tag, "Digite um CEP V&aacute;lido." );
                }
            }
        }

        
        //validando CPF/CNPJ
        if ( $tag->name == 'CPFCNPJ' )
        {
            $campoDoc = $tag->name;
            if ( isset( $_POST[$campoDoc] ) )
            {
                $c = trim( $_POST[$campoDoc] );
                $doc =  preg_replace("/[^0-9]/", "", $c);
                $cont = (int) strlen($doc);
                
                if($cont != 11 && $cont != 14)
                {
                    $result->invalidate( $tag, "Digite um documento v&aacute;lido." );
                }
            }
        }
        
        
        return $result;
}
function getCountMail($email){
    require_once 'inc/persistence/UsuarioDAO.php';
    $usuarioDAO = new UsuarioDAO('Plataforma Apoio - Formulário de Usuário');
    return $usuarioDAO->getCountEmail($email);
    
}
function getCountApoiosByMail($mail,$idColab){
    require_once 'inc/persistence/UsuarioDAO.php';
    $usuarioDAO = new UsuarioDAO("Plataforma Apoio - Formulário de Usuário");
    $usuarioDAO->formApoio = "Plataforma Apoio - Formulário de Apoio";
    return $usuarioDAO->getCountApoioColab($mail, $idColab);
}
function computarApoio($idColaboracao){
    require_once 'inc/entity/Colaboracao.php';
    return Colaboracao::computarApoioColaboracao($idColaboracao);
}

//Validação para salvar arquivos na pasta correspondente ao projeto
add_filter('cfdb_form_data', 'cfdbFilterSaveFile');
function cfdbFilterSaveFile($formData)
{
    
            // Bordas da Cidade - Produtor
        if ($formData->title == 'Bordas da Cidade - Produtor' && isset($formData->uploaded_files['imagem_colaboracao']))
        {
            // CHANGE THIS: directory where the file will be saved permanently
            $formName = $formData->title; 
            $uploaddir = './wp-content/uploads/bordas_da_cidade/produtor/';
            $fieldName = 'imagem_colaboracao';
        }
        // Bordas da Cidade - Turismo
        else if ($formData->title == 'Bordas da Cidade - Turismo' && isset($formData->uploaded_files['imagem_colaboracao']))
        {
            // CHANGE THIS: directory where the file will be saved permanently
            $formName = $formData->title; 
            $uploaddir = './wp-content/uploads/bordas_da_cidade/turismo/';
            $fieldName = 'imagem_colaboracao';
        }

 
    if ($formData && $formName == $formData->title && isset($formData->uploaded_files[$fieldName])) {
        // make a copy of data from cf7
        $formCopy = clone $formData;
        
        // breakdown parts of uploaded file, to get basename
        $path = pathinfo($formCopy->uploaded_files[$fieldName]);
        // directory of the new file
        $newfile = $uploaddir . $path['basename'];
 
        // check if a file with the same name exists in the directory
        if (file_exists($newfile)) {
            $dupname = true;
            $i = 2;
            while ($dupname) {
                $newpath = pathinfo($newfile);
                $newfile = $uploaddir . $newpath['filename'] . '-' . $i . '.' . $newpath['extension'];
                if (file_exists($newfile)) {
                    $i++;
                } else {
                    $dupname = false;
                }
            }
        }
        // make a copy of file to new directory
        copy($formCopy->uploaded_files[$fieldName], $newfile);
        // save the path to the copied file to the cfdb database
        $formCopy->posted_data[$fieldName] = $newfile;
 
        // delete the original file from $formCopy
        unset($formCopy->uploaded_files[$fieldName]);
 
        return $formCopy;
    }
    return $formData;
}
 



//validando email
add_filter( 'wpcf7_validate_email*', 'custom_email_confirmation_validation_filter', 20, 2 );
function custom_email_confirmation_validation_filter( $result, $tag ) {
    $tag = new WPCF7_Shortcode( $tag );
    
    if ( $tag->name == 'emailCadastro' ){
        $cadastro_mail =  $_POST['emailCadastro'];
        $count = getCountMail($cadastro_mail);
        if($count > 0){
            $result->invalidate( $tag, "Este e-mail já foi cadastrado. Verifique sua caixa de E-mail." );
        }
    }
    
    
    if ( $tag->name == 'emailApoio' ){
        $mail =  $_POST['emailApoio'];
        $idColab = $_POST['idColab'];
        $count = getCountMail($mail);
        if($count == 0){
            $result->invalidate( $tag, "Email não registrado ou não confirmado. Verifique sua caixa de Email ou Cadastre-se ao lado." );
        }
        else{
                $countComment = getCountApoiosByMail($mail, $idColab);
                if($countComment == 0){
                         computarApoio($idColab);
                }else{
                    $result->invalidate( $tag, "Você já apoiou esta colaboração." );
                }
        }
    }
    
    
    return $result;
}


//gerando token
//add_action('wpcf7_before_send_mail', 'token_Proposta');
//add_filter('cfdb_form_data', 'token_Proposta');
add_filter( 'wpcf7_posted_data', 'posted_data_wpcf7' );
function posted_data_wpcf7($formData)
{
    if ( isset($formData['plataformaApoioToken']) )
    {
        global $wpdb;
    
        $token = substr(uniqid(), 1, 8) . substr(uniqid(), 3, 9) . substr(uniqid(), 8, 13);
        $i = true;
        $token_count = 0;
        while($i)
        {
            $token_count = $wpdb->get_var("
                                        select count(*) from wp_cf7dbplugin_submits 
                                        where field_name = 'plataformaApoioToken'
                                          and field_value = '" . $token . "'
                                        ");
            if ($token_count > 0)
            {
                $token = substr(uniqid(), 1, 8) . substr(uniqid(), 3, 9) . substr(uniqid(), 8, 13);
                
            }
            else
            {
                //$formData->posted_data['tokenDaPessoa'] = $token;
                $formData['plataformaApoioToken'] = $token;
                $i = false;
            }
        }
    }
    
    if(isset($formData['plataformaApoioConfirmadoEmail']))
    {
        $formData['plataformaApoioConfirmadoEmail'] = 'N';
    }
    if(isset($formData['plataformaAprovado']))
    {
        $formData['plataformaAprovado'] = 'N';
    }
    if(isset($formData['plataformaStatus']))
    {
        $formData['plataformaStatus'] = 'N';
    }
    
    if(isset($formData['plataformaApoioNumeroApoios']))
    {
        $formData['plataformaApoioNumeroApoios'] = '0';
    }
    
    if(isset($formData['plataformaApoioSenha']))
    {
        $formData['plataformaApoioSenha'] = '';
    }
    
    if(isset($formData['plataformaApoioModeracaoProposta']))
    {
        $formData['plataformaApoioModeracaoProposta'] = '';
    }

    
    if(isset($formData['CEP']))
    {
            $cep = $formData['CEP'];
            if(!strstr($cep,"-"))
            {
                $antes = substr($cep, 0,5);
                $depois = substr($cep, 5);
                $cep = $antes . "-" . $depois;
            }
            $formData['CEP'] = $cep;
    }
    
    return $formData;  
}

//author: Renan

//popup Plataforma planos regionais - Participacao Usuario
//shortcode: [plataformaPlanosRegionaisParticipa]
add_shortcode('plataformaPlanosRegionaisParticipa', 'plataformaPlanosRegionaisParticipa');
function plataformaPlanosRegionaisParticipa(){
        ob_start();
        ?>
                <!-- Informações do Perímetro -->
		<div class="commentcontainer">
				<div>
					<span style="color: #E00808;font-size: 18px;font-weight:bold;">Marque o local da proposta no mapa.</span>
				</div>
				<link rel="stylesheet" href="https://openlayers.org/en/v3.13.1/css/ol.css" type="text/css">
				<script src="https://openlayers.org/api/OpenLayers.js"></script><script src="https://code.jquery.com/jquery-1.11.2.min.js"></script><script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
				<script src="https://openlayers.org/en/v3.8.2/build/ol.js"></script>
				<div id="map" class="map" style="width:100%; height:200px; padding-left:1px; padding-right:1px"></div>
				<div>&nbsp;</div>
				<div id="drawRegionData"></div>
				<div>
					<label>Tipo de marcação:&nbsp;</label><select id="geom_type">
						<option value="Point" selected>Marcar ponto</option>
						<option value="Polygon">Desenhar região</option>
					</select></p>
				</div>
				<div id="delete" style="text-decoration:underline;cursor:pointer" align="center">
					<p>Clique aqui para desfazer a marcação.</p>
				</div> 
		</div>
		<div id="formComment" style="float:left; width: 90%; margin-left: 20px">
			<?php echo do_shortcode('[contact-form-7 id="73" title="Planos Regionais - Formulário de Proposta"]');?>                    
		</div>
        <script>
                    // CRIA A LAYER DE VETOR PARA EDITAR
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
					opacity: 0.9,
					scale: 1,
					src: '../../wp-content/themes/mapacolaborativo-1.0/images/img-map-marker-icon-purple.png',					
				}),
			});
            var vector_layer = new ol.layer.Vector({
				name: 'my_vectorlayer',
				source: new ol.source.Vector(),
				style: featuresStyle,
            });
            // CRIA O MAPA
            var map = new ol.Map({
              target: 'map',
              layers: [
                new ol.layer.Tile({
                  source: new ol.source.OSM()
                }),
                vector_layer
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
			var $geom_type = $('#geom_type');
			jQuery("#pinpointLocationContainer").css('display', 'none');
			// RECONSTROI INTERACAO QUANDO ALTERA O TIPO
			$geom_type.on('change', function(e) {
			  map.removeInteraction(draw_interaction);
			  addDrawInteraction();
			  /*
			  if($geom_type.val() == "Point"){
				jQuery("#pinpointLocationContainer").css('display', 'block');
			  }
			  */
			  //ESCONDE COORDENADAS
			  //else{
				jQuery("#pinpointLocationContainer").css('display', 'none');  
			  //}
			});
			// CRIA A INTERACAO DRAW
			function addDrawInteraction() {
				// REMOVE OUTRAS INTERACOES
				map.removeInteraction(select_interaction);
				map.removeInteraction(modify_interaction);  
				// CRIA A INTERACAO
				draw_interaction = new ol.interaction.Draw({
				source: vector_layer.getSource(),
				type: /** @type {ol.geom.GeometryType} */ ($geom_type.val())
				});
				// ADICIONA AO MAPA
				map.addInteraction(draw_interaction);
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
				format = new ol.format['GeoJSON'](),
			  // this will be the data in the chosen format
				drawRegionData;
			  try {
				// convert the data of the vector_layer into the chosen format				
				data = format.writeFeaturesObject(vector_layer.getSource().getFeatures());				
			  } catch (e) {
				// at time of creation there is an error in the GPX format (18.7.2014)
				//$('#data').val(e.name + ": " + e.message);
				return;
			  }
				// $('#data').val(JSON.stringify(data, null, 4));
				// ENVIA COORDENADAS DO PERIMETRO DESENHADO PARA O FORM				
				jQuery("#coordsPerimetro").val(JSON.stringify(data.features[0]));
			}			
            // LIMPA O MAPA/DESFAZ MARCAÇÃO
			$("#delete").click(function() {
				clearMap();
			});
			function clearMap() {
				vector_layer.getSource().clear();
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
				//$('#data').val('');
			}            
            // creates unique id's
			function uid(){
			  var id = 0;
			  return function() {
				if (arguments[0] === 0) {
				  id = 0;
				}
				return id++;
			  }
			}
			//LATLONG
			var pontoMarcado = false;
			//PEGA LATITUDE E LONGITUDE DO PONTO SELECIONADO
			var latFinal = 0;
			var longFinal = 0;
			map.on('click', function(evt) {
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
        </script>
        <?php
        return wpautop(ob_get_clean());
}



//endAuthor: Renan

//popup Plataforma planos regionais
//shortcode: [plataformaPlanosRegionais]
add_shortcode('plataformaPlanosRegionais', 'plataformaPlanosRegionais');
function plataformaPlanosRegionais(){
    if( isset($_GET['hd']) || $_POST["hd"] ){
            $id = '';
            if( isset($_GET['hd']) )
            {
                $id = (string)$_GET['hd'];
            }
            else if ($_POST["hd"])
            {
                $id = (string)$_POST['hd'];
            }
    }
        require_once 'inc/persistence/PerimetroDAO.php';
        require_once 'inc/entity/Perimetro.php';
        $perimetroDAO = new PerimetroDAO();
        $perimetro = new Perimetro();
        $perimetro = $perimetroDAO->getPerimetro($id);
        ob_start();
        ?>
	<script>
	var feature = (new ol.format.GeoJSON()).readFeature(sessionStorage.feature);
	var isProposta;
	</script>

	<!-- Informações do Perímetro -->
	<div class="commentcontainer">
			<link rel="stylesheet" href="https://openlayers.org/en/v3.13.1/css/ol.css" type="text/css">
			<script src="https://openlayers.org/api/OpenLayers.js"></script>
			<div id="map" class="map" style="width:100%; height:200px; padding-left:1px; padding-right:1px"></div>
					  
			<!--<div id="googleMap" style="width:100%; height:200px; padding-left:1px; padding-right:1px"></div>-->
			<div class="perimeterinfo">
			<?php if($perimetro->caracterizacao != null){ ?>
					<script>
					/**
					*	Define se feature é oficial ou é proposta
					*/
					isProposta = false;
					</script>
					<h5>Caracterização</h5>
					<p>
						<?php echo $perimetro->caracterizacao; ?>
					</p>
					<br />
					<h5>Objetivos:</h5>
					<p>
						<?php echo $perimetro->objetivos; ?>
					</p>
					<br />
					<h5>Diretrizes:</h5>
					<p>
						<?php echo $perimetro->diretrizes; ?>
					</p>
					<br />
					<h5>Observações:</h5>
					<p>
						<?php echo $perimetro->observacoes; ?>
					</p>
				<?php } else { ?>
					<!-- Janela de Proposta -->
					<h5>Autor</h5>
					<p id="autorProposta"></p>								
					<br />
					<h5>Título</h5>
					<p id="tituloProposta"></p>
					<br />
					<h5>Colaboração</h5>
					<p id="colaboracao"></p>
					<br />
					<script>
					isProposta = true;
					/**
					*	Recebe dados da feature Proposta e preenche divs.
					*/
						jQuery("#autorProposta").append(feature.get("AUTHOR_NAME"));
						jQuery("#tituloProposta").append(feature.get("NOME"));
						jQuery("#colaboracao").append(feature.get("COLABORACAO"));
					</script>
				<?php } ?>
			</div>
	</div>

	<!-- <div class="minhocaoDetalhesLinha"></div> -->

	<div id="formComment" style="float:left; width: 90%; margin-left: 20px">
		<?php echo do_shortcode('[contact-form-7 id="44" title="Planos Regionais - Formulário de Colaboração"]');?>		
		<script>
		if(isProposta){
			jQuery('#pRegiaoComentada').css('display','none');
		}
		</script>		
	</div>
        <!--INNERLAYER: IFRAME COM DETALHES DO PERIMETRO-->                        
    <script>
	/** 
	*	Desenha a feature 
	*/
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
						src: '../../wp-content/themes/mapacolaborativo-1.0/images/img-map-marker-icon.png'
					})
				})
		document.addEventListener("DOMContentLoaded", function(event){
			var raster = new ol.layer.Tile({source: new ol.source.OSM()});
			vectorLayer = new ol.layer.Vector({
				name: 'propsLayer',
				source: new ol.source.Vector(),
				style: featuresStyle,
			});
			vectorLayer.getSource().addFeature(feature);
			var map = new ol.Map({
			layers: [raster, vectorLayer],        
			target: 'map',
			view: new ol.View(
				{
				zoom: 10,
				minZoom: 11,
				maxZoom: 17
				})
			});
			map.getView().fit(vectorLayer.getSource().getExtent(), map.getSize());
			var id = sessionStorage.perimetro;
			document.getElementById("idFeature").value = id;
			console.log(id);
		});
    </script>
        <?php
        return wpautop(ob_get_clean());
}

//popupPlataforma planos regionais
//shortcode: [plataformaPlanosRegionaisComments]
add_shortcode('plataformaPlanosRegionaisComments', 'plataformaPlanosRegionaisComments');
function plataformaPlanosRegionaisComments(){
    require_once 'inc/persistence/ComentarioDAO.php';
    require_once 'inc/entity/Comentario.php';
    $comentarioDAO = new ComentarioDAO("Planos Regionais - Formulário de Colaboração", "Plataforma Apoio - Formulário de Usuário", "Plataforma Apoio - Formulário de Apoio");
    if( isset($_GET['hd']) || $_POST["hd"] ){
            $id = '';
            if( isset($_GET['hd']) )
            {
                $id = (string)$_GET['hd'];
            }
            else if ($_POST["hd"])
            {
                $id = (string)$_POST['hd'];
            }
    
        $comentarios = $comentarioDAO->getAllCommentsApproved($id,"");      
    }else if(isset($_GET['recents'])){
        $comentarios = $comentarioDAO->getAllCommentsRecentsApproved(); 
    }


    ob_start();
        ?><script type="text/javascript">
        function apoio1(i){
            document.getElementById("idColab").value = i;
        }
        jQuery(document).ready(function(){
             jQuery('.offsetLink').click(function(){
                     hrf = jQuery(this).attr('href');
                     topPos = jQuery(hrf).offset().top;
                     jQuery('html, body').scrollTop(topPos);		 
                     return false; 
             });	 
        });
    </script><?php 
        $contRegiao = 0;
        $regiaoAtual = "";
        foreach ($comentarios as $comentario){ 
        $regiao = $comentario->regiaoDescricaoPerimetro;
        if($regiao != $regiaoAtual){
            $contRegiao = 0;
        }
        ?>
	<!-- Comentarios -->
	<div class="commentcontainer">
                        <?php if($contRegiao == 0){?>
                        <!-- Filtro de comentários provisoriamente desabilitado -->   
                        <!--<h2 id="<?php //echo preg_replace('/\s+/', '', $regiao); ?>"><?php //echo $regiao; ?></h2> -->
                        <?php } ?>
			<div class="headercontainer">
					<div class="topo">
							<h4><?php echo $comentario->titulo; ?></h4>
					</div>
					<div align="right">
							<p class="apoiadas">
									<?php echo $comentario->numApoios." Apoiadas."; ?>
							</p>			
					</div>
					<div>
							<p class="commenttime">
									<?php echo $comentario->submitted; ?>
							</p>
					</div>
			</div>
			<div class="headercontainer">
					<div class="topo" style="padding-right:5em">
							<p class="userinfo">
									<img src="../../wp-content/themes/mapacolaborativo-1.0/images/img-user-icon.png" alt="Usuário">
									<?php echo $comentario->autor->nome; ?>
							</p>
					</div>
					<div class="topo"> 
							<p class="userinfo">
									<img src="../../wp-content/themes/mapacolaborativo-1.0/images/img-institution-icon.png" alt="Instituição">
									<?php echo $comentario->autor->instituicao; ?>
							</p>
					</div> 
			</div>
			<div align="justify">
                                        <p> Posicionamento: <?php echo $comentario->posicionamento; ?></p>
					<p class="usercomment">
							<?php echo $comentario->comentario; ?>
					</p>
			</div>
	<!-- Botao de apoio -->
	<div class="BotaoApoiarVermelho popmake-114" onclick="apoio1(<?php echo $comentario->id; ?>);">
					Apoiar
				</div>
	</div>
	<hr style="height:2px;">

	<?php
        $regiaoAtual = $comentario->regiaoDescricaoPerimetro;
        $contRegiao++;
    }
    return wpautop(ob_get_clean());
}

//shortcode: [plataformaPlanosRegionaisComments]
add_shortcode('plataformaPlanosRegionaisProps', 'plataformaPlanosRegionaisProps');
function plataformaPlanosRegionaisProps(){
    require_once 'inc/persistence/ComentarioDAO.php';
    require_once 'inc/entity/Comentario.php';
    $comentarioDAO = new ComentarioDAO("Planos Regionais - Formulário de Colaboração", "Plataforma Apoio - Formulário de Usuário", "Plataforma Apoio - Formulário de Apoio");
    if( isset($_GET['hd']) || $_POST["hd"] ){
            $id = '';
            if( isset($_GET['hd']) )
            {
                $id = (string)$_GET['hd'];
            }
            else if ($_POST["hd"])
            {
                $id = (string)$_POST['hd'];
            }
    
        $comentarios = $comentarioDAO->getAllCommentsApproved($id,"");      
    }else if(isset($_GET['recents'])){
        $comentarios = $comentarioDAO->getAllCommentsRecentsApproved(); 
    }


    ob_start();
        ?>
    <script type="text/javascript">
        function apoio1(i){
            document.getElementById("idColab").value = i;
        }
    </script>
   <?php foreach ($comentarios as $comentario){ ?>
   
              <!--  <br>
                <strong><?php //echo $comentario->author_mail; ?></strong>
                <br>
                Data: <?php //echo $comentario->submitted; ?><br><br>
                <strong>Região do comentário:</strong><br>
                <p><?php //echo $comentario->regiaoDescricaoPerimetro;  ?></p><br>
                 <strong>Comentário:</strong>
                <p style="width: 300px;"><?php //echo $comentario->colaboracao; ?></p>
                <hr> -->
                

                <!-- Comentarios -->
                <div class="commentcontainer">
                        <div class="headercontainer">
                                <div class="topo">
                                        <h4><?php echo $comentario->titulo; ?></h4>
                                </div>
                                <div align="right">
                                        <p class="apoiadas">
                                                <?php echo $comentario->numApoios." Apoiadas."; ?>
                                        </p>			
                                </div>
                                <div>
                                        <p class="commenttime">
                                                <?php echo $comentario->submitted; ?>
                                        </p>
                                </div>
                        </div>
                        <div class="headercontainer">
                                <div class="topo" style="padding-right:5em">
                                        <p class="userinfo">
                                                <img src="../../wp-content/themes/mapacolaborativo-1.0/images/img-user-icon.png" alt="Usuário">
                                                <?php echo $comentario->autor->nome; ?>
                                        </p>
                                </div>
                                <div class="topo"> 
                                        <p class="userinfo">
                                                <img src="../../wp-content/themes/mapacolaborativo-1.0/images/img-institution-icon.png" alt="Instituição">
                                                <?php echo $comentario->autor->instituicao; ?>
                                        </p>
                                </div> 
                        </div>
                        <div align="justify">
                                <p class="usercomment">
                                        <?php echo $comentario->comentario; ?>
                                </p>
                        </div>
                <!-- Botao de apoio -->
                <div class="BotaoApoiarVermelho popmake-114" onclick="apoio1(<?php echo $comentario->id; ?>);">
                                Apoiar
                            </div>
                </div>
                <hr style="height:2px;">

        <?php
    }
    return wpautop(ob_get_clean());
}
// endAuthor: Renan
//shortcode [shortcode_confirmarEmail_user]
add_shortcode('shortcode_confirmarEmail_user', 'shortcode_confirmarEmail_user');
function shortcode_confirmarEmail_user(){
        if(isset($_GET['tkn'])){
            $token = (string)$_GET['tkn'];
        }else{
            wp_die();
        }
    require_once 'inc/persistence/UsuarioDAO.php';
    $usuarioDAO = new UsuarioDAO("Plataforma Apoio - Formulário de Usuário");
    $id = $usuarioDAO->getSubmitByToken($token);
    //echo $id;
    if($id){
        $usuarioDAO->updateEmail($id);
    }else{
        wp_die();
    }               
}
add_action( 'wp_ajax_nopriv_planosRegionais_getAllPerimetros', 'planosRegionais_getAllPerimetros_ajax' );
add_action( 'wp_ajax_planosRegionais_getAllPerimetros', 'planosRegionais_getAllPerimetros_ajax' );
function planosRegionais_getAllPerimetros_ajax(){
    require_once "inc/persistence/PerimetroDAO.php";
    $perimetrosArray = [];
    $perimetroDAO = new PerimetroDAO();
    $perimetros = $perimetroDAO->getAllPerimetros();
    foreach ($perimetros as $perimetro){
        $perimetroArrayItem = [
            "ID" => $perimetro->id,
            "NOME" => $perimetro->nome,
            "TIPO" => $perimetro->tipo,
            "LOCALIZACAO" => $perimetro->localizacao,
            "CARACTERIZACAO" => $perimetro->caracterizacao,
            "OBJETIVOS" => $perimetro->objetivos,
            "DIRETRIZES" => $perimetro->diretrizes
        ];
        array_push($perimetrosArray,$perimetroArrayItem);
    }
    echo json_encode($perimetrosArray);
    die();
}

add_action( 'wp_ajax_nopriv_plataformaApoio_getAllPropostas', 'plataformaApoio_getAllPropostas_ajax' );
add_action( 'wp_ajax_plataformaApoio_getAllPropostas', 'plataformaApoio_getAllPropostas_ajax' );
function plataformaApoio_getAllPropostas_ajax()
{
    require_once "inc/persistence/PropostaDAO.php";
    require_once "inc/entity/Proposta.php";
    $propostaDAO = new PropostaDAO('Planos Regionais - Formulário de Proposta','Plataforma Apoio - Formulário de Usuário');
    $propostas = $propostaDAO->getPropostasApproved();
    $propostasJSON = [];
    foreach ($propostas as $proposta){
        $coords = [
                  'latitude' => $proposta->latitude,
                  'longitude' => $proposta->longitude,
                  'feature' => json_decode($proposta->feature)];
        $objAutor = [
                    'id' => $proposta->autor->id,
                    'nome' => $proposta->autor->nome,
                    'email' => $proposta->autor->email,
                    'instituicao' => $proposta->autor->instituicao,
                    'endereco' => $proposta->autor->endereco,
                    'cep' => $proposta->autor->cep];
        $objProposta = [
            'coords' => $coords,
            'id' => $proposta->id,
            'titulo' => $proposta->titulo,
            'submitted' => $proposta->submitted,
            'colaboracao' => $proposta->colaboracao,
            'autor' => $objAutor
        ];
        array_push($propostasJSON, $objProposta);
    }
    echo json_encode(Proposta::jsonSerializeObjPropostas($propostasJSON));
    die();
}
//função social - get all propostas approved(ajax)
add_action( 'wp_ajax_nopriv_plataformaApoio_funcaoSocial_getAllColaboracoes', 'plataformaApoio_funcaoSocial_getAllColaboracoes_ajax' );
add_action( 'wp_ajax_plataformaApoio_funcaoSocial_getAllColaboracoes', 'plataformaApoio_funcaoSocial_getAllColaboracoes_ajax' );
function plataformaApoio_funcaoSocial_getAllColaboracoes_ajax()
{
    header('Content-Type: application/json; charset=utf-8');
    require_once "inc/persistence/funcao_social/ColabImovelDAO.php";
    require_once "inc/entity/funcao_social/ColabImovel.php";
    $colabImovelDAO = new ColabImovelDAO('Função Social - Formulário Marcação Imóvel','Plataforma Apoio - Formulário de Usuário');
    $colaboracoes = $colabImovelDAO->getColaboracoesMapa('aprovados');
    $colaboracoesJSON = [];
    foreach ($colaboracoes as $colaboracao){
        $latlon = [
                  'latitude' => $colaboracao->latitude,
                  'longitude' => $colaboracao->longitude];
        $objColaboracao = [
            'latlon' => $latlon,
            'id' => $colaboracao->id,
            'logradouro' => $colaboracao->logradouro,
            'numero' => $colaboracao->numeroLogradouro,
            'numApoios' => $colaboracao->numApoios,
            'ponto_referencia' => $colaboracao->pontoReferencia,
            'caracteristicas' => $colaboracao->caracteristicaImovel,
            'possesDoImovel' => $colaboracao->possesDoImovel,
            'tipo_aprovacao' => $colaboracao->tipoAprovacao,
            'tempo_inutilizado' => $colaboracao->tempoInutilizado
        ];
        array_push($colaboracoesJSON, $objColaboracao);
    }
    echo json_encode($colaboracoesJSON);
    die();
}

//bordas da cidade - get all propostas approved(ajax)
add_action( 'wp_ajax_nopriv_plataformaApoio_bordasDaCidade_getAllColaboracoes', 'plataformaApoio_bordasDaCidade_getAllColaboracoes_ajax' );
add_action( 'wp_ajax_plataformaApoio_bordasDaCidade_getAllColaboracoes', 'plataformaApoio_bordasDaCidade_getAllColaboracoes_ajax' );
function plataformaApoio_bordasDaCidade_getAllColaboracoes_ajax()
{
    require_once "inc/persistence/bordas_da_cidade/ColabProdutorDAO.php";
    require_once "inc/persistence/bordas_da_cidade/ColabTurismoDAO.php";
    $colabTurismoDAO = new ColabTurismoDAO('Bordas da Cidade - Turismo','Plataforma Apoio - Formulário de Usuário');
    $colabProdutorDAO = new ColabProdutorDAO('Bordas da Cidade - Produtor','Plataforma Apoio - Formulário de Usuário');
    $colaboracoesProdutor = $colabProdutorDAO->getColaboracoesMapa('aprovados');
    $colaboracoesTurismo = $colabTurismoDAO->getColaboracoesMapa('aprovados');
    $colaboracoesJSONProdutor = [];
    $colaboracoesJSONTurismo = [];
    foreach ($colaboracoesProdutor as $colaboracao){
        $latlon = [
                  'latitude' => $colaboracao->latitude,
                  'longitude' => $colaboracao->longitude];
        $autor = [
            'id' => $colaboracao->autor->id,
            'email' => $colaboracao->autor->email,
            'nome' => $colaboracao->autor->nome,
            'endereco' => $colaboracao->autor->endereco,
            'instituicao' => $colaboracao->autor->instituicao,
            'cep' => $colaboracao->autor->cep
        ];
        $objColaboracao = [
            'latlon' => $latlon,
            'id' => $colaboracao->id,
            'caracteristicasProducao' => $colaboracao->caracteristicasProducao,
            'produtosCultivados' => $colaboracao->produtosCultivados,
            'autor' => $autor,
            'finalidadeProducao' => $colaboracao->finalidadeProducao,
            'principalFonteRenda' => $colaboracao->principalFonteRenda,
            'permiteVisitantes' => $colaboracao->permiteVisitantes,
            'urlImagem' => $colaboracao->imagem
        ];
        array_push($colaboracoesJSONProdutor, $objColaboracao);
    }
    foreach ($colaboracoesTurismo as $colaboracao){
        $latlon = [
                  'latitude' => $colaboracao->latitude,
                  'longitude' => $colaboracao->longitude];
        $autor = [
            'id' => $colaboracao->autor->id,
            'email' => $colaboracao->autor->email,
            'nome' => $colaboracao->autor->nome,
            'endereco' => $colaboracao->autor->endereco,
            'instituicao' => $colaboracao->autor->instituicao,
            'cep' => $colaboracao->autor->cep
        ];
        $objColaboracao = [
            'latlon' => $latlon,
            'id' => $colaboracao->id,
            'tipoEmpreendimento' => $colaboracao->tipoEmpreendimento,
            'tipoAcessoEmpreendimento' => $colaboracao->tipoAcessoEmpreendimento,
            'autor' => $autor,
            'meioAcessoEmpreendimento' => $colaboracao->meioAcessoEmpreendimento,
            'principalFonteRenda' => $colaboracao->principalFonteRenda,
            'principaisAtrativos' => $colaboracao->principaisAtrativos,
            'urlImagem' => $colaboracao->imagem
        ];
        array_push($colaboracoesJSONTurismo, $objColaboracao);
    }
    echo json_encode(['colaboracoes_produtor' => $colaboracoesJSONProdutor,'colaboracoes_turismo' => $colaboracoesJSONTurismo]);
    die();
}

add_action( 'wp_ajax_nopriv_plataformaApoio_getAllComments', 'plataformaApoio_getAllComments_ajax' );
add_action( 'wp_ajax_plataformaApoio_getAllComments', 'plataformaApoio_getAllComments_ajax' );
function plataformaApoio_getAllComments_ajax(){
    require_once "inc/persistence/ComentarioDAO.php";
    if(isset($_POST['id']) && isset($_POST['tag']))
    {
        $idFeature = (string)$_POST['id'];
        $tag = (string)$_POST['tag'];
        switch ($tag){
            case 0: $comentarioDAO = new ComentarioDAO('Planos Regionais - Formulário de Colaboração', 'Plataforma Apoio - Formulário de Usuário', '');
                    break;
            case 1: $comentarioDAO = new ComentarioDAO('Função Social - Formulário de Comentários', 'Plataforma Apoio - Formulário de Usuário', '');
                    break;
            default: echo "Error during get data :(";
                    die();
                    break;
        }
        
        $comentarios = $comentarioDAO->getAllCommentsApproved($idFeature, "");
        $objComentarios = [];
        foreach($comentarios as $comentario){
            $comment = [            
                'comment_id' =>    $comentario->id,
                'comment_autor' =>    [                
                                        'author_id' => $comentario->autor->id,
                                        'author_nome' =>   $comentario->autor->nome,
                                        'author_mail' =>    $comentario->autor->email,
                                        'author_instituicao' =>    $comentario->autor->instituicao,
                                        'author_endereco' =>    $comentario->autor->endereco,
                                        'author_cep' =>    $comentario->autor->cep
                ],
                'comment_posicionamento' =>    $comentario->posicionamento,
                'comment_submitted' =>    $comentario->submitted,
                'comment_title' =>    $comentario->titulo,
                'comment_comentario' =>    $comentario->comentario,
                'comment_numApoios' =>    $comentario->numApoios,
                'comment_idFeature' =>    $comentario->idFeature
            ];
            array_push($objComentarios, $comment);
        }
        echo json_encode($objComentarios);
        die();
    }
}




add_filter('popmake_popup_is_loadable', 'mycustom_popup_is_loadable', 10, 2 );
function mycustom_popup_is_loadable( $is_loadable, $popup_id)
{
    return $is_loadable;
}

add_filter( 'wpcf7_support_html5_fallback', '__return_true' );




