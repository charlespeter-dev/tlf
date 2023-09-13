<?php

/**
 * Template Name: v2 / Resources / Subpage v1
 * Template Post Type: resources
 */

 if (is_admin())
 return;

global $post;

$options = get_fields('options');

$fields = get_fields($post->ID);

print_r($fields); exit;