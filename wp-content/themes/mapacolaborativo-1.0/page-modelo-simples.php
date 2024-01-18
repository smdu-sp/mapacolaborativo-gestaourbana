<?php
/*
 * Template Name: Modelo Simples
 */

get_header(); ?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

<div id="news-inner">
  <div class="wrapper">
      <div class="inner-text">
           <h3><?php echo the_title(); ?></h3>
            <?php
              $content = get_the_content();
              $content = apply_filters('the_content', $content);
              $content = str_replace(']]>', ']]&gt;', $content);
              $content = str_replace('<p> </p>', '', $content);
              $content = explode('<p>',$content);
              for ($x = 0; $x < count($content); $x++) : 
               if ($x == get_field('read_more_paragraph') && get_field('show_read_more')): ?>
                <div class="also-read">
                  <?php if (function_exists('the_related')){ 
                            the_related(); 
                         }; ?>
                </div>
                <?php endif; ?>
              <p><?php echo str_replace('</p>', '',$content[$x]); ?></p>
            <?php endfor; ?>
      </div>
    <div class="clear"></div>

  </div>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>