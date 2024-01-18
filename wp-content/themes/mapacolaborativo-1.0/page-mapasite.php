<?php
/*
Template Name: Mapa do Site
*/
?>

<?php get_header(); 
/* select para obter o id dos itens de nav menus:
 * 
 * select * from wp_posts where post_type = 'nav_menu_item'
 * 
 *  */

?>
<div id="library">
    <div class="wrapper">
        <div class="inner">
            <div id="lista-artigos">
                <div class="left" style="width: 100%;">
                    <div class="left">
                        <br/>
                        <h3>Mapa do Site</h3>
                        <br/>
                    </div>
                    
                    <div class="right">

                    </div>
                </div>
                
                <div class="filtros2">
                    <div>
                        <p>
                            <?php wp_nav_menu( array( 'theme_location' => 'header-menu' ) ); ?>
                        </p>
                    </div>
                    
                    <div>
                        <p>
                            <?php
                                $menu_object = wp_get_nav_menu_items( 110 );
                                //print_r( $menu_object ) ;
                                foreach ( $menu_object as $menu_ )
                                {
                            ?>
                                <ul>
                                    <li class="menu-menu-interno-plano-diretor-container">
                                        <?php
                                            if ($menu_->ID == 1367)
                                            {
                                                ?>
                                                    <a href="<?php echo $menu_->url; ?>"><?php echo $menu_->title; ?></a>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                    <ul>
                                                        <li>
                                                            <a href="<?php echo $menu_->url; ?>"><?php echo $menu_->title; ?></a>
                                                        </li>
                                                    </ul>
                                                <?php
                                            }
                                        ?>
                                        <?php
                                            if ($menu_->ID == 1367)
                                            {

                                            }
                                            else
                                            {
                                                ?>
                                                    <ul>
                                                        <li>
                                                            <?php
                                                                if ($menu_->ID == 1381)
                                                                {
                                                                    wp_nav_menu( array( 'theme_location' => 'menu-interno-plano-diretor' ) );
                                                                }
                                                                if ($menu_->ID == 6592)
                                                                {
                                                                    wp_nav_menu( array( 'theme_location' => 'menu-interno-parcelamento-ocupacao-solo' ) );
                                                                }
                                                            ?>
                                                        </li>
                                                    </ul>
                                                <?php
                                            }
                                        ?>
                                    </li>
                                </ul>
                            <?php
                                }
                            ?>

                            <?php
                                $menu_object = wp_get_nav_menu_items( 113 );
                                //print_r( $menu_object ) ;
                                foreach ( $menu_object as $menu_ )
                                {
                            ?>
                                <ul>
                                    <li class="menu-menu-interno-arco-tiete-container">
                                        <?php
                                            if ($menu_->ID == 1380)
                                            {
                                                ?>
                                                    <a href="<?php echo $menu_->url; ?>"><?php echo $menu_->title; ?></a>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                    <ul>
                                                        <li>
                                                            <a href="<?php echo $menu_->url; ?>"><?php echo $menu_->title; ?></a>
                                                        </li>
                                                        <ul>
                                                            <li>
                                                                <?php wp_nav_menu( array( 'theme_location' => 'menu-interno-agua-branca-' ) ); ?>
                                                            </li>
                                                        </ul>
                                                    </ul>
                                                <?php
                                            }
                                        ?>
                                        <?php
                                            if ($menu_->ID == 1380)
                                            {

                                            }
                                            else
                                            {
                                        ?>
                                                <ul>
                                                    <li>
                                                        <?php
                                                            if ($menu_->ID == 1375)
                                                            {
                                                                wp_nav_menu( array( 'theme_location' => 'menu-interno-arco-tiete-' ) );
                                                            }
                                                            if ($menu_->ID == 9719)
                                                            {
                                                                wp_nav_menu( array( 'theme_location' => 'menu-interno-arco-tamanduatei-' ) );
                                                            }
                                                        ?>
                                                    </li>
                                                </ul>
                                        <?php
                                            }
                                        ?>
                                    </li>
                                </ul>
                            <?php
                                }
                            ?>
							
                            <?php
                                $menu_object = wp_get_nav_menu_items( 121 );
                                //print_r( $menu_object ) ;
                                foreach ( $menu_object as $menu_ )
                                {
                            ?>
                                <ul>
                                    <li class="menu-menu-interno-projeto-especiais-container">
                                        <?php
                                            if ($menu_->ID == 3493)
                                            {
                                                ?>
                                                    <a href="<?php echo $menu_->url; ?>"><?php echo $menu_->title; ?></a>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                    <ul>
                                                        <li>
                                                            <a href="<?php echo $menu_->url; ?>"><?php echo $menu_->title; ?></a>
															<?php wp_nav_menu( array( 'theme_location' => 'menu-interno-eixos-transformacao-' ) );?>
                                                        </li>
                                                    </ul>
                                                <?php
                                            }
                                        ?>
                                        <?php
                                            if ($menu_->ID == 3493)
                                            {

                                            }
                                            else
                                            {
                                                ?>
                                                    <ul>
                                                        <li>
                                                            <?php
                                                                if ($menu_->ID == 3492)
                                                                {

                                                                }
                                                            ?>
                                                        </li>
                                                    </ul>
                                                <?php
                                            }
                                        ?>
                                    </li>
                                </ul>
                            <?php
                                }
                            ?>

                            <?php
                                $menu_object = wp_get_nav_menu_items( 120 );
                                //print_r( $menu_object ) ;
                                foreach ( $menu_object as $menu_ )
                                {
                            ?>
                                <ul>
                                    <li class="menu-menu-interno-ceu-container">
                                        <?php
                                            if ($menu_->ID == 13577)
                                            {
                                        ?>
                                                <a href="<?php echo $menu_->url; ?>"><?php echo $menu_->title; ?></a>
                                        <?php
                                            }
                                            else
                                            {
                                        ?>
                                                <ul>
                                                    <li>
                                                        <?php
                                                            if ($menu_->ID == 3445)
                                                            {
                                                                ?>
                                                                    <a href="<?php echo $menu_->url; ?>"><?php echo $menu_->title; ?></a>
                                                                <?php
                                                                    wp_nav_menu( array( 'theme_location' => 'menu-interno-ceu-' ) );
                                                            }
                                                            else
                                                            {
                                                                ?>
                                                                    <a href="<?php echo $menu_->url; ?>"><?php echo $menu_->title; ?></a>
																<?php
																	if($menu_->ID == 19338 )
																	{
																		wp_nav_menu( array( 'theme_location' => 'menu-interno-wifi-' ) );
																	}
															}
														?>
                                                    </li>
                                                </ul>
                                        <?php
                                            }
                                        ?>
                                    </li>
                                </ul>
                            <?php
                                }
                            ?>

                            <?php
                                $menu_object = wp_get_nav_menu_items( 143 );
                                //print_r( $menu_object ) ;
                                foreach ( $menu_object as $menu_ )
                                {
                                    //print_r( $menu_ ) ;
                            ?>
                                <ul>
                                    <li class="menu-menu-interno-centro-aberto-container">
                                        <?php
                                            if ($menu_->ID == 15354)
                                            {
                                                ?>
                                                    <a href="<?php echo $menu_->url; ?>"><?php echo $menu_->title; ?></a>
                                                <?php
                                            }
                                            else
                                            {
                                        ?>
                                                <ul>
                                                    <li>
                                                        <?php
                                                            if ($menu_->ID == 7478)
                                                            {
                                                                ?>
                                                                    <a href="<?php echo $menu_->url; ?>"><?php echo $menu_->title; ?></a>
                                                                <?php
                                                                    wp_nav_menu( array( 'theme_location' => 'menu-interno-centro-aberto-' ) );
                                                            }
                                                            else
                                                            {
                                                                ?>
                                                                    <a href="<?php echo $menu_->url; ?>"><?php echo $menu_->title; ?></a>
																<?php
																	if($menu_->ID == 17293)
																	{
																		wp_nav_menu( array( 'theme_location' => 'menu-interno-centro-aberto-' ) );
																	}
																	if($menu_->ID == 15765)
																	{
																		wp_nav_menu( array( 'theme_location' => 'menu-interno-calcadao-' ) );
																	}
																	if($menu_->ID == 17292)
																	{
																		wp_nav_menu( array( 'theme_location' => 'menu-interno-parklets-' ) );
																	}
                                                            }
														?>
                                                    </li>
                                                </ul>
                                        <?php
                                            }
                                        ?>
                                    </li>
                                </ul>
                            <?php
                                }
                            ?>
                        
                        </p>
                    </div>
                    
                    <div>
                        <p>
                            <?php wp_nav_menu( array( 'theme_location' => 'extra-menu' ) ); ?>
                        </p>
                    </div>
                </div>
                <div class="clear"></div>

                <ul id="display" class="list boxes">
                </ul>
            </div>
            
            <script type="text/javascript">
                jQuery(document).ready(function ()
                {
                    jQuery('.menu-menu-principal-container').removeClass('menu-menu-principal-container');
                    
                    jQuery('#menu-menu-principal-1 a').css({
                            color: 'black'
                        });
                    
                    jQuery('#menu-footer a').css({
                            color: 'black'
                        });
                        
                });
                
            </script>

            <?php the_content_nav(); ?>

            <div class="clear"></div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
