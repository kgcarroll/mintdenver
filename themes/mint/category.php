<?php
/*
* Category page template
*
*/
get_header(); ?>
  <div id="category" class="container">
    <?php print_blog_header_section(); ?>
    <div id="blog-page">
    	<div class="blog-page-wrapper">
    		<div class="left">
    			<?php include('blog-landing-page.php'); ?>
    		</div>
    		<div class="right">
    		  <?php get_sidebar(); ?>
    		</div>
  		</div>
  	</div>
  <?php print_blog_call_outs(); ?>
  </div>
<?php get_footer(); ?>