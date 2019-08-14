<?php 
/*
* Template Name: Contact
*
*/
get_header(); ?>
  <div id="contact" class="container" role="main">
	  <?php //print_header_section(); --edited function below to feature sub-heading as h1
		  if ($headline = get_field('headline')) {
			  $sub_headline = get_field('sub_headline');
			  $pre_title = get_field('pre_title');
			  echo '<div class="page-header">';
			  print_header_image(); // Global Header Image setup.
			  echo '<div class="pre-title fade-in">'.$pre_title.'</div>';
			  echo '<div class="header-content fade-in vertical-center-parent">';
			  echo '<div class="vertical-center-child">';
			  echo '<span class="icon-mint-diamond"></span>';
			  echo '<div class="header-headline ease">'.$headline.'</div>';
			  echo '<div class="header-sub-headline"><h1>'.$sub_headline.'</h1></div>';
			  echo '</div>';
			  echo '</div>';
			  echo '<div class="header-bottom">';
			  echo '<div class="header-line-break ease">';
			  echo '<img class="breaks" src="'.get_bloginfo('template_directory').'/images/breaks.png" />';
			  echo '<div class="background"></div>';
			  echo '</div>';
			  echo '<div class="scroll">';
			  echo '<span class="scroll-title">Scroll</span>';
			  echo '<span class="icon-mint-triangle"></span>';
			  echo '</div>';
			  echo '</div>';
			  echo '</div>';
		  }
	  ?>
  	<?php print_contact_content(); // form is in here. ?>
		<?php print_call_outs(); ?>
	</div>
<?php get_footer(); ?>