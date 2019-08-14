<?php
/*
* Template Name: Online Leasing
*
*/
get_header(); ?>
	<div id="online-leasing-wrapper">
		<div id="widget-container"></div>
		<?php
		$scriptUrl = 'https://property.onesite.realpage.com/oll/eol/widget';
		$scriptUrl .= '?siteId=4105148';
		$scriptUrl .= '&container=widget-container';
		$scriptUrl .= '&css='.urlencode( get_template_directory_uri ().'/css/realpage-widget.css' );
		?>
		<script src="<?php echo $scriptUrl; ?>"></script>
	</div>
<?php get_footer(); ?>