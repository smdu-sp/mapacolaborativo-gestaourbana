<?php
/*
 * Template Name: Página em Branco
 */
?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, minimumscale=1.0, maximum-scale=1.0" />
	<title>Gestão Urbana SP</title>
	<meta name="description" content=" Participe do planejamento de uma nova São Paulo">
	<link rel="stylesheet" type="text/css" href="<?php bloginfo( 'stylesheet_url' ); ?>?<?php echo time(); ?>"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-agenda-interna.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-agenda-sidebar.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-agenda.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-biblioteca.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-contato.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-entenda-etapas.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-entenda-introducao.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-entenda-perguntas.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-equipe.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-home.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-interna.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-noticias-interna.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-noticias-sidebar.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-noticias.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-comments.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/glDatePicker.flatwhite.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/jquery.fancybox.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo bloginfo('template_url'); ?>/css/style-mapa-colaborativo.css"/>
        <!-- Bootstrap -->
        <!-- Latest compiled and minified CSS -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        <script src="http://openlayers.org/en/v3.13.1/build/ol.js"></script>
	<script type="text/javascript">
	  var template_url = "<?php echo bloginfo('template_url'); ?>";
	  var slider = 'slider';
          jQuery( "#CEP" ).keypress(function() {
                    if (jQuery( "#CEP" ).val().length == 5)
                    {
                        jQuery( "#CEP" ).val(jQuery( "#CEP" ).val() + '-');
                    }
           });
	</script>

<?php
wp_enqueue_script('respond', get_stylesheet_directory_uri() . '/js/respond.min.js');
wp_enqueue_script('site-script', get_stylesheet_directory_uri() . '/js/script.js', array( 'jquery' ));
wp_enqueue_script('bjqs', get_stylesheet_directory_uri() . '/js/bjqs-1.3.js', array( 'jquery' ));
wp_enqueue_script('glDatePicker', get_stylesheet_directory_uri() . '/js/glDatePicker.js', array( 'jquery' ));
wp_enqueue_script('jquery.fancybox', get_stylesheet_directory_uri() . '/js/jquery.fancybox.js', array( 'jquery' ));

?>
</head>

<?php wp_head();
if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

<div id="news-inner">
    <!-- <div class="wrapper"> -->
        <div class="left" style="width:100%;">
            <div class="floatComment">
                <div class="text">
                    <div class="inner-text">
                        
                        <?php
                            $content = get_the_content();
                            $content = apply_filters('the_content', $content);
                            $content = str_replace(']]>', ']]&gt;', $content);
                            $content = str_replace('<p> </p>', '', $content);
                            $content = explode('<p>',$content);
                        ?>
                        
                        <?php for ($x = 0; $x < count($content); $x++) : ?>
                            <?php if ($x == get_field('read_more_paragraph') && get_field('show_read_more')): ?>
                                <div class="also-read">
                                    <?php if (function_exists('the_related')) { the_related(); }; ?>
                                </div>
                            <?php endif; ?>

                            <p><?php echo str_replace('</p>', '',$content[$x]); ?></p>

                        <?php endfor; ?>

                        <div class="clear"></div>
                    </div>
                </div>
                
            </div>
        </div>
         <div class="clear"></div>
    <!-- </div> -->
</div>
<?php endwhile;wp_footer();?>

</body>

</html>