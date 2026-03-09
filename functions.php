<?php
/**
 * Moyna functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Moyna
 */

// Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


/**
 * Theme setup functions
 */
 
function moyna_load_textdomain() {
    load_theme_textdomain(
        'moyna',
        get_template_directory() . '/languages'
    );
}
add_action( 'init', 'moyna_load_textdomain', 0 );


function moyna_theme_setup() {
	
    // Force WordPress to switch from Static page to Latest posts. 
    if (is_admin() && get_option('show_on_front') === 'page') {
        update_option('show_on_front', 'posts');
        update_option('page_on_front', 0);
        update_option('page_for_posts', 0);
    }
	
    // Enable support for dynamic title tag and other features
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');

    // Register menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'moyna'),
        'footer'  => __('Footer Menu', 'moyna'),
    ));
}
add_action('after_setup_theme', 'moyna_theme_setup');

/**
 * Enqueue theme styles
 */
function moyna_enqueue_styles() {
    wp_enqueue_style('moyna-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'moyna_enqueue_styles');



/**
 * Disable Post and Page creation in Admin
 */
function disable_page_creation() {
    remove_menu_page('edit.php?post_type=page'); // Pages
}
add_action('admin_menu', 'disable_page_creation');


// Disable Gutenberg editor for posts
add_filter('use_block_editor_for_post', '__return_false');


// Remove the Tags and Categories submenu
function hide_tags_categories_submenu() {
    // Remove the Tags submenu
    remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=post_tag');
    // Remove the Categories submenu
    remove_submenu_page('edit.php', 'edit-tags.php?taxonomy=category');
}
add_action('admin_menu', 'hide_tags_categories_submenu', 999);


// Post Columns to show only Author, Content and Date 
function customize_post_columns($columns) {
    // Remove default columns
    unset($columns['title'], $columns['categories'], $columns['tags'], $columns['comments']);
    // Add custom columns
$columns['author'] = esc_html(__('Author', 'moyna'));
$columns['content_excerpt'] = esc_html(__('Post', 'moyna'));
$columns['date'] = esc_html(__('Date', 'moyna'));
	
    return $columns;
}
add_filter('manage_posts_columns', 'customize_post_columns');

function customize_post_columns_content($column, $post_id) {
    if ($column === 'content_excerpt') {
        // Get the first 150 characters of post content
        $content = get_post_field('post_content', $post_id);
        $excerpt = wp_trim_words($content, 25, '...'); // Approximately 150 characters
        echo esc_html($excerpt);
    }
}
add_action('manage_posts_custom_column', 'customize_post_columns_content', 10, 2);
function customize_column_widths() {
    echo '<style>
        .column-content_excerpt { width: 100%; }
    </style>';
}
add_action('admin_head', 'customize_column_widths');


// Keep only "Edit" and "Trash" actions
function customize_post_row_actions($actions, $post) {
    // Only keep "Edit" and "Trash"
    return array(
        'edit'  => $actions['edit'],    // Keep the "Edit" action
        'trash' => $actions['trash']    // Keep the "Trash" action
    );
}
add_filter('post_row_actions', 'customize_post_row_actions', 10, 2);


// Disable maximum functions of Post Editor screen
function hide_post_editor_elements() {
    global $pagenow;

    // Check if we are on the post editor screen
    if (in_array($pagenow, array('post.php', 'post-new.php'))) {
        echo '<style>
            /* Hide unnecessary elements */

            #poststuff #titlediv, #edit-slug-box, .editor-post-title, .editor-post-publish-panel, #postbox-container-2, #categorydiv, #tagsdiv-post_tag, #postimagediv, #comment-status, #commentdiv, #authordiv, #postexcerpt, .handlediv, .hndle, #revisionsdiv, #trackbacksdiv, #slugdiv, #post-format-id, #acf-fields, #advanced-image-editor, #wpseo_meta, .wpseo-notice
			{
                display: none !important;
            }
            
            /* Make sure the TinyMCE editor remains visible */
            #post-body-content .editor-post-text-editor {
                display: block !important;
            }
            
            /* Ensure the Publish/Update box is visible */
            #major-publishing-actions {
                display: block !important;
                visibility: visible !important;
            }
        </style>';
    }
}
add_action('admin_head', 'hide_post_editor_elements');



