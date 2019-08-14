<?php
/*
* Search Results template
*
*/
get_header();
$GLOBALS['search-result'] = true;
?>
<div class="main container">
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