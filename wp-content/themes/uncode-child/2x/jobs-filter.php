<?php

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
    'order' => 'DESC',
    'fields' => 'ids',
    'post_status' => 'publish'
]);

wp_reset_query();

$vacatures_ids = $vacatures_query->posts;

$jobs_ids = $vacatures_ids;

$categories = get_categories([
    'taxonomy' => 'career-category',
    'hide_empty' => false,
    'orderby' => 'name',
    'order' => 'ASC'
]);

$filtered_categories = [];

foreach ($categories as $category) {
    if ($category->slug != 'jobs-country-list') {
        $filtered_categories[] = $category;
    }
}

$categories = $filtered_categories;

?>

<?php if (isset($jobs_ids)): ?>
    <section class="job-listings">
        <div class="row-container">
            <div class="filters">
                <div id="filters" class="button-group job-filter">
                    <button class="filter-btn is-checked" data-filter="*">All</button>
                    <?php foreach ($categories as $category): ?>
                        <button class="filter-btn" data-filter="<?php echo '.' . $category->slug; ?>">
                            <?php echo $category->name; ?>
                        </button>
                    <?php endforeach ?>
                </div>
            </div>

            <div class="jobs-wrapper">
                <div class="rows grid">
                    <?php foreach ($jobs_ids as $job_id): ?>
                        <a class="job-card grid-item <?php foreach (get_the_category($job_id) as $subcat) {
                            echo $subcat->slug;
                        } ?>" href="<?= get_the_permalink($job_id) ?>" data-category="<?php foreach (get_the_category($job_id) as $subcat) {
                               echo $subcat->slug;
                           } ?>">
                            <div class="job-item h-100">
                                <div class="job-body">
                                    <p class="job-title">
                                        <?= get_the_title($job_id) ?>
                                    </p>
                                    <?php if (get_field('job_country', $job_id)): ?>
                                        <div class="job-location">
                                            <p class="job-country">
                                                <?= get_field('job_country', $job_id) ?>
                                            </p>
                                            <p class="job-city">
                                                <?= get_field('job_city', $job_id) ?>
                                            </p>
                                        </div>
                                    <?php endif ?>

                                    <?php if (get_field('job_type', $job_id)): ?>
                                        <div class="job-type mt-2">
                                            <p>
                                                <?= get_field('job_type', $job_id) ?>
                                            </p>
                                        </div>
                                    <?php endif ?>
                                </div>
                            </div>
                        </a>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </section>
<?php endif ?>