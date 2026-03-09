<?php
get_header();

// Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get the current page for pagination
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$args = array(
    'post_type'      => 'post',  // Corrected 'post' type
    'posts_per_page' => get_option('posts_per_page'), // Use the admin setting
    'paged'          => $paged,
    'orderby'        => 'date',
    'order'          => 'DESC',
);

$query = new WP_Query($args);
?>

<div class="container">
    <?php
    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            // Fetch the author ID
            $author_id = get_the_author_meta('ID');

            // Fetch the author's name
            $author_name = get_the_author_meta('display_name', $author_id);

            // Fetch the Gravatar (size: 50x50)
            $gravatar_url = get_avatar_url($author_id, array('size' => 50));

            // Fetch the post content
            $post_content = get_post_meta(get_the_ID(), '_post_content', true) ?: get_the_excerpt(); // Fallback to excerpt if custom content not found

            // Get the time format set by the administrator
            $date_format = get_option('date_format');
            $time_format = get_option('time_format');
            $formatted_time = get_the_date($date_format . ' ' . $time_format);

            ?>

            <div class="post">
                <div class="author-info">
                    <img src="<?php echo esc_url($gravatar_url); ?>" alt="<?php echo esc_attr($author_name); ?>'s Gravatar" style="border-radius: 50%; width: 50px; height: 50px; margin-right: 10px;">
                    <strong><?php echo esc_html($author_name); ?></strong>
                </div>
<div class="post-content" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php
    // Get the full content of the post
    $content = get_the_content();
    
    // Strip HTML tags to remove the block comments and other markup
    $clean_content = wp_strip_all_tags($content);
    
    // Limit the cleaned content to 160 characters
    $excerpt = mb_strimwidth($clean_content, 0, 160, '...');
    
    // Output the cleaned and truncated content
    echo '<p>' . esc_html($excerpt) . '</p>';
    ?>
    <small><?php echo esc_html($formatted_time); ?></small>
</div>



            </div>

        <?php endwhile; ?>
		
		<?php
			if (function_exists('wp_link_pages')) {
			wp_link_pages([
			'before' => '<div class="page-links">' . __('Pages:', 'moyna'),
			'after'  => '</div>',
			]);
			}
			?>
			
        <!-- Pagination (Previous / Next buttons only) -->
		<div class="pagination">
			<?php
			previous_posts_link(__('← Previous', 'moyna'));
			next_posts_link(__('Next →', 'moyna'), $query->max_num_pages);
			?>
		</div>


    <?php
    else :
        echo '<p>' . __('No Post Found.', 'moyna') . '</p>';
    endif;

    // Reset post data
    wp_reset_postdata();
    ?>
</div>

<?php get_footer(); ?>