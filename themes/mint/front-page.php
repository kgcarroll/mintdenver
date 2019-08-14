<?php 
/*
* Front page template
*
*/
get_header(); ?>
	<div id="home" class="container" role="main">
	<?php print_homepage_header_section(); ?>
	<?php print_homepage_main_content(); ?>
	<?php print_call_outs(); ?>
	<?php print_homepage_form(); ?>
	</div>
<?php get_footer(); ?>