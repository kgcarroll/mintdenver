<?php
/*
* Template Name: Thank You
*
*/
get_header(); ?>
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window,document,'script',
'https://connect.facebook.net/en_US/fbevents.js');

 	fbq('init', '783345711865140'); 
	fbq('track', 'PageView');
	fbq('track', 'Lead');
</script>
<noscript>
 <img height="1" width="1" 
src="https://www.facebook.com/tr?id=783345711865140&ev=PageView
&noscript=1"/>
</noscript>
<!-- End Facebook Pixel Code -->

  <div id="thank-you" class="container" role="main">
    <?php print_header_section(); ?>
    <?php print_thank_you_content(); ?>
    <?php print_call_outs(); ?>
  </div>
<?php get_footer(); ?>