<?php
/*
Template Name: FIFA Template
Template Post Type: page
*/
get_header();
?>
<div id="primary" class="content-area template-fifa">
    <main id="main" class="site-main">
        <?php
        while (have_posts()) :
            the_post();
            the_content();
        endwhile;
        ?>
    </main>
</div>
<?php
get_footer();
?>