// Disable the WordPress editor and replace it with a plain textarea for the "post" post type.
function replace_tinymce_with_textarea() {
    // Check if we are on the post editor screen (post.php or post-new.php)
    if (in_array($GLOBALS['pagenow'], array('post.php', 'post-new.php'))) {
        // Remove TinyMCE and the visual editor
        remove_post_type_support('post', 'editor'); // Disable the visual editor
        
        // Create a plain textarea for the post content with a 160 character limit
        add_action('edit_form_after_title', 'add_custom_textarea_for_post_content');
    }
}
add_action('admin_init', 'replace_tinymce_with_textarea');


// Add a custom textarea after the post title for the "post" post type
function add_custom_textarea_for_post_content($post) {
    // Get the current content of the post
    $content = get_post_field('post_content', $post->ID);
    ?>
    <div class="form-field">
        <label for="content"><?php echo esc_html__('Post Content (Max 160 characters):', 'moyna'); ?></label><br>
        <textarea name="content" id="content" rows="10" style="width: 100%;" maxlength="160"><?php echo esc_textarea($content); ?></textarea>
        <p class="description"><?php echo esc_html__('You can enter up to 160 characters for this post.', 'moyna'); ?></p>
    </div>
    <?php
}

// Enforce the character limit when saving the post
function enforce_character_limit($post_id) {
    if (isset($_POST['content'])) {
        $content = sanitize_text_field($_POST['content']);
        if (strlen($content) > 160) {
            // If content exceeds 160 characters, truncate or show an error
            $content = substr($content, 0, 160);  // Optionally truncate content
            wp_update_post(array(
                'ID' => $post_id,
                'post_content' => $content,
            ));
        }
    }
}
add_action('save_post', 'enforce_character_limit');

function disable_media_buttons() {
    $screen = get_current_screen();
    if ($screen->post_type === 'post') {
        remove_action('media_buttons', 'media_buttons');
    }
}
add_action('admin_head', 'disable_media_buttons');


/**
 * Remove Comments Dashboard Widget and Comments Menu
 */
function remove_comments_dashboard_widget_and_menu() {
    // Remove Recent Comments Dashboard Widget
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
    // Remove Comments Menu from Admin Sidebar
    remove_menu_page('edit-comments.php');
}
add_action('wp_dashboard_setup', 'remove_comments_dashboard_widget_and_menu');
add_action('admin_menu', 'remove_comments_dashboard_widget_and_menu');


/**
 * Custom Title Format for All Pages
 */
add_filter('document_title_parts', 'moyna_site_title');
function moyna_site_title($title) {
    $site_title = get_bloginfo('name');
    $tagline = get_bloginfo('description');

    // Set the title structure to {site-title} | {tagline} for all pages
    $title['title'] = $site_title . ' | ' . $tagline;

    // Remove the default 'tagline' and 'page' parts
    unset($title['tagline']);
    unset($title['page']);

    return $title;
}


/**
 * Custom Post Class
 */
add_filter('post_class', function($classes) {
    if (is_singular('post')) { // Add class only for singular posts
        $classes[] = 'custom-status-class';
    }
    return $classes;
});


/**
 * Customizer
 */

require get_theme_file_path('/customizer-options.php');

function moyna_ajax_reset_customizer() {
    if (!wp_doing_ajax()) {
        wp_die('Invalid request');
    }

    if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'moyna_reset_nonce')) {
        wp_die('Security check failed', 403);
    }

    if (!current_user_can('edit_theme_options')) {
        wp_die('Insufficient permissions', 403);
    }
    
    remove_theme_mods(); // Reset all Customizer settings
    wp_send_json_success();
    wp_die(); 
}

add_action('wp_ajax_moyna_reset_customizer', 'moyna_ajax_reset_customizer');


/**
 * Theme Info File (Optional for Developers)
 */
require get_theme_file_path('/developers/developers.php');