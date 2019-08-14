<?php
/*
* Template Name: Gallery
*
*/
get_header(); ?>
	<div id="gallery" class="container" role="main">
  	<?php //print_small_header_section();--edited function below to feature sub-heading as h1
    if ($headline = get_field('headline')) {
        $sub_headline = get_field('sub_headline');
        $pre_title = get_field('pre_title');
        echo '<div class="page-header small">';
        print_small_header_image(); // Global Header Small 	Image setup.
        echo '<div class="pre-title fade-in">'.$pre_title.'</div>';
        echo '<div class="header-content fade-in vertical-center-parent">';
        echo '<div class="vertical-center-child">';
        echo '<div class="header-headline ease">'.$headline.'</div>';
        echo '<div class="header-sub-headline"><h1>'.$sub_headline.'</h1></div>';
        echo '</div>';
        echo '</div>';
        echo '<div class="header-bottom">';
        echo '<div class="header-line-break ease">';
        echo '<img class="breaks" src="'.get_bloginfo('template_directory').'/images/breaks.png" />';
        echo '<div class="background"></div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }


     ?>
    <div id="gallery-container" class="fade-in">
    <div class="content-arrow">
      <div class="background"></div>
      <img class="arrow" src="<?php echo get_bloginfo('template_directory'); ?>/images/content-arrow.png" />
    </div>
  	 <ul id="categories"></ul>
    	<div id="overlap">
  	    <div id="gallery-wrapper">
          <div id="gallery">
            <div id="image-container"></div>
            <div id="next-image"><i class="fa fa-angle-right ease"></i></div>
            <div id="prev-image"><i class="fa fa-angle-left ease"></i></div>
          </div>
        </div>
    	</div>
      <div class="content-arrow-up">
        <img class="arrow" src="<?php echo get_bloginfo('template_directory'); ?>/images/up-arrow.png" />
        <div class="background"></div>
      </div>
    </div>
		<?php print_call_outs(); ?>
  </div>
<?php get_footer(); ?>