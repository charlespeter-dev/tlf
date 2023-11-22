<?php

/**
 * Template Name: Global Flexible Contents
 * Template Post Type: post
 */

if (is_admin())
    return;

global $post;

$options = get_fields('options');

$fields = get_fields($post->ID);

if ($fields)
    extract($fields);

/**
 * css specifics
 */

wp_enqueue_style('_2x-css-global-flexible-content', sprintf('%s/2x/assets/css/global-flexible-contents.css', get_stylesheet_directory_uri()), ['_2x-css-bootstrap'], time(), 'screen');

get_header() ?>

<script>
    const mql = window.matchMedia("(min-width: 992px)");
    const attach = (e) => {
        if (e.matches) {
            document.body.classList.remove('is-mobile');
            document.body.classList.add('is-desktop');
        } else {
            document.body.classList.remove('is-desktop');
            document.body.classList.add('is-mobile');
        }
    }

    mql.addEventListener('change', attach);
    window.addEventListener('DOMContentLoaded', function () {
        attach(mql);
    });
</script>

<div class="bootstrap-container global-flexible-content">

    <?php

    /**
     * headers
     */

    if (isset($headers) && $headers) {

        foreach ($headers as $header) {

            $tplpath = sprintf('%s/2x/global-flexible-contents/headers/%s.php', get_stylesheet_directory(), $header['acf_fc_layout']);

            if (file_exists($tplpath)) {
                get_template_part(sprintf('2x/global-flexible-contents/headers/%s', $header['acf_fc_layout']), '', $header);
            }
        }
    }

    /**
     * mains
     */

    if (isset($mains) && $mains) {

        foreach ($mains as $main) {

            $tplpath = sprintf('%s/2x/global-flexible-contents/mains/%s.php', get_stylesheet_directory(), $main['acf_fc_layout']);

            if (file_exists($tplpath)) {
                get_template_part(sprintf('2x/global-flexible-contents/mains/%s', $main['acf_fc_layout']), '', $main);
            }
        }
    }

    /**
     * footers
     */

    if (isset($footers) && $footers) {

        foreach ($footers as $footer) {

            $tplpath = sprintf('%s/2x/global-flexible-contents/footers/%s.php', get_stylesheet_directory(), $footer['acf_fc_layout']);

            if (file_exists($tplpath)) {
                get_template_part(sprintf('2x/global-flexible-contents/footers/%s', $footer['acf_fc_layout']), '', $footer);
            }
        }
    }

    ?>

</div>

<?php get_footer() ?>