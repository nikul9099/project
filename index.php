<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */


define( 'WP_USE_THEMES', true );
ERROR_REPORTING(E_ALL);
ini_set("display_errors","2");

/** Loads the WordPress Environment and Template */
require __DIR__ . '/wp-blog-header.php';
