<?php get_header(); ?>
    <?php print_blog_header_section(); ?>
    <div id="single-post" class="container">
        <div class="single-post-wrapper">
            <div class="left">
                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <div class="post">
                            <div class="post-content">
                                <h1 class="post-title"><?php wp_title(''); ?></h1>
                                <div class="image">
                                    <?php
                                    if ( has_post_thumbnail() ) {
                                        the_post_thumbnail();
                                    } ?>
                                </div>
                                <div class="post-attribution">
                                    <?php
                                    if ($author = get_author_full_name()){
                                        echo '<span>'.$author.'</span>';
                                    }
                                    echo '<span>'.the_date('F j, Y','','',false).'</span>';
                                    ?>
                                </div>
                                <?php the_content(); ?>
                            </div>
                        </div>
                    <?php endwhile;?>
                <?php endif;?>
            </div>
            <div class="right">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
    <?php print_blog_call_outs(); ?>
<?php get_footer(); ?>
