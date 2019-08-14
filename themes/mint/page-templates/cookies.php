<?php
/*
* Template Name: Cookies
*
*/
get_header(); ?>
	<div id="cookies" class="container cookies-content" role="main">
		<div class="page-header">

    	<div class="mobile">
    		<canvas class="canvas" height="384" width="320"></canvas>
    		<svg x="0px" y="0px" viewBox="0 0 320 384" style="enable-background:new 0 0 320 384;" xml:space="preserve">
    			<defs>
    				<pattern id="mobile-image" x="0" y="0" width="1" height="1" viewBox="0 0 100 100" preserveAspectRatio="none">
    					<image xlink:href="https://mintdenverapts.com/wp-content/uploads/2017/03/contact-header-light-320x384.jpg" x="0" y="0" width="100" height="100" preserveAspectRatio="none"></image>
    				</pattern>
    			</defs>
    			<g class="header-shape">
    				<path fill="url(#mobile-image)" d="M319.5,0v332l-160,52L-0.13,332.52V0.02h237.2L319.5,0z"></path>
    			</g>
    			<g class="border">
    				<path style="fill:#FFFFFF;" d="M320,327v5l-160.5,52L0,332.52v-5.5l159.67,52L320,327z"></path>
    			</g>
    		</svg>
    	</div>
    	<div class="tablet">
    		<canvas class="canvas" height="742" width="768"></canvas>
    		<svg x="0px" y="0px" viewBox="0 0 768 742" style="enable-background:new 0 0 768 742;" xml:space="preserve"><defs><pattern id="tablet-image" x="0" y="0" width="1" height="1" viewBox="0 0 100 100" preserveAspectRatio="none"><image xlink:href="https://mintdenverapts.com/wp-content/uploads/2017/03/contact-header-light-768x742.jpg" x="0" y="0" width="100" height="100" preserveAspectRatio="none"></image></pattern></defs><g class="header-shape"><path fill="url(#tablet-image)" d="M768-0.5V613L384.5,741L0,613.52V-0.98l384.67,0.5L768-0.5z"></path></g><g class="border"><path style="fill:#FFFFFF;" d="M768,609v4L384.5,741L0,613.52v-5.5l384.67,128L768,609z"></path></g></svg>
    	</div>
			<div class="desktop">
				<canvas class="canvas" height="1103" width="1920"></canvas><svg x="0px" y="0px" width="1920px" height="1103px" viewBox="0 0 1920 1103" style="enable-background:new 0 0 1920 1103;" xml:space="preserve"><defs><pattern id="image" x="0" y="0" width="1" height="1" viewBox="0 0 100 100" preserveAspectRatio="none"><image xlink:href="https://mintdenverapts.com/wp-content/uploads/2017/03/contact-header-light.jpg" x="0" y="0" width="100" height="100" preserveAspectRatio="none"></image></pattern></defs>
						<g class="header-shape"><path fill="url(#image)" d="M960,1097c-0.67,0-1.33,0-2,0c-1.47-0.66-2.89-1.46-4.41-1.97c-40.23-13.52-80.48-27.01-120.72-40.5
		c-247-82.84-493.99-165.67-740.98-248.52C61.24,795.73,30.63,785.34,0,775C0,516.67,0,258.33,0,0c640,0,1280,0,1920,0
		c0,259.33,0,518.67,0,778c-0.65,0.13-1.32,0.2-1.94,0.4c-66.4,22.05-132.79,44.12-199.19,66.16
		c-239.52,79.51-479.05,159-718.56,238.53C986.82,1087.58,973.43,1092.36,960,1097z"></path></g><g class="border"><path style="fill:#FFFFFF;" d="M1718.87,844.07c-239.52,79.51-479.05,159-718.56,238.53c-13.49,4.48-26.87,9.26-40.31,13.9
		c-0.67,0-1.33,0-2,0c-1.47-0.66-2.89-1.46-4.41-1.97c-40.23-13.52-80.48-27.01-120.72-40.5c-247-82.84-493.99-165.67-740.98-248.52
		C61.24,795.23,30.63,784.84,0,774.5c0,2,0,4,0,6c30.63,10.34,61.24,20.73,91.88,31.01c246.99,82.85,493.99,165.69,740.98,248.52
		c40.24,13.5,80.49,26.98,120.72,40.5c1.52,0.51,2.94,1.31,4.41,1.97c0.67,0,1.33,0,2,0c13.43-4.64,26.82-9.42,40.31-13.9
		c239.51-79.53,479.04-159.02,718.56-238.53c66.4-22.04,132.79-44.12,199.19-66.16c0.62-0.21,1.29-0.27,1.94-0.4c0-2,0-4,0-6
		c-0.65,0.13-1.32,0.2-1.94,0.4C1851.66,799.95,1785.27,822.03,1718.87,844.07z"></path></g></svg>
			</div>
    <div class="pre-title fade-in loaded">Brand new apartments in Stapleton, CO</div>
    <div class="header-content fade-in vertical-center-parent loaded" style="height: 880px;">
        <div class="vertical-center-child"></div>
    </div>
    <div class="header-bottom">
        <div class="header-line-break ease"><img class="breaks" src="https://mintdenverapts.com/wp-content/themes/mint/images/breaks.png">
            <div class="background"></div>
        </div>
        <div class="scroll"><span class="scroll-title">Scroll</span><span class="icon-mint-triangle"></span></div>
    </div>
    </div>


    <div id="page-content">
        <div class="content-arrow">
            <div class="background"></div><img class="arrow" src="https://mintdenverapts.com/wp-content/themes/mint/images/content-arrow.png"></div>
        <div class="title fade-in loaded">
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
								<h1><?php the_title(); ?></h1>
								
						<?php endwhile; else : ?>
							<p><?php esc_html_e( 'Sorry, no posts matched your criteria.' ); ?></p>
						<?php endif; ?>
        </div>
        <div class="copy fade-in loaded">
						<?php the_content(); ?>
        </div>
        <div class="content-arrow-up"><img class="arrow" src="https://mintdenverapts.com/wp-content/themes/mint/images/up-arrow.png">
            <div class="background"></div>
        </div>
    </div>



  </div>
<?php get_footer(); ?>