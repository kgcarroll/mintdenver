<!DOCTYPE html>
<html>
<head>
    <title><?php wp_title(''); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!--[if lte IE 8]>
    <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/css/ie.css">
    <script src="<?php bloginfo('template_url'); ?>/js/html5shiv.js"></script>
    <![endif]-->

    <script>var templateURL="<?php bloginfo("template_url"); ?>";</script>
    <!-- analytics -->
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-38538327-18', 'auto');
        ga('send', 'pageview');
    </script>
    <!-- end analytics -->
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-5K45FS5');
    </script>
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-KBW3NBJ');</script>
    <!-- End Google Tag Manager -->

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
    </script>
    <noscript>
     <img height="1" width="1" 
    src="https://www.facebook.com/tr?id=783345711865140&ev=PageView
    &noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5K45FS5"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KBW3NBJ"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<div id="wrapper">
    <?php print_slidout(); ?>
    <header id="header" class="ease">
        <div class="header-left">
            <div class="desktop-elements">
                <div id="chat">
                    <a id="_lpChatBtn" href='https://home-c9.incontact.com/inContact/ChatClient/ChatClientPatron.aspx?poc=d5c2dd18-3b2a-45f2-9cbb-639fa698d4af&bu=4593739' name='hcIcon' alt='Chat Button' onclick='window.open("https://home-c9.incontact.com/inContact/ChatClient/ChatClientPatron.aspx?poc=d5c2dd18-3b2a-45f2-9cbb-639fa698d4af&bu=4593739", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,width=475,height=400");return false;'>
                        <span class="icon-chat ease"></span>
                    </a>
                    <button id="schedule-tour-popup-button" class="ease schedule-a-tour-button" title="Schedule a Tour"><i class="fa fa-calendar"></i></button>
                </div>
                <?php if(get_field('resident_login_url','options') or get_field('leasing_login_url','options')) { ?>
                    <div class="resident-quicklinks">
                        <?php print_resident_login(); ?>
                        <?php print_leasing_login(); ?>
                    </div>
                <?php } ?>
                <div class="quick-signup ease">
                    <?php if(is_front_page()) { ?>
                        <a class="jump ease" href="#home-contact-form">Sign-up for updates</a>
                    <?php } elseif(is_page_template('page-templates/contact.php')) { ?>
                        <a class="jump ease" href="#contact-form">Sign-up for updates</a>
                    <?php } else { ?>
                        <a class="ease" href="/contact">Sign-up for updates</a>
                    <?php } ?>
                </div>
            </div>
            <div class="mobile-elements">
                <?php print_mobile_address_link(); ?>
                <?php print_mobile_phone_link(); ?>
                <button id="schedule-tour-popup-button" class="ease mobile-icon schedule-a-tour-button" title="Schedule a Tour"><i class="fa fa-calendar"></i></button>
            </div>
        </div>
        <?php print_logo(); ?>
        <nav id="priority-menu"><?php wp_nav_menu( array( 'container' => '', 'theme_location' => 'priority_menu') ); ?></nav>
    </header>
    <div id="main-menu-wrapper" style="display:none;">
        <div class="menu-logo"><a href="/"><img src="<?php bloginfo('template_url') ?>/images/menu-logo.png" /></a></div>
        <?php $menu_bg = get_field('default_menu_background','options'); ?>
        <div id="menu-background" class="ease" style="background-image: url('<?php echo $menu_bg;?>');"></div>
        <div id="background-color"></div>
        <div id="main-menu-container">
            <nav id="navigation">
                <div class="vertical-center-parent">
                    <div class="vertical-center-child">
                        <div class="nav-inner-wrap">
                            <div class="menu-container"><?php wp_nav_menu( array( 'container' => '', 'theme_location' => 'main_menu') ); ?></div>
                            <div id="resident-links">
                                <?php print_resident_login(); ?>
                                <?php print_leasing_login(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div>
<?php print_trigger(); ?>