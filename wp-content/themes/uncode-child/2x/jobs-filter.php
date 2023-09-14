<?php

/**
 * Template Name: v2 / Jobs Section with filter
 * Template Post Type: post
 */

 global $post;

 $options = get_fields('options');
 
 $fields = get_fields($post->ID);
 
 extract($fields);
 
/**
 * vacatures
 */

$vacatures_query = new WP_Query([
    'post_type' => 'vacatures',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order'   => 'DESC',
    'fields' => 'ids',
    'post_status' => 'publish'
]);
wp_reset_query();
$vacatures_ids = $vacatures_query->posts;
$jobs_ids = $vacatures_ids;

// category parent slug & get the id
$slug = 'jobs-country-list'; 
$cat = get_category_by_slug($slug);  
$catID = $cat->term_id;

$categories = get_categories( array(
    'orderby' => 'name',
    'child_of'  => $catID,
    'hide_empty' => FALSE, // to also display categories that has no post
    'order'   => 'ASC'
) );
?>

<?php if (isset($jobs_ids)): ?>
    <section class="job-listings">
        <div class="row-container">
            <div class="filters">
                <ul class="job-filter">
                    <li class="is-checked" data-filter="*">All</li>
                    <?php foreach ($categories as $category): ?>
                        <li data-filter="<?php echo '.' . $category->slug; ?>"> <?php echo $category->name; ?> </li>
                    <?php endforeach ?>
                </ul>
            </div>
            <div class="jobs-wrapper">
                <div class="rows grid">
                    <?php foreach ($jobs_ids as $job_id): ?>
                    <a class="job-card grid-item <?php foreach(get_the_category($job_id) as $subcat) { echo $subcat->slug;} ?>" href="<?= get_the_permalink($job_id) ?>" data-category="<?php foreach(get_the_category($job_id) as $subcat) { echo $subcat->slug;} ?>">
                        <div class="job-item h-100">
                            <div class="job-body">
                                <p class="job-title">
                                    <?= get_the_title($job_id) ?>
                                </p>
                                <div class="job-location">
                                    <p class="job-country"><?= get_field('job_country', $job_id) ?></p>
                                    <p class="job-city"><?= get_field('job_city', $job_id) ?></p>
                                </div>
                                <div class="job-type">
                                    <p><?= get_field('job_type', $job_id) ?></p>
                                </div>
                            </div>
                        </div>
                    </a>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </section>
<?php endif ?>
