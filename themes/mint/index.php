<?php 
/*
* Basic default index template for the entire wordpress site
*
*/
get_header(); ?>
<div class="main container">
	<?php print_blog_header_section(); ?>
	<div id="blog-page">
		<div class="blog-page-wrapper">
			<div class="left">
				<div class="mobile-search"><?php get_search_form(); ?></div>
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



