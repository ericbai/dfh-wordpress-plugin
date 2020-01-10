<?php

// see https://wordpress.org/gutenberg/handbook/designers-developers/developers/filters/block-filters/#managing-block-categories
add_filter('block_categories', 'dfh_block_categories', 10, 2);
function dfh_block_categories($categories, $post) {
    // Note that the order specified here is the order the categories will display
    return array_merge(
        array(
            array(
                'slug'  => DFH_BLOCK_CATEGORY_COMMON,
                'title' => __('Common', DFH_TEXT_DOMAIN),
            ),
            array(
                'slug'  => DFH_BLOCK_CATEGORY_LAYOUT,
                'title' => __('Layout', DFH_TEXT_DOMAIN),
            ),
            array(
                'slug'  => DFH_BLOCK_CATEGORY_MEDIA,
                'title' => __('Media', DFH_TEXT_DOMAIN),
            ),
            array(
                'slug'  => DFH_BLOCK_CATEGORY_LANDING,
                'title' => __('Landing Page', DFH_TEXT_DOMAIN),
            ),
            array(
                'slug'  => DFH_BLOCK_CATEGORY_RESOURCE,
                'title' => __('Resources', DFH_TEXT_DOMAIN),
            ),
            array(
                'slug'  => DFH_BLOCK_CATEGORY_TOOLKIT,
                'title' => __('Toolkits', DFH_TEXT_DOMAIN),
            ),
        ),
        $categories
    );
}

// see https://jasonyingling.me/enqueueing-scripts-and-styles-for-gutenberg-blocks/
add_action('enqueue_block_editor_assets', 'dfh_register_editor_blocks');
function dfh_register_editor_blocks() {
    // Register the block editor script.
    wp_enqueue_script(
        'dfh-editor-script', // label
        plugins_url('/build/index.js', DFH_PLUGIN_ROOT), // URL to script file
        array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-block-editor', 'wp-data', 'wp-hooks', 'wp-api-fetch'), // dependencies
        filemtime(DFH_PLUGIN_DIR . '/build/index.js') // is a file path, set version as file last modified time
    );
    // see https://wordpress.org/gutenberg/handbook/designers-developers/developers/internationalization/
    if (function_exists('wp_set_script_translations')) {
        wp_set_script_translations(
            'dfh-editor-script',
            DFH_TEXT_DOMAIN,
            DFH_PLUGIN_DIR . '/languages'
        );
    }
    // Register the block editor stylesheet.
    wp_enqueue_style(
        'dfh-editor-styles', // label
        plugins_url('/build/editor.css', DFH_PLUGIN_ROOT), // URL to CSS file
        array('wp-edit-blocks'), // dependencies
        filemtime(DFH_PLUGIN_DIR . '/build/editor.css') // is a file path, set version as file last modified time
    );
}

// see https://jasonyingling.me/enqueueing-scripts-and-styles-for-gutenberg-blocks/
add_action('enqueue_block_assets', 'dfh_register_frontend_blocks');
function dfh_register_frontend_blocks() {
    // Styles and scripts needed for ONLY the frontend
    // see https://github.com/WordPress/gutenberg/issues/1893#issuecomment-315240663
    if (!is_admin()) {
        wp_enqueue_script(
            'dfh-frontend-script', // label
            plugins_url('/build/frontend.js', DFH_PLUGIN_ROOT), // URL to script file
            array('lodash', 'jquery'), // dependencies
            filemtime(DFH_PLUGIN_DIR . '/build/frontend.js') // is a file path, set version as file last modified time
        );
        // see https://wordpress.org/gutenberg/handbook/designers-developers/developers/internationalization/
        if (function_exists('wp_set_script_translations')) {
            wp_set_script_translations(
                'dfh-frontend-script',
                DFH_TEXT_DOMAIN,
                DFH_PLUGIN_DIR . '/languages'
            );
        }
        // Register the frontend stylesheet.
        wp_enqueue_style(
            'dfh-frontend-styles', // label
            plugins_url('/build/style.css', DFH_PLUGIN_ROOT), // URL to CSS file
            array(), // dependencies
            filemtime(DFH_PLUGIN_DIR . '/build/style.css') // is a file path, set version as file last modified time
        );
    }
}

add_action('init', 'dfh_register_dynamic_blocks');
function dfh_register_dynamic_blocks() {
    // if Block Editor is not active, bail.
    if (!function_exists('register_block_type')) {
        return;
    }
    // scripts and stylesheets already registered in editor-specific hooks
    // in these files, we only need to specify the render_callback AND attributes
    // see https://github.com/WordPress/gutenberg/issues/6187#issuecomment-381446732
    require DFH_PLUGIN_DIR . '/inc/php/setup/block/page_title.php';
    require DFH_PLUGIN_DIR . '/inc/php/setup/block/resource_category_filter.php';
    require DFH_PLUGIN_DIR . '/inc/php/setup/block/resource_detail_info.php';
    require DFH_PLUGIN_DIR . '/inc/php/setup/block/resource_overview.php';
    require DFH_PLUGIN_DIR . '/inc/php/setup/block/toolkit_detail_header.php';
}